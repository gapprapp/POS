<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $cus_id = $_POST['cus_id'];	
    $output = array();	

	$sql = "SELECT d.special_prod_price,d.prod_id,p.prod_name,p.barcode,p.prod_price,u.unit_name 
    FROM detail_customer d INNER JOIN product p ON d.prod_id = p.prod_id INNER JOIN unit u
    ON p.unit_id = u.unit_id WHERE customer_id = '$cus_id' AND customer_id != 0";
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