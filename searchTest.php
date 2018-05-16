<?php

$_POST['firstName']="bob";

		require_once("connect.php");
		$param_types = '';
		$params = array();
		$filters = array("firstName","lastName","subject","university","rating");
		foreach($filters as $filter){
			if(isset($_POST[$filter])){
				if(!empty($_POST[$filter])){
					$params[$filter] = $_POST[$filter];
					$param_types.="s";
				}
			}
		}
		$sql = "SELECT users.firstName, users.lastName, users.university, users.profilePic, tutors.walkinStatus, AVG(COALESCE(ratings.rating,0)) AS averageRating FROM tutors
				INNER JOIN users ON tutors.userEmail = users.email 
				LEFT JOIN ratings ON ratings.recipientEmail= users.email ";
		$whereParamNum = 0;
		$havingParamNum = 0;
		$havingClause = "";
		foreach($params as $key => $value){
 			switch($key){
 				case "rating":
					if($havingParamNum>0){
						$havingClause .= " && ";

					}else{
						$havingClause = " HAVING ";
					}
					$havingClause .= " averageRating >= ?";
					$havingParamNum++;
					break;
				default:
					if($whereParamNum>0){
						$sql .= " && ";
					}else{
						$sql .= " WHERE ";
					}
					$sql .= $key . " = ? ";
					$whereParamNum += 1;
					break;
			}
		}
		$sql .= " GROUP BY users.email " . $havingClause;
		
		if(!$stmt = $conn->prepare($sql)){	
			$err = array('error' => -3 , 'errormessage' => 'Query Failed to Prepare');
			echo json_encode($err);
			die;
		}		
 		if(!$stmt -> bind_param($param_types ,...array_values($params))){	
			$err = array('error' => -2 , 'errormessage' => 'Failed to Bind Parameter');
			echo json_encode($err);
			die;
		}
		if($stmt->execute()){//Successful Query
			$result = $stmt->get_result();
			$resultArray = array();

			if(mysqli_num_rows($result)>0){//Something was affected
				while($row = $result->fetch_assoc()){
					$resultArray[] = $row;
				}
				echo json_encode($resultArray,JSON_FORCE_OBJECT);	
			}else if(result==0){//Nothing Was Affected
				$err = array('error' => -4 , 'errormessage' => 'No Rows were Affected');
				echo json_encode($err);
				die;
			}else{//Error Occured
				$err = array('error' => -5 , 'errormessage' => 'Unspecified Error Occurred, ErrorNo' . mysqli_errno($conn));
				echo json_encode($err);
				die;				
			}

		}else{//Unsuccessful Query	
			switch(mysqli_errno($conn)){
				default: //Unhandled SQL Exception occured
					$err = array('error' => -7 , 'errormessage' => 'Query failed at Execution due to an unspecified error');
					echo json_encode($err);
					die;
			}	
		}

?>