<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("realconnect.php");
		$requiredParameters = array("email","lat","long");
		foreach($requiredParameters as $param){
			if(!isset($_POST[$param])){
				$err = array('error' => -4 , 'errormessage' => $param . " parameter is missing");
				echo json_encode($err);
				die;
			}
		}
		$email = $_POST['email'];
		$lat = $_POST['lat'];
		$long = $_POST['long'];
		$sql = "UPDATE tutors SET workLat=? , workLong=? WHERE userEmail = ?";
		if(!$stmt = $conn->prepare($sql)){
			$err = array('error' => -2 , 'errormessage' => 'Query Failed to Prepare'. mysqli_errno($conn));
			echo json_encode($err);
		}
		if(!$stmt -> bind_param("sss" ,$lat, $long, $email)){
			$err = array('error' => -2 , 'errormessage' => 'Query Failed to Bind'. mysqli_errno($conn));
			echo json_encode($err);
		}
		if($stmt->execute()){
			if(mysqli_affected_rows($conn)>0){//Must use Real Connect with FOUND_ROWS flag on
				$arr = array('success' => "true");
				echo json_encode($arr);
			}else{//No Users Matched, Email is not a tutor
				$err = array('error' => -1 , 'errormessage' => 'Email is not a tutor');
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