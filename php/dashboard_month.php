<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $month = $_POST['month']; //Ex: "09";
    $year = $_POST['year']; //Ex: "2018";
    $b_id = $_POST['b_id']; //branch_id;

    date_default_timezone_set('Asia/Bangkok');
    
    $sql = "SELECT DATE(s.date_time) as d, SUM(s.total_price) as sale_value FROM sale_order s WHERE s.order_number NOT LIKE '%can%' ";
    if($year == NULL || $year == "none"){
	$year = "YEAR(CURDATE())";
    }else{
	$year = "'".$year."'";
    }
    if($month == NULL || $month == "none"){
	$month = "MONTH(CURDATE())";
    }else{
	$month = "'".$month."'";
    }
    $sql = $sql." AND MONTH(s.date_time) = ".$month." AND YEAR(s.date_time) = ".$year." ";
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
    }else if($result == null){
    echo "fail";
    }   else{
	echo "fail";
    }	
    mysqli_close($conn);
?>