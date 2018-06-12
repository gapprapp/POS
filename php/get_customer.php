<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	$cus_id = $_POST['cus_id'];		

	$sql = "SELECT * FROM customer WHERE customer_id = '$cus_id'";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0) {
		while($row = $result->fetch_assoc()){						
			$output[] = $row;
		}
	}

	if($result){
		echo json_encode($output);		   
	}else{
		echo "fail";
	}	
	mysqli_close($conn);
?>