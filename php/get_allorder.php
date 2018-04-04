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
	    AND u.user_name LIKE '%$user_name%' AND o.payment_type = '$pay' AND o.date_time between '$date' and '$date 23:59:59'
      LIMIT $start,100";
		}else
		{
			$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
			FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
			INNER JOIN unit u ON p.unit_id = u.unit_id LIMIT $start,100";			
		}
		
	}else if($sort_by != "none" && $order != "none"){
		if($name || $barcode || $type){
			if($order == "asc"){
			$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
			FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
			INNER JOIN unit u ON p.unit_id = u.unit_id WHERE p.barcode LIKE '%$barcode%' AND p.prod_name LIKE '%$name%' 
	        AND t.prod_type_name LIKE '%$type%' ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
				$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
				FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
				INNER JOIN unit u ON p.unit_id = u.unit_id WHERE p.barcode LIKE '%$barcode%' AND p.prod_name LIKE '%$name%' 
	        AND t.prod_type_name LIKE '%$type%' ORDER BY $sort_by DESC LIMIT $start,100";
			}
		}else{
			if($order == "asc"){
				$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
				FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
				INNER JOIN unit u ON p.unit_id = u.unit_id ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
				$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
				FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
				INNER JOIN unit u ON p.unit_id = u.unit_id ORDER BY $sort_by DESC LIMIT $start,100";
			}
		}
	}
    
  $sql = "SELECT s.order_id,s.total_price,s.date_time,c.customer_name FROM sale_order s INNER JOIN customer c 
  ON s.customer_id = c.customer_id  WHERE date_time between '$date' and '$date 23:59:59'";
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