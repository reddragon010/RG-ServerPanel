<?php
//Based on Zebra_Session by Stefan Gabos <contact@stefangabos.ro>

class SessionManager {
    
    static private $instance;
    
    private $table_name = 'sessions';
    private $db_name = 'web';
    private $db;
    private $session_lifetime;
    private $security_code = 'sEcUr1tY_c0dE';
    private $lock_timeout = 60;
    private $locking = false;
    
    public static function start(){
        static::$instance = new SessionManager();
        session_start();
    }
    
    public static function save(){
        session_write_close();
    }
    
    public function get_instance(){
        return static::$instance;
    }
    
    private function __construct() {
        $this->db = DatabaseManager::get_database($this->db_name);
        $this->session_lifetime = ini_get('session.gc_maxlifetime');
        session_set_save_handler(
                array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc')
        );
    }
    
    public function regenerate_id() {
        $old_session_id = session_id();
        session_regenerate_id();
        $this->destroy($old_session_id);
    }

    public function stop() {
        $this->regenerate_id();
        session_unset();
        session_destroy();
    }
    
    public function get_active_sessions() {
        $this->gc($this->session_lifetime);
        
        $result = $this->db->query_and_fetch_one(
            'SELECT COUNT(session_id) as count FROM {$this->table_name}'
        );

        return $result['count'];
    }

    public function close() {
        if($this->locking)
            $this->db->query('SELECT RELEASE_LOCK(:lock)', array(':lock' => $this->session_lock));
        return true;
    }

    public function destroy($session_id) {
        $result = $this->db->query("
            DELETE FROM {$this->table_name} WHERE session_id = :id", array(':id' => $session_id)
        );

        if ($result->rowCount() !== -1) {
            return true;
        }

        return false;
    }

    public function gc($maxlifetime) {
        $this->db->query(
            "DELETE FROM {$this->table_name} WHERE session_expire < :expire", 
            array(':expire' => time() - $maxlifetime)
        );
    }

    public function open($save_path, $session_name) {
        return true;
    }

    public function read($session_id) {
        if($this->locking){
            $this->session_lock = 'session_' . $session_id;
            $result = $this->db->query(
                'SELECT GET_LOCK(:lock, :timeout)',
                array(':lock' => $this->session_lock, ':timeout' => $this->lock_timeout)
            );
            
            if ($result->rowCount() != 1) {
                throw new Exception('Cant unlock session');
            }
        }

        //  reads session data associated with a session id, but only if
        //  -   the session ID exists;
        //  -   the session has not expired;
        //  -   the HTTP_USER_AGENT is the same as the one who had previously been associated with this particular session;
        $paras = array(
            ':id' => $session_id, 
            ':expire' => time(), 
            ':agent' => md5((isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') . $this->security_code)
        );

        $result = $this->db->query("
            SELECT
                session_data
            FROM
                {$this->table_name}
            WHERE
                session_id = :id AND
                session_expire > :expire AND
                http_user_agent = :agent
            LIMIT 1
        ", $paras);

        if ($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            return $row['session_data'];
        }

        // on error return an empty string - this HAS to be an empty string
        return '';
    }

    public function write($session_id, $session_data) {
        $paras = array(
            ':id' => $session_id, 
            ':agent' => md5((isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') . $this->security_code),
            ':data' => $session_data,
            ':expire' => time() + $this->session_lifetime,
            ':data' => $session_data,
            ':expire' => time() + $this->session_lifetime
        );

        $result = $this->db->query("
            INSERT INTO
                {$this->table_name} (
                    session_id,
                    http_user_agent,
                    session_data,
                    session_expire
                )
            VALUES (:id, :agent, :data, :expire)
            ON DUPLICATE KEY UPDATE
                session_data = :data, session_expire = :expire
        ", $paras);

        if ($result) {
            // note that after this type of queries, mysql_affected_rows() returns
            // - 1 if the row was inserted
            // - 2 if the row was updated
            // if the row was updated
            if ($result->rowCount() > 1) {
                return true;
            } else {
                return '';
            }
        }

        return false;
    }

}

?>
