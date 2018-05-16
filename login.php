<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
	//	$_POST['email']="test@test.test";
	//	$_POST['password']="wow";
		if($_POST['email']==""){
			$err = array('error' => -4 , 'errormessage' => 'No Email Sent');
			echo json_encode($err);
			die;
		}
		$email = $_POST['email'];
		if($_POST['password']==""){
			$err = array('error' => -5 , 'errormessage' => 'No Password Sent');
			echo json_encode($err);
			die;
		}
		$password = $_POST['password'];

		//$sql = "SELECT `firstName`, `lastName`, `email`, `hashedPassword`, `onlineStatus` FROM `users` WHERE `email` = \"lance@lance.lance\"";
		//	$sql = "SELECT `email` FROM `users` where `email` = \"".$email."\"";
		
		$sql = "SELECT `email` FROM `users` WHERE `email` = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s" , $email);
		if($stmt->execute()){//Check to see if email exists
			$result = $stmt->get_result();
			if(mysqli_num_rows($result)>0){
				
				$sql = "SELECT `firstName`, `lastName`, `email`,`userDescription` ,`profilePic`, `phoneNumber` FROM `users` WHERE `email` = ?  AND `hashedPassword` = ?";
				$stmt = $conn->prepare($sql);
				$stmt-> bind_param("ss",$email,$password);
				
				if($stmt->execute()){//Check to see if password matches email
					$result2 = $stmt->get_result();
					if(mysqli_num_rows($result2)>0){
						$rows = array();
						while($row = $result2->fetch_assoc()){
							$rows = $row;
						}	
						$sql = "SELECT `userEmail` FROM `tutors` WHERE `userEmail` = ?";
						$stmt = $conn->prepare($sql);
						$stmt -> bind_param("s",$email);
						if($stmt->execute()){//Check to see if the user is a tutor
							$result3 = $stmt->get_result();						
							if(mysqli_num_rows($result3)>0){
								$rows['isTutor'] = 'true';
							}else{
								$rows['isTutor'] = 'false';
							}
						}
						$rows['success'] ='true';
						echo json_encode($rows);
					}else{//No Password Matches
						$err = array('error' => -3 , 'errormessage' => 'Invalid Password');
						echo json_encode($err);
						die;
					}
				}else{ //Second Query Fails
					$err = array('error' => -1 , 'errormessage' => 'Second Query Failed to Execute');
					echo json_encode($err);
					die;
				}
			}else{ //No Email Results
				$err = array('error' => -2 , 'errormessage' => 'Invalid Email');
				echo json_encode($err);
				die;
			}
		}else{ //First Query Fails
			$err = array('error' => -1 , 'errormessage' => 'First Query Failed to Execute');
			echo json_encode($err);
			die;
		}
	}
?>