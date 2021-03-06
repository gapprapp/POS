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
    $shift = $_POST['shift'];
    $txt = "(cancel)";
    date_default_timezone_set('Asia/Bangkok');
    $d =  date('Y-m-d H:i:s');
    
    $sql;
        if($sort_by == "none" && $order == "none"){
        if($shift){
            $sql = "SELECT s.order_id,s.order_number,c.customer_name,u.user_name,b.branch_name,s.payment_type,s.total_price,s.date_time,s.branch_id
            FROM sale_order s INNER JOIN customer c ON s.customer_id = c.customer_id INNER JOIN user u ON s.user_id = u.user_id 
            INNER JOIN branch b ON s.branch_id = b.branch_id WHERE s.payment_type='เงินสด' AND s.order_number NOT LIKE '%can%' 
            AND s.date_time BETWEEN (SELECT date_time FROM cash_record ORDER BY date_time DESC LIMIT 1) AND '$d' ORDER BY s.order_id DESC LIMIT $start,100"; 
        }else if($date || $cus_name || $number || $user_name || $pay || $b_id){		
            $sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time,o.branch_id
            FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
            INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
            WHERE o.order_number NOT LIKE '%$txt%' AND c.customer_name LIKE '%$cus_name%' AND o.branch_id LIKE '%$b_id%' AND o.order_number LIKE '%$number%' 
            AND u.user_name LIKE '%$user_name%' AND o.payment_type LIKE '%$pay%' AND o.date_time LIKE '%$date%' ORDER BY o.order_id DESC LIMIT $start,100";
        }else{           
            $sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time,o.branch_id
            FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
            INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
            WHERE o.order_number NOT LIKE '%$txt%' ORDER BY o.order_id DESC LIMIT $start,100";            				
        }        
    }else if($sort_by != "none" && $order != "none"){
        if($order == "asc"){
            if($shift){
                $sql = "SELECT s.order_id,s.order_number,c.customer_name,u.user_name,b.branch_name,s.payment_type,s.total_price,s.date_time,s.branch_id
                FROM sale_order s INNER JOIN customer c ON s.customer_id = c.customer_id INNER JOIN user u ON s.user_id = u.user_id 
                INNER JOIN branch b ON s.branch_id = b.branch_id WHERE s.payment_type='เงินสด' AND s.order_number NOT LIKE '%can%' 
                AND s.date_time BETWEEN (SELECT date_time FROM cash_record ORDER BY date_time DESC LIMIT 1) AND '$d' ORDER BY s.$sort_by ASC LIMIT $start,100"; 
            }else if($date || $cus_name || $number || $user_name || $pay || $b_id){           
                $sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time,o.branch_id
                FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
                INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
                WHERE o.order_number NOT LIKE '%$txt%' AND c.customer_name LIKE '%$cus_name%' AND o.branch_id LIKE '%$b_id%' AND o.order_number LIKE '%$number%' 
                AND u.user_name LIKE '%$user_name%' AND o.payment_type LIKE '%$pay%' AND o.date_time LIKE '%$date%'
                ORDER BY o.$sort_by ASC LIMIT $start,100";
            }else{
                $sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time,o.branch_id
                FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
                INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
                WHERE o.order_number NOT LIKE '%$txt%' ORDER BY o.$sort_by ASC LIMIT $start,100";
            }    
        }else if($order == "desc"){
            if($shift){
                $sql = "SELECT s.order_id,s.order_number,c.customer_name,u.user_name,b.branch_name,s.payment_type,s.total_price,s.date_time,s.branch_id
                FROM sale_order s INNER JOIN customer c ON s.customer_id = c.customer_id INNER JOIN user u ON s.user_id = u.user_id 
                INNER JOIN branch b ON s.branch_id = b.branch_id WHERE s.payment_type='เงินสด' AND s.order_number NOT LIKE '%can%' 
                AND s.date_time BETWEEN (SELECT date_time FROM cash_record ORDER BY date_time DESC LIMIT 1) AND '$d' ORDER BY s.$sort_by DESC LIMIT $start,100"; 
            }else if($date || $cus_name || $number || $user_name || $pay || $b_id){         
                $sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time,o.branch_id
                FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
                INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
                WHERE o.order_number NOT LIKE '%$txt%' AND c.customer_name LIKE '%$cus_name%' AND o.branch_id LIKE '%$b_id%' AND o.order_number LIKE '%$number%' 
                AND u.user_name LIKE '%$user_name%' AND o.payment_type LIKE '%$pay%' AND o.date_time LIKE '%$date%'
                ORDER BY o.$sort_by DESC LIMIT $start,100";            
            }else{
                $sql = "SELECT o.order_id,o.order_number,c.customer_name,u.user_name,b.branch_name,o.payment_type,o.total_price,o.date_time,o.branch_id
                FROM sale_order o INNER JOIN customer c ON o.customer_id = c.customer_id 
                INNER JOIN user u ON o.user_id = u.user_id INNER JOIN branch b ON o.branch_id = b.branch_id
                WHERE o.order_number NOT LIKE '%$txt%' ORDER BY o.$sort_by DESC LIMIT $start,100";
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