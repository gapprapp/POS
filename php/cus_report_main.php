<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $date_from  = $_POST['date_from']; // Ex: "2018-09-01";
    $date_to  = $_POST['date_to']; // Ex: "2018-09-19";
    //$date_from  = "2018-09-01";
    //$date_to  = "2018-09-19";
    $report_mode = $_POST['report_mode'];  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "allbranch_daterange";  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "alldate_daterange";  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "alldate_selectedmonth";  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "allmonth";  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "allyear";  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    $year = $_POST['year'];           //selected year for alldate_selectedmonth|allmonth;
    //$year = 2018;
    $month = $_POST['month'];         //selected month for alldate_selectedmonth;
    //$month = 11;
    $order_seq = $_POST['order_seq']; //{ASC|DESC} *UPPERCASE TEXT
    //$order_seq = "ASC"; //{ASC|DESC} *UPPERCASE TEXT
	//$order_seq = "DESC"; //{ASC|DESC} *UPPERCASE TEXT
	$start = $_POST['start'];
	//$start = 0;

	date_default_timezone_set('Asia/Bangkok');
	
	// max days in month
	$max_date = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);
	$arr_max_date_in_month = array("max_date" => $max_date);

    $mode_matched = 0;
    if($report_mode == "allbranch_daterange" && $date_from != "none" && $date_to != "none" && $date_from != NULL && $date_to != NULL){
	    $sql_where = " AND DATE(s.date_time) >= '".$date_from."' AND DATE(s.date_time) <= '".$date_to."' ";
	    $mode_matched = 1;	    
    }
    if($report_mode == "alldate_daterange" && $date_from != "none" && $date_to != "none" && $date_from != NULL && $date_to != NULL){
	    $sql_where = " AND DATE(s.date_time) >= '".$date_from."' AND DATE(s.date_time) <= '".$date_to."' ";
	    $mode_matched = 1;	    
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
	    $mode_matched = 1;	    
    }
    if($report_mode == "allmonth"){
	    if($year == NULL || $year == "none"){
		$year = "YEAR(CURDATE())";
	    }else{
		$year = "'".$year."'";
	    }
	    $sql_where = " AND YEAR(s.date_time) = ".$year." ";
	    $mode_matched = 1;	    
    }
    if($report_mode == "allyear"){
	    $sql_where = "";
	    $mode_matched = 1;	    
    }

    if($mode_matched==1){
	    $sql1 = "SELECT t.customer_id, t.customer_name, t.payment_history, t.num_order, t.num_item, t.sale_value ";
	    $sql2 = " FROM (SELECT c.customer_id, c.customer_name, GROUP_CONCAT(DISTINCT s.payment_type, ' | ') as payment_history, 
		      IFNULL(COUNT(DISTINCT si.order_id),0) as num_order, IFNULL(COUNT(DISTINCT si.prod_id),0) as num_item, 
 		      IFNULL(SUM((si.prod_price*si.prod_amount)-si.prod_discount),0) as sale_value FROM customer c LEFT JOIN sale_order s 
		      ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." 
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t ";
	    $sql = $sql1.$sql2." ORDER BY t.sale_value ".$order_seq." LIMIT ".$start.",100";
    }

    //echo $sql;
    //die();

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