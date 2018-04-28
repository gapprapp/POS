<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    date_default_timezone_set('Asia/Bangkok');
    $d =  date('Y-m-d H:i:s');

    $sql = "SELECT s.order_id,s.order_number,c.customer_name,u.user_name,b.branch_name,s.payment_type,s.total_price,s.date_time
    FROM sale_order s INNER JOIN customer c ON s.customer_id = c.customer_id INNER JOIN user u ON s.user_id = u.user_id 
    INNER JOIN branch b ON s.branch_id = b.branch_id WHERE s.payment_type='เงินสด' AND s.order_number NOT LIKE '%can%' 
    AND s.date_time BETWEEN (SELECT date_time FROM cash_record ORDER BY date_time DESC LIMIT 1) AND '$d'";  
    $result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0){    
      while($row = mysqli_fetch_array($result)){      
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