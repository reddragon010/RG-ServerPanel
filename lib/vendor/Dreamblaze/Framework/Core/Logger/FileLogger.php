<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 18.04.12
 * Time: 22:09
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Framework\Core\Logger;
use Dreamblaze\GenericLogger\Logger;

class FileLogger implements \Dreamblaze\GenericLogger\Observer
{
    private $type_names = array(
        1 => 'ERROR',
        2 => 'WARN',
        3 => 'NOTE',
        4 => 'DBG'
    );

    private $uid;
    private $level = 4;
    private $cache = array();
    private $file_path;
    private $request_start;
    private $request_end;

    public function __construct($file_path){
        $this->file_path = $file_path;
    }

    public function OnInit($level)
    {
        $this->uid = uniqid();
        $this->level = $level;
        $this->request_start = microtime(true);

        $start_msg = 'Request from ' . $_SERVER['REMOTE_ADDR'] . " to " . $_SERVER['REQUEST_URI'];
        $this->push(Logger::TYPE_NOTICE,$start_msg);
    }

    public function OnEnd()
    {
        $this->request_end = microtime(true);
        $exec_time = floor(($this->request_end - $this->request_start) * 1000);
        $msg =  sprintf('End Request to %s (%s)', $_SERVER['REQUEST_URI'] ,$exec_time . 'ms');

        if($exec_time > 3000)
            $type = Logger::TYPE_WARNING;
        else
            $type = Logger::TYPE_NOTICE;

        $this->push($type, $msg);
    }

    public function OnDebug($msg,$label=null)
    {
        if(is_object($msg) || is_array($msg)){
            $msg = var_export($msg, true);
        } else {
            $msg = self::stripNewline($msg);
        }
        $this->push(Logger::TYPE_DEBUG,$msg);
    }

    public function OnNotice($msg,$label=null)
    {
        $this->push(Logger::TYPE_NOTICE,$msg);

    }

    public function OnWarning($msg,$label=null)
    {
        $this->push(Logger::TYPE_WARNING,$msg);
    }

    public function OnError($msg,$label=null)
    {
        $this->push(Logger::TYPE_ERROR,$msg);
    }

    private function push($type, $msg){
        if($this->level >= $type){
            if(is_array($msg)) $msg = join("\n", $msg);
            $output = '[' . $this->type_names[$type] . '] [' . strftime("%y%m%d %T",time()) . '] ' . $this->uid . ' -> ' . $msg . "\n";
            self::write($output);
        }
    }

    private function write($text){
        if(!file_exists($this->file_path))
            touch($this->file_path);

        $fh = fopen($this->file_path,'a');
        fwrite($fh,$text);
        fclose($fh);
        $this->cache = '';
    }

    public function OnGroupEnter($label)
    {
        // TODO: Implement OnGroupEnter() method.
    }

    public function OnGroupLeave()
    {
        // TODO: Implement OnGroupLeave() method.
    }

    private static function stripNewline($text){
        $text = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $text);
        return trim(preg_replace("/\s+/", ' ',$text));
    }
}
