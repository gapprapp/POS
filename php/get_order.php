<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
  $date  = $_POST['date'];
  $cus_id = $_POST['cus_id'];
  $b_id = $_POST['b_id'];
  $number = $_POST['number'];
  $user_id = $_POST['user_id'];
  $pay = $_POST['pay'];
    
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