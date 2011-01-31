<?php  
require_once(dirname(__FILE__) . '/common.inc.php');
if (!isset ($_SESSION["userid"]))  
{  
  header ("Location: index.php");  
}  
?> 