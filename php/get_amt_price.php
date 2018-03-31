<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	$amt = $_POST['amount'];	
	$prod_id = $_POST['prod_id'];    

	$sql = "SELECT prod_price FROM detail_customer WHERE prod_id = '$prod_id' AND amt_threshold = '$amt'";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			echo $row['prod_price'];
		}		
	}else{
		echo "fail";
	}

	mysqli_close($conn);
?>