<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		if(!isset($_POST['email'])){
			$err = array('error' => -4 , 'errormessage' => 'No Email Sent');
			echo json_encode($err);
			die;
		}
		$email = $_POST['email'];
		$sql = "SELECT email, firstName, lastName, university, profilePic, userDescription ,`phoneNumber`, AVG(COALESCE(ratings.rating,0)) AS averageRating, GROUP_CONCAT(SubjectName ORDER BY SubjectName DESC SEPARATOR ',') AS Subjects FROM users
					LEFT JOIN subjecttutor ON users.email=subjecttutor.tutorEmail
					LEFT JOIN ratings ON users.email = ratings.recipientEmail
					WHERE email=?
					GROUP BY email";
		$stmt = $conn->prepare($sql);
		$stmt-> bind_param("s",$email);
		if($stmt->execute()){
			$result = $stmt->get_result();
			if(mysqli_num_rows($result)>0){
				$rows = array();
				while($row = $result->fetch_assoc()){
					$rows = $row;
				}	
				echo json_encode($rows);
			}else{
				$err = array('error' => -2 , 'errormessage' => ' No Results');
				echo json_encode($err);
				die;
			}
		}else{ // Query Fails
			$err = array('error' => -1 , 'errormessage' => ' Query Failed to Execute');
			echo json_encode($err);
			die;
		}
	}
?>