<?php 

$path = preg_replace('/wp-content.*$/','',__DIR__);
require_once($path.'/wp-load.php');


mnrPart( 'templates/main'); 


?>