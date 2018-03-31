<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");		

	$sql = "SELECT customer_id,customer_name FROM customer";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0) {
		while($row = $result->fetch_assoc()){						
			$output[] = $row;
		}
	}else {
		echo "fail";		
	}

	if($result){
		echo json_encode($output);		   
	}else{
		echo "fail";
	}
	
	mysqli_close();
?>