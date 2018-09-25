<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $date_from  = $_POST['date_from']; //Ex: "2018-09-01";
    $date_to  = $_POST['date_to']; //Ex: "2018-09-19";
    $b_id  = $_POST['b_id']; //branch_id;

    date_default_timezone_set('Asia/Bangkok');
    
    if($date_to == NULL){
	    $date_to = "CURDATE()";
    }else{
	    $date_to = "'".$date_to."'";
    }
    $sql = "SELECT DATE(s.date_time) as d, SUM(s.total_price) as sale_value FROM sale_order s WHERE s.order_number NOT LIKE '%can%' 
	    AND DATE(s.date_time) >= '".$date_from."' AND DATE(s.date_time) <= ".$date_to." ";
    if($b_id != NULL && $b_id != "none"){
	    $sql = $sql." AND s.branch_id = '".$b_id."' ";
    }
    $sql = $sql." GROUP BY d ORDER BY d"; 
    
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