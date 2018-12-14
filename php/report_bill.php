<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $date_from  = $_POST['date_from']; // Ex: "2018-09-01";
    //$date_from  = "2018-09-01";
    $date_to  = $_POST['date_to']; // Ex: "2018-09-19";
    //$date_to  = "2018-09-19";
    $report_mode = $_POST['report_mode']; //{alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "alldate_daterange";
    //$report_mode = "alldate_selectedmonth";
    //$report_mode = "allmonth";
    //$report_mode = "allyear";
    $report_scene = $_POST['report_scene']; //{branch|date|user|payment_type}
    //$report_scene = "branch";
    //$report_scene = "date";
    //$report_scene = "user";
    //$report_scene = "payment_type";
    $year = $_POST['year'];           //selected year for alldate_selectedmonth|allmonth;
    //$year = "2018";
    $month = $_POST['month'];         //selected month for alldate_selectedmonth;
    //$month = "09";
    $order_seq = $_POST['order_seq']; //{ASC|DESC} *UPPERCASE TEXT
    //$order_seq = "ASC"; //{ASC|DESC} *UPPERCASE TEXT
    //$order_seq = "DESC"; //{ASC|DESC} *UPPERCASE TEXT

    date_default_timezone_set('Asia/Bangkok');

   if($report_mode == "alldate_daterange" && $date_from != "none" && $date_to != "none" && $date_from != NULL && $date_to != NULL){
	    $sql_where = " AND DATE(s.date_time) >= '".$date_from."' AND DATE(s.date_time) <= '".$date_to."' ";
	    if($report_scene == "date"){
    		$sql_prefix = "SELECT DATE_FORMAT(s.date_time, '%d-%m-%Y') as date, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value 
			       FROM sale_order s LEFT JOIN sale_order_item si ON s.order_id = si.order_id WHERE s.order_number NOT LIKE '%can%' 
		    	       AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY DATE_FORMAT(s.date_time, '%d-%m-%Y') ORDER BY DATE_FORMAT(s.date_time, '%d-%m-%Y') ";	    
	    }
    }
    if($report_mode == "alldate_selectedmonth"){
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
	    $sql_where = " AND YEAR(s.date_time) = ".$year." AND MONTH(s.date_time) = ".$month." ";
	    if($report_scene == "date"){
	    	$sql_prefix = "SELECT DATE_FORMAT(s.date_time, '%d-%m-%Y') as date, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value 
			       FROM sale_order s LEFT JOIN sale_order_item si ON s.order_id = si.order_id WHERE s.order_number NOT LIKE '%can%' 
		    	       AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY DATE_FORMAT(s.date_time, '%d-%m-%Y') ORDER BY DATE_FORMAT(s.date_time, '%d-%m-%Y') ";
	    }
    }
    if($report_mode == "allmonth"){
	    if($year == NULL || $year == "none"){
		$year = "YEAR(CURDATE())";
	    }else{
		$year = "'".$year."'";
	    }
	    $sql_where = " AND YEAR(s.date_time) = ".$year." ";
	    if($report_scene == "date"){
	    	$sql_prefix = "SELECT MONTH(s.date_time) as month, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value 
		     	       FROM sale_order s LEFT JOIN sale_order_item si ON s.order_id = si.order_id WHERE s.order_number NOT LIKE '%can%' 
		    	       AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY MONTH(s.date_time) ORDER BY MONTH(s.date_time) ";
	    }
    }
    if($report_mode == "allyear"){
	    if($report_scene == "date"){
	    	$sql_prefix = "SELECT YEAR(s.date_time) as year, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value 
		    	     FROM sale_order s LEFT JOIN sale_order_item si ON s.order_id = si.order_id WHERE s.order_number NOT LIKE '%can%' 
		    	     AND si.order_id NOT LIKE '%refund%' GROUP BY YEAR(s.date_time) ORDER BY YEAR(s.date_time) ";
	    }
    }

    if($report_scene == "branch"){
	    $sql_prefix = "SELECT s.branch_id, b.branch_name, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value 
		           FROM branch b LEFT JOIN sale_order s ON b.branch_id = s.branch_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
			   WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY s.branch_id ORDER BY s.branch_id ";
    }
    if($report_scene == "user"){
     	    $sql_prefix = "SELECT s.user_id, u.user_name, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value 
		           FROM user u LEFT JOIN sale_order s ON u.user_id = s.user_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
			   WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY s.user_id ORDER BY s.user_id ";	    
    }
    if($report_scene == "payment_type"){
    	    $sql_prefix = "SELECT s.payment_type, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value 
		           FROM sale_order s LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
			   WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY s.payment_type ORDER BY s.payment_type ";
    } 
    $sql = $sql_prefix.$order_seq;

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