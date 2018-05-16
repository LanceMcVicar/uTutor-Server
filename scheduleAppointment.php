<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		$requiredParameters = array("tutorEmail","tuteeEmail","startAppDateTime","endAppDateTime");
		$params=array();
		foreach($requiredParameters as $parameter){
			if(!isset($_POST[$parameter])){
				$err = array('error' => -1 , 'errormessage' => 'Required Parameter: ' . $parameter . ' not given');
				echo json_encode($err);
				die;				
			}
		}
		$sql="";
		if(isset($_POST["status"])){
			$sql = "INSERT INTO `appointments`(`tutorEmail`,`tuteeEmail`,`startAppDateTime`,`endAppDateTime`,`is_accepted`) VALUES (?,?,?,?,?)";
				if(!$stmt = $conn->prepare($sql)){	
					$err = array('error' => -3 , 'errormessage' => 'Query Failed to Prepare');
					echo json_encode($err);
					die;
				}	
			if(!$stmt -> bind_param("sssss",$_POST["tutorEmail"],$_POST["tuteeEmail"],$_POST["startAppDateTime"],$_POST["endAppDateTime"],$_POST["status"])){	
				$err = array('error' => -2 , 'errormessage' => 'Failed to Bind Parameter');
				echo json_encode($err);
				die;
			}
		}else{
			$sql = "INSERT INTO `appointments`(`tutorEmail`,`tuteeEmail`,`startAppDateTime`,`endAppDateTime`) VALUES (?,?,?,?)";
			if(!$stmt = $conn->prepare($sql)){	
				$err = array('error' => -3 , 'errormessage' => 'Query Failed to Prepare');
				echo json_encode($err);
				die;
			}		
			if(!$stmt -> bind_param("ssss",$_POST["tutorEmail"],$_POST["tuteeEmail"],$_POST["startAppDateTime"],$_POST["endAppDateTime"])){	
				$err = array('error' => -2 , 'errormessage' => 'Failed to Bind Parameter');
				echo json_encode($err);
				die;
			}
		}		
		if($stmt->execute()){//Successful Query
			$result = mysqli_affected_rows($conn);
			if(result>0){//Something was affected
				$arr = array('success' => "true");
				echo json_encode($arr);
			}else if(result==0){//Nothing Was Affected
				$err = array('error' => -4 , 'errormessage' => 'No Rows were Affected');
				echo json_encode($err);
				die;
			}else{//Error Occured
				$err = array('error' => -5 , 'errormessage' => 'Unspecified Error Occurred, ErrorNo: ' . mysqli_errno($conn));
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
	}
?>