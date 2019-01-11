<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $selected_date = $_POST['selected_date']; // Ex: "2018-10-16";
//    $selected_date = "2018-10-16";

    date_default_timezone_set('Asia/Bangkok');

   if($selected_date != "none" && $selected_date != NULL){
	$sql = "SELECT DATE(s.date_time) as selected_date, COUNT(DISTINCT s.order_id) as num_order 
		FROM sale_order s LEFT JOIN sale_order_item si ON s.order_id = si.order_id WHERE s.order_number NOT LIKE '%can%' 
	    	AND si.order_id NOT LIKE '%refund%' AND DATE(s.date_time) = '".$selected_date."' GROUP BY selected_date ";
    }
  
//    echo $sql;
//    die();

    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
       while($row = mysqli_fetch_array($result)){      
       		$output[] = $row;
       	}   
    }  
    if($result){
	echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }else{
	echo "fail";
    }	
    mysqli_close($conn);
?>