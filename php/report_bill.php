<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $date_from  = $_POST['date_from'];
    $date_to  = $_POST['date_to'];
    $b_id  = $_POST['b_id'];
    date_default_timezone_set('Asia/Bangkok');
    
    if($date_from == "none" || $date_to == "none" || $b_id == "none"){
            $sql = "SELECT DATE(s.date_time) as d, SUM(s.total_price) as sale_value FROM sale_order s WHERE s.order_number NOT LIKE '%can%' 
		    AND MONTH(s.date_time) = MONTH(CURDATE()) AND YEAR(s.date_time) = YEAR(CURDATE()) GROUP BY d ORDER BY d";
    }else 
            $sql = "SELECT DATE(s.date_time) as d, SUM(s.total_price) as sale_value FROM sale_order s WHERE s.order_number NOT LIKE '%can%' 
		    AND DATE(s.date_time) >= '".$date_from."' AND DATE(s.date_time) <= '".$date_to."' AND s.branch_id = '".$b_id."' GROUP BY d ORDER BY d"; 
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