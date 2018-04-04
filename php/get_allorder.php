<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
  $date  = $_POST['date'];
  $cus_name = $_POST['cus_name'];
  $b_id = $_POST['b_id'];
  $number = $_POST['number'];
  $user_name = $_POST['user_name'];
  $pay = $_POST['pay'];
  $start = $_POST['start'];
	$sort_by = $_POST['sort_by'];
  $order = $_POST['order'];
  
  $sql;
	if($sort_by == "none" && $order == "none"){
		if($date || $cus_id || $b_id || $number || $user_id || $pay){		
			$sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time
			FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
			INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
      WHERE c.customer_name LIKE '%$cus_name%' AND o.branch_id = '$b_id' AND o.order_number LIKE '%$number%' 
	    AND u.user_name LIKE '%$user_name%' AND o.payment_type = '$pay' AND o.date_time LIKE '%$date%' 
      LIMIT $start,100";
		}else
		{
			$sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time
			FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
			INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id LIMIT $start,100";			
		}
		
	}else if($sort_by != "none" && $order != "none"){
		if($date || $cus_id || $b_id || $number || $user_id || $pay){
			if($order == "asc"){
        $sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time
        FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
        INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
        WHERE c.customer_name LIKE '%$cus_name%' AND o.branch_id = '$b_id' AND o.order_number LIKE '%$number%' 
        AND u.user_name LIKE '%$user_name%' AND o.payment_type = '$pay' AND o.date_time LIKE '%$date%'
        ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
        $sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time
        FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
        INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
        WHERE c.customer_name LIKE '%$cus_name%' AND o.branch_id = '$b_id' AND o.order_number LIKE '%$number%' 
        AND u.user_name LIKE '%$user_name%' AND o.payment_type = '$pay' AND o.date_time LIKE '%$date%'
        ORDER BY $sort_by DESC LIMIT $start,100";
			}
		}else{
			if($order == "asc"){
        $sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time
        FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
        INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
        ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
				$sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time
        FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
        INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
        ORDER BY $sort_by DESC LIMIT $start,100";
			}
		}
	}
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