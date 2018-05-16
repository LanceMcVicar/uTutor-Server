<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		$email = $_POST['email'];
		$defaultWorkHours = "[]";
		$sql = "INSERT INTO `tutors`(`userEmail`,`workHours`) VALUES (?,?)";
		if(!$stmt = $conn->prepare($sql)){	
			$err = array('error' => -3 , 'errormessage' => 'Query Failed to Prepare');
			echo json_encode($err);
			die;
		}		
		if(!$stmt -> bind_param("ss",$email,$defaultWorkHours)){	
			$err = array('error' => -2 , 'errormessage' => 'Failed to Bind Parameter');
			echo json_encode($err);
			die;
		}			
		if($stmt->execute()){//Successful Query
			$result = mysqli_affected_rows($conn);
			if($result>0){//Something was affected
				$arr = array('success' => "true");
				echo json_encode($arr);
			}else if($result==0){//Nothing Was Affected
				$err = array('error' => -4 , 'errormessage' => 'No Rows were Affected');
				echo json_encode($err);
				die;
			}else{//Error Occurred
				$err = array('error' => -5 , 'errormessage' => 'Unspecified Error Occurred, ErrorNo' . mysqli_errno($conn));
				echo json_encode($err);
				die;				
			}

		}else{//Unsuccessful Query	
			$err = array('error' => -1 , 'errormessage' => 'Failed to Execute');
			echo json_encode($err);
			die;
		}	
	}
?>