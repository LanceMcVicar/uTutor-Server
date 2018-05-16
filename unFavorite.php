<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("realconnect.php");
		$requiredParameters = array("favoriteeEmail","favoritorEmail");
		$optionalParameters = array();
		foreach($requiredParameters as $param){
			if(!isset($_POST[$param])){
				$err = array('error' => -4 , 'errormessage' => $param . " parameter is missing");
				echo json_encode($err);
				die;
			}
		}
		foreach($optionalParameters as $param) {
			if(!isset($_POST[$param])){
				$_POST[$param]=null;
			}
		}
		$sql = "DELETE FROM favorites
				WHERE favoritorEmail = ? AND favoriteeEmail=?"; 
		if(!$stmt = $conn->prepare($sql)){
			$err = array('error' => -2 , 'errormessage' => 'Query Failed to Prepare'. mysqli_errno($conn));
			echo json_encode($err);
		}
		if(!$stmt -> bind_param("ss" ,$_POST["favoritorEmail"], $_POST["favoriteeEmail"])){
			$err = array('error' => -2 , 'errormessage' => 'Query Failed to Bind'. mysqli_errno($conn));
			echo json_encode($err);
		}
		if($stmt->execute()){
			if(mysqli_affected_rows($conn)>0){//Must use Real Connect with FOUND_ROWS flag on
				$arr = array('success' => "true");
				echo json_encode($arr);
			}else{//No Users Matched, Probably because at least one email isn't a user
				$err = array('error' => -1 , 'errormessage' => 'At least one email isn\'t a user');
				echo json_encode($err);
				die;				
			}
		}else{
			$err = array('error' => -3 , 'errormessage' => 'Query Failed to Execute' . mysqli_errno($conn));
			echo json_encode($err);
			die;	
		}
	}
?>