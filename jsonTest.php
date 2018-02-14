<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		{	
		$array = array('Hi' => 'Hi','Puffy' => 'AmiYumi');
		echo json_encode($array);
		}
?>