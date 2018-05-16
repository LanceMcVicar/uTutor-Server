<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		$email = $_POST['email'];
		$profilePicNum = $_POST['profilePicNum'];
		$sql = "UPDATE `users` SET `profilePic`=? WHERE `email` = ?";
		$stmt = $conn->prepare($sql);
		$stmt -> bind_param("ss" , $profilePicNum, $email);
		if(!$stmt->execute()){
			$err = array('error' => -2 , 'errormessage' => 'Query Failed. '.mysqli_errno($conn));
			echo json_encode($err);
			die;
		}else{
			$result = mysqli_affected_rows($conn);
			if($result>0){//Something was affected
				$arr = array('success' => "true");
				echo json_encode($arr);
			}else if($result==0){
				$err = array('error' => -1 , 'errormessage' => 'Current Email is not in Database');
				echo json_encode($err);
				die;
			}else{ //Something went wrong
				$err = array('error' => -3 , 'errormessage' => 'Something Catastrophic Happened Failed. '.mysqli_errno($conn));
				echo json_encode($err);
				die;
			}
		}
	}
?>