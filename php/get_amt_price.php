<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	$amt = $_POST['amount'];	
	$prod_id = $_POST['prod_id'];    

	$sql = "SELECT max(amt_threshold) AS max_amt FROM detail_customer WHERE prod_id = '$prod_id' AND amt_threshold <= '$amt'";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$amount = $row['max_amt'];			
			$sql = "SELECT prod_price FROM detail_customer WHERE prod_id = '$prod_id' AND amt_threshold = '$amount'";
			$result1 = mysqli_query($conn, $sql);
			if(mysqli_num_rows($result1) > 0){
				while($row1 = mysqli_fetch_array($result1)){					
					echo $row1['prod_price'];		
				}		
			}else{
				echo "fail";
			}						
		}		
	}

	mysqli_close($conn);
?>