<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	$order_id  = $_POST['order_id']; 
    
    $sql = "
    SELECT s.item_id,p.barcode,p.prod_name,s.prod_price,s.prod_amount,u.unit_name
    FROM sale_order_item s INNER JOIN product p ON s.prod_id = p.prod_id 
    INNER JOIN unit u ON p.unit_id = u.unit_id WHERE s.order_id = '$order_id'";
    $result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0){    
      while($row = mysqli_fetch_array($result)){      
        $output[] = $row;
      }   
    }else{    
       echo "nothing";
    }   

	if($result){
		echo json_encode($output);
	}else{
		echo "fail";
	}
	
	mysqli_close();
?>