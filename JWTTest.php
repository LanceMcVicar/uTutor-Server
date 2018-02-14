<?php
	$header = ['typ' => 'JWT' , 'alg' => 'HS256'];
	$header = json_encode($header);
	$header = base64_encode($header);
	print_r($header);
	
	
	

?>