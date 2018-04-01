<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id'];		

	$sql = "SELECT * FROM detail_customer WHERE prod_id = '$prod_id'";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0) {
		while($row = $result->fetch_assoc()){
            $cus_id = $row['customer_id'];
            $sql = "SELECT customer_name FROM customer WHERE customer_id = '$cus_id'";
            $result1 = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result1) > 0) {
                while($row1 = $result1->fetch_assoc()){
                    $row['customer_id'] = $row1['customer_name'];
                }
            }
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