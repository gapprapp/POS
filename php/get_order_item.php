<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
  $order_id  = $_POST['order_id'];
  

  $sql = "SELECT b.branch_id,b.branch_name,o.order_number,o.date_time,u.user_name,c.customer_name,o.sum_price,o.total_discount
  ,o.total_price,o.get_money,o.change_money,o.payment_type,c.address,c.tel,o.customer_id FROM sale_order o INNER JOIN branch b 
  ON o.branch_id = b.branch_id INNER JOIN user u ON o.user_id = u.user_id INNER JOIN customer c ON o.customer_id = c.customer_id
  WHERE o.order_id = '$order_id'";
  $result = mysqli_query($conn, $sql);

  if(mysqli_num_rows($result) > 0){    
    while($row = mysqli_fetch_array($result)){  
      $cus_id = $row['customer_id'];    
      $output[] = $row;
    }   
  } 
    
  $sql = "SELECT i.item_id,i.prod_id,p.prod_name,i.prod_price,i.prod_amount,u.unit_name,p.barcode,p.img_string,i.prod_discount
  ,p.prod_price FROM sale_order_item i INNER JOIN product p ON i.prod_id = p.prod_id INNER JOIN unit u ON p.unit_id = u.unit_id 
  WHERE i.order_id = '$order_id'";
  $result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0){    
      while($row = mysqli_fetch_array($result)){
        $old = array('old_price' => $row[9]);
        array_push($row,$old);
        $prod_id = $row['prod_id'];       
        $sql = "SELECT special_prod_price FROM detail_customer WHERE prod_id = '$prod_id' AND customer_id = '$cus_id'";
        $result1 = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result1) > 0){
            while($row1 = mysqli_fetch_array($result1)){
              $row[9] = $row1['special_prod_price'];                    
            }		
        }
        $output2 = [];
        $sql = "SELECT amt_threshold,prod_price FROM detail_customer WHERE prod_id = '$prod_id' AND customer_id = 0 ORDER BY amt_threshold ASC";
        $result2 = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result2) > 0){
            while($row2 = mysqli_fetch_array($result2)){
              $output2[] = $row2;                        
            } 
            array_push($row,$output2);                          		
        }       
        array_push($output,$row);
      }   
  } 

	if($result){    
		echo json_encode($output);
	}else{
		echo "fail";
	}
	
	mysqli_close($conn);
?>