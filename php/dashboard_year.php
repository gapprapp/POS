<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $year = $_POST['year']; //Ex: "2018";
    $b_id = $_POST['b_id']; //branch_id;
    
    date_default_timezone_set('Asia/Bangkok');
    
    if($year == NULL || $year == "none"){
	    $sql = "SELECT MONTH(s.date_time) as m, MONTHNAME(s.date_time) as mname, SUM(s.total_price) as sale_value FROM sale_order s 
		    WHERE s.order_number NOT LIKE '%can%' AND YEAR(s.date_time) = YEAR(CURDATE()) GROUP BY m ORDER BY m";
    }else{
            $sql = "SELECT MONTH(s.date_time) as m, MONTHNAME(s.date_time) as mname, SUM(s.total_price) as sale_value FROM sale_order s 
		    WHERE s.order_number NOT LIKE '%can%' AND YEAR(s.date_time) = '".$year."' ";
	    if($b_id != NULL && $b_id != "none"){
		    $sql = $sql." AND s.branch_id = '".$b_id."' ";
	    }
	    $sql = $sql." GROUP BY m ORDER BY m";             
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