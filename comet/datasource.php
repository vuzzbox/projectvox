<?php
require_once 'voxMenu.php';

$session_id = 'xyz12345'; 
$command = $_REQUEST['command']; 

$num = rand(1, 1000);

if (!putVoxCommand($session_id,$command)) {
	echo 'fail';
}	


?>