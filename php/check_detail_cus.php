<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos"); 
    $cus_id = $_POST['cus_id'];   
    $amt = $_POST['amt'];   
    $prod_id = $_POST['prod_id'];   
 
    $sql = "SELECT special_prod_price FROM detail_customer WHERE prod_id = '$prod_id' AND customer_id = '$cus_id'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
               $output[] = $row;                    
        }		
    }   

    $sql = "SELECT max(amt_threshold) AS max_amt FROM detail_customer WHERE prod_id = '$prod_id' AND amt_threshold <= '$amt'";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$amount = $row['max_amt'];			
			$sql = "SELECT prod_price FROM detail_customer WHERE prod_id = '$prod_id' AND amt_threshold = '$amount'";
			$result1 = mysqli_query($conn, $sql);
			if(mysqli_num_rows($result1) > 0){
				while($row1 = mysqli_fetch_array($result1)){					
                    array_push($output,$row1);  
				}		
			}					
		}		
	}  

    if($result){
        echo json_encode($output);		   
    }else{
        echo "fail";
    }
    mysqli_close($conn);
?>