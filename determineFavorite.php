<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		$requiredParameters = array("favoritorEmail","favoriteeEmail");
		foreach($requiredParameters as $parameter){
			if(!isset($_POST[$parameter])){
				$err = array('error' => -1 , 'errormessage' => 'Required Parameter: ' . $parameter . ' not given');
				echo json_encode($err);
				die;				
			}
		}
		$sql = "SELECT * FROM favorites
				WHERE favoriteeEmail= ? AND favoritorEmail=?";
		$stmt = $conn->prepare($sql);
		$stmt-> bind_param("ss",$_POST["favoriteeEmail"],$_POST["favoritorEmail"]);
		if($stmt->execute()){
			$result = $stmt->get_result();
			if(mysqli_num_rows($result)>0){
				$arr = array('success' => 1);
				echo json_encode($arr);
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