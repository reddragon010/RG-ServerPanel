<?php
require_once('common.php');

$tpl = $twig->loadTemplate('tools.tpl');
echo $tpl->render(array());
?>