<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("realconnect.php");
		$email = $_POST['email'];
		$workHours = $_POST['workHours'];
		$sql = "UPDATE `tutors` SET `workHours`=? WHERE `userEmail` = ?";
		if(!$stmt = $conn->prepare($sql)){
			$err = array('error' => -2 , 'errormessage' => 'Query Failed to Prepare'. mysqli_errno($conn));
			echo json_encode($err);
		}
		if(!$stmt -> bind_param("ss" ,$workHours, $email)){
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