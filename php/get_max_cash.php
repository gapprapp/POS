<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $date  = $_POST['date'];   
    $b_id = $_POST['b_id']; 
    $user_name = $_POST['user_name'];   
    $start = $_POST['start'];
    $sort_by = $_POST['sort_by'];
    $order = $_POST['order'];     
    $sql;
	if($sort_by == "none" && $order == "none"){
		if($date || $user_name || $b_id){		
			$sql = "SELECT u.user_name,b.branch_name,c.date_time,c.cash FROM cash_record c INNER JOIN user u 
            ON c.record_by = u.user_id INNER JOIN branch b ON c.branch_id = b.branch_id WHERE c.branch_id = '$b_id'             
	        AND u.user_name LIKE '%$user_name%' AND c.date_time LIKE '%$date%'";
		}else
		{
			$sql = "SELECT u.user_name,b.branch_name,c.date_time,c.cash FROM cash_record c INNER JOIN user u 
            ON c.record_by = u.user_id INNER JOIN branch b ON c.branch_id = b.branch_id";			
		}
		
	}else if($sort_by != "none" && $order != "none"){
		if($date || $user_name || $b_id){
			if($order == "asc"){
                $sql = "SELECT u.user_name,b.branch_name,c.date_time,c.cash FROM cash_record c INNER JOIN user u 
                ON c.record_by = u.user_id INNER JOIN branch b ON c.branch_id = b.branch_id WHERE c.branch_id = '$b_id'             
                AND u.user_name LIKE '%$user_name%' AND c.date_time LIKE '%$date%' ORDER BY c.$sort_by ASC";
			}else if($order == "desc"){
                $sql = "SELECT u.user_name,b.branch_name,c.date_time,c.cash FROM cash_record c INNER JOIN user u 
                ON c.record_by = u.user_id INNER JOIN branch b ON c.branch_id = b.branch_id WHERE c.branch_id = '$b_id'             
                AND u.user_name LIKE '%$user_name%' AND c.date_time LIKE '%$date%' ORDER BY c.$sort_by DESC";
			}
		}else{
			if($order == "asc"){
                $sql = "SELECT u.user_name,b.branch_name,c.date_time,c.cash FROM cash_record c INNER JOIN user u 
                ON c.record_by = u.user_id INNER JOIN branch b ON c.branch_id = b.branch_id ORDER BY c.$sort_by 
                ASC";
			}else if($order == "desc"){
				$sql = "SELECT u.user_name,b.branch_name,c.date_time,c.cash FROM cash_record c INNER JOIN user u 
                ON c.record_by = u.user_id INNER JOIN branch b ON c.branch_id = b.branch_id ORDER BY c.$sort_by
                 DESC";
			}
		}
	}
    $result = mysqli_query($conn, $sql);
	$number_cash = mysqli_num_rows($result);

	if($result){
		echo $number_cash;		   
	}else{
		echo "fail";
	}
	mysqli_close($conn);
?>