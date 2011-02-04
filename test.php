<?php
require_once('common.php');

$tpl = $twig->loadTemplate('test.tpl');
echo $tpl->render(array());
?>