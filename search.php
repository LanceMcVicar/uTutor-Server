<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		$param_types = '';
		$params = array();
		$filters = array("workLat", "workLong", "firstName","lastName","email","university","subject","rating","distance");
		$distanceClause = "";
		foreach($filters as $filter){
			if(isset($_POST[$filter])){
				if(!empty($_POST[$filter])){
					switch($filter){
						case "workLat":
							if($_POST["workLat"] == "0.0" && $_POST["workLong"]=="0.0"){
								
							}else{
								$params[$filter] = $_POST[$filter];
								$params["workLong"]=$_POST["workLong"];
								$params['lat2'] = $_POST[$filter];
								$param_types.="sss";
							}
						break;
						case "workLong":
						break;
						case "distance": //If distance is set, a lat/long also needs to be set
							if(!empty($_POST["workLat"]) && !empty($_POST["workLong"])){
								$params[$filter] = $_POST[$filter];
								$param_types.="s";								
							}else{
								$err = array('error' => -2 , 'errormessage' => 'Distance Needs a Lat and Long');
								echo json_encode($err);
								die;
							}
							$distanceClause= ",(3959  *acos( cos( radians(?) ) * cos( radians( workLat ) ) * cos( radians( workLong ) - radians(?) )+ sin( radians(?) ) * sin(radians(workLat)) ) ) as Distance ";
						break;
						default:
							$params[$filter] = $_POST[$filter];
							$param_types.="s";
						break;	
					}
				}
			}
		}
		$sql = "SELECT users.email, users.firstName, users.lastName, users.university, users.profilePic, tutors.walkinStatus, AVG(COALESCE(ratings.rating,0)) AS averageRating";		
		$sql .= $distanceClause . "  , workLat, workLong , COALESCE(GROUP_CONCAT(SubjectName ORDER BY SubjectName DESC SEPARATOR ','),'None') AS Subjects FROM tutors INNER JOIN users ON tutors.userEmail = users.email LEFT JOIN subjecttutor on tutors.userEmail = subjecttutor.tutorEmail LEFT JOIN ratings ON ratings.recipientEmail= users.email  ";
				

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
				case "distance":
					if($havingParamNum>0){
						$havingClause .= " && ";
					}else{
						$havingClause = " HAVING ";
					}
					$havingClause .= " Distance <= ?";
					$havingParamNum++;
					break;
				case "lat2":
				case "workLat":
				case "workLong":
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
		if($whereParamNum > 0 && !empty($distanceClause)){
			$sql .= " && !(workLat = 0 && workLong = 0) GROUP BY users.email " . $havingClause . " LIMIT 50";
		}else if(!empty($distanceClause)){
			$sql .= " WHERE !(workLat = 0 && workLong = 0) GROUP BY users.email " . $havingClause . " LIMIT 50";
		}else{
				$sql .= " GROUP BY users.email " . $havingClause . " LIMIT 50";
		}
		if(!$stmt = $conn->prepare($sql)){	
			$err = array('error' => -3 , 'errormessage' => 'Query Failed to Prepare' . $sql);
			echo json_encode($err);
			die;
		}		
 		if(!$stmt -> bind_param($param_types ,...array_values($params))){	
			$err = array('error' => -2 , 'errormessage' => 'Failed to Bind Parameter'.$sql);
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
			}				
			echo json_encode($resultArray,JSON_FORCE_OBJECT);	
		}else{//Unsuccessful Query	
			switch(mysqli_errno($conn)){
				default: //Unhandled SQL Exception occured
					$err = array('error' => -7 , 'errormessage' => 'Query failed at Execution due to an unspecified error');
					echo json_encode($err);
					die;
			}	
		}
	}
?>