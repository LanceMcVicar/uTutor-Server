<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		$newEmail = $_POST['newEmail'];
		$currentEmail = $_POST['currentEmail'];
		$currentPassword = $_POST['currentPassword'];
		if($_POST['newPassword'] ==""){
			$newPassword = $currentPassword;
		}else{
			$newPassword = $_POST['newPassword'];
		}
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$university = $_POST['university'];
		$description = $_POST['description'];
		$phoneNumber = $_POST['phoneNumber'];
		$sql = "SELECT `email` FROM `users` where `email` = ? AND `hashedPassword` = ?";
		$stmt = $conn->prepare($sql);
		$stmt -> bind_param("ss" , $currentEmail, $currentPassword);
		if(!$stmt->execute()){
			$err = array('error' => -2 , 'errormessage' => 'Statement Failed to Form.'.mysqli_errno($conn));
			echo json_encode($err);
			die;
		}

		$result = $stmt->get_result();
		if($result->num_rows > 0){//If the Current user exists and has the right password
			$sql = "UPDATE `users` SET `email`=?,`firstName`=?,`lastName`=?,`hashedPassword`=?,`university`=?,`userDescription`=? , `phoneNumber` =? WHERE `email` = ?";
			$stmt = $conn->prepare($sql);
			$stmt -> bind_param("ssssssss",$newEmail,$firstName,$lastName,$newPassword,$university,$description,$phoneNumber, $currentEmail);
			if($stmt->execute()){
				$result = $stmt->get_result();
				echo json_encode(array('success' =>"true"));
			}else{
				switch(mysqli_errno($conn)){
					case 1062:
						$err = array('error' => -3 , 'errormessage' => 'Update Failed Due to New Email Already Existing');
						echo json_encode($err);
						die; 
					default:
						$err = array('error' => -4 , 'errormessage' => 'Update Failed Due to an Unspecified Error '.mysqli_errno($conn));
						echo json_encode($err);
						die; 						
				}
			}
		}else{//Current User's Email and Password is incorrect
			$err = array('error' => -1 , 'errormessage' => 'Current Email/Password is not in Database');
			echo json_encode($err);
			die;
		}
	}
?>