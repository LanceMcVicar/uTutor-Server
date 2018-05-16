<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		$email = $_POST['email'];
		$password = $_POST['password'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		if(isset($_POST['university'])){
			$university = $_POST['university'];
		}else{
			$university = null;
		}
		$sql = "INSERT INTO `users`(`email`,`hashedPassword`,`firstName`,`lastName`,`university`) VALUES (?,?,?,?,?)";
		//$sql= "INSERT INTO users (email,hashedPassword,firstName,lastName,university) VALUES('".$email."' ,'".$password. "','".$firstname. "','".$lastname. "','".$university. "')";
		if(!$stmt = $conn->prepare($sql)){	
			$err = array('error' => -3 , 'errormessage' => 'Query Failed to Prepare');
			echo json_encode($err);
			die;
		}		
		if(!$stmt -> bind_param("sssss",$email,$password,$firstname,$lastname,$university)){	
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
			}else{//Error Occured
				$err = array('error' => -5 , 'errormessage' => 'Unspecified Error Occurred, ErrorNo' . mysqli_errno($conn));
				echo json_encode($err);
				die;				
			}

		}else{//Unsuccessful Query	
			switch(mysqli_errno($conn))	{
				case 1062: //Duplicate Primary Key, User wants to change to an email that already exists
					$err = array('error' => -6 , 'errormessage' => 'Query Failed due to Email Already Existing');
					echo json_encode($err);
					die;
				default: //Unhandled SQL Exception occured
					$err = array('error' => -7 , 'errormessage' => 'Query failed at Execution due to an unspecified error');
					echo json_encode($err);
					die;
			}	
		}
	}
?>