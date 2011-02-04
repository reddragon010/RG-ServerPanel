<?php 
require_once('common.php');

$db = new Database($config,$config['db']['webdb']);
$sql = "SELECT * FROM `news` ORDER BY `date` DESC;";
$db->query($sql);
$numrows = $db->count();
$news = array();
if($numrows > 0){
	while($row = $db->fetchRow()){;
		$news[] = array(
			'id' => $row["id"],
			'date' => $row["date"],
			'title' => $row["title"],
			'content' => $row["content"],
			'author' => $row["author"]
		);
	}
}

$tpl = $twig->loadTemplate('site_home.tpl');
echo $tpl->render(array('news' => $news));
?>