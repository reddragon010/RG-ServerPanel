<?php 
require_once('common.php');

$sql = "SELECT * FROM `news` ORDER BY `date` DESC";
$db_web->query($sql);
$numrows = $db_web->count();
$news = array();
if($numrows > 0){
	while($row = $db_web->fetchRow()){;
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