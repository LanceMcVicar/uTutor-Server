<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		$whereClause = "";
		if(!isset($_POST['email'])){
			$err = array('error' => -1 , 'errormessage' => 'No Email Sent');
			echo json_encode($err);
			die;
		}
		$email = $_POST['email'];
/* 		if(isset($_POST['tutorEmail'])&&!isset($_POST['tuteeEmail'])){
			$whereClause = "WHERE tutorEmail = ?";
			$email = $_POST['tutorEmail'];
		}else if(!$_POST['tutorEmail'])&& isset($_POST['tuteeEmail'])){
			$whereClause = "WHERE tuteeEmail = ?";
			$email = $_POST['tutorEmail'];
		}else{
			$err = array('error' => -1 , 'errormessage' => 'No Email Sent');
			echo json_encode($err);
			die;
		} */
		$sql = "SELECT u.firstName AS tuteeFirstName, u.lastName AS tuteeLastName, tuteeEmail, users.firstName AS tutorFirstName, users.lastName AS tutorLastName, tutorEmail , startAppDateTime, endAppDateTime , is_accepted
				FROM users as u
				INNER JOIN appointments as app ON u.email=app.tuteeEmail
				INNER JOIN users ON app.tutorEmail = users.email
				WHERE 	tuteeEmail = ?
					OR
						tutorEmail = ?";
		$stmt = $conn->prepare($sql);
		$stmt-> bind_param("ss",$email,$email);
		if($stmt->execute()){
			$result = $stmt->get_result();
			$rows = array();
			if(mysqli_num_rows($result)>0){
				while($row = $result->fetch_assoc()){
					$rows[] = $row;
				}	
			}
			echo json_encode($rows,JSON_FORCE_OBJECT);
		}else{ // Query Fails
			$err = array('error' => -2 , 'errormessage' => ' Query Failed to Execute');
			echo json_encode($err);
			die;
		}
	}
?>