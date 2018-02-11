<?php
	$sql = "SELECT * FROM `users`";
	$result = $conn->query($sql);
	
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
				echo $row["email"] . " " . $row["hashedPassword"];
		}
	}else{
		echo "0 results";
	}
?>