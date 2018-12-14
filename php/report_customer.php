<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $date_from  = $_POST['date_from']; // Ex: "2018-09-01";
    //$date_from  = "2018-09-01";
    $date_to  = $_POST['date_to']; // Ex: "2018-09-19";
    //$date_to  = "2018-09-19";
    $report_mode = $_POST['report_mode']; //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "allbranch_daterange";
    //$report_mode = "alldate_daterange";
    //$report_mode = "alldate_selectedmonth";
    //$report_mode = "allmonth";
    //$report_mode = "allyear";
    $year = $_POST['year'];           //selected year for alldate_selectedmonth|allmonth;
    //$year = "2018";
    $month = $_POST['month'];         //selected month for alldate_selectedmonth;
    //$month = "09";
    $order_seq = $_POST['order_seq']; //{ASC|DESC} *UPPERCASE TEXT
    //$order_seq = "ASC"; //{ASC|DESC} *UPPERCASE TEXT
    //$order_seq = "DESC"; //{ASC|DESC} *UPPERCASE TEXT

    date_default_timezone_set('Asia/Bangkok');

    if($report_mode == "allbranch_daterange" && $date_from != "none" && $date_to != "none" && $date_from != NULL && $date_to != NULL){
	    $sql_where = " AND DATE(s.date_time) >= '".$date_from."' AND DATE(s.date_time) <= '".$date_to."' ";
	    $sql1 = "SELECT t.customer_id, t.customer_name, t.payment_history, t.num_order, t.num_item, t.sale_value ";
	    $selected_column = "";
	    $sql2 = " FROM (SELECT c.customer_id, c.customer_name, GROUP_CONCAT(DISTINCT s.payment_type, '|') as payment_history, 
		      COUNT(DISTINCT si.order_id) as num_order, COUNT(DISTINCT si.prod_id) as num_item, 
 		      SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value FROM customer c LEFT JOIN sale_order s 
		      ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." 
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t ";
	    $sql_repeat = "";

            $pre_sql = "SELECT branch_id FROM branch";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($b_row = mysqli_fetch_array($pre_result)){   
		    $selected_column = $selected_column.", t".$b_row[0].".sale_value".$b_row[0]." ";					           			
		    $sql_repeat = $sql_repeat." LEFT JOIN (SELECT c.customer_id, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value".$b_row[0]." 
		      FROM customer c LEFT JOIN sale_order s ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' AND s.branch_id = '".$b_row[0]."' ".$sql_where."
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t".$b_row[0]." 
		      ON t.customer_id = t".$b_row[0].".customer_id";
       	    	}
    	    }
	    $sql = $sql1.$selected_column.$sql2.$sql_repeat." ORDER BY t.sale_value ".$order_seq;	    
    }
   if($report_mode == "alldate_daterange" && $date_from != "none" && $date_to != "none" && $date_from != NULL && $date_to != NULL){
	    $sql_where = " AND DATE(s.date_time) >= '".$date_from."' AND DATE(s.date_time) <= '".$date_to."' ";
	    $sql1 = "SELECT t.customer_id, t.customer_name, t.payment_history, t.num_order, t.num_item, t.sale_value ";
	    $selected_column = "";
	    $sql2 = " FROM (SELECT c.customer_id, c.customer_name, GROUP_CONCAT(DISTINCT s.payment_type, '|') as payment_history, 
		      COUNT(DISTINCT si.order_id) as num_order, COUNT(DISTINCT si.prod_id) as num_item, 
 		      SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value FROM customer c LEFT JOIN sale_order s 
		      ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." 
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t ";
	    $sql_repeat = "";

            $pre_sql = "SELECT DISTINCT DATE_FORMAT(date_time, '%Y%m%d') FROM sale_order WHERE DATE(date_time) >= '".$date_from."' AND DATE(date_time) <= '".$date_to."' ";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($d_row = mysqli_fetch_array($pre_result)){   
		    $selected_column = $selected_column.", t".$d_row[0].".sale_value".$d_row[0]." ";		
		    $sql_repeat = $sql_repeat." LEFT JOIN (SELECT c.customer_id, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value".$d_row[0]." 
		      FROM customer c LEFT JOIN sale_order s ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' AND DATE_FORMAT(s.date_time, '%Y%m%d') = '".$d_row[0]."' ".$sql_where."
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t".$d_row[0]." 
		      ON t.customer_id = t".$d_row[0].".customer_id"; 
		}
    	    }
	    $sql = $sql1.$selected_column.$sql2.$sql_repeat." ORDER BY t.sale_value ".$order_seq;
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
	    $sql1 = "SELECT t.customer_id, t.customer_name, t.payment_history, t.num_order, t.num_item, t.sale_value ";
	    $selected_column = "";
	    $sql2 = " FROM (SELECT c.customer_id, c.customer_name, GROUP_CONCAT(DISTINCT s.payment_type, '|') as payment_history, 
		      COUNT(DISTINCT si.order_id) as num_order, COUNT(DISTINCT si.prod_id) as num_item, 
 		      SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value FROM customer c LEFT JOIN sale_order s 
		      ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." 
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t ";
	    $sql_repeat = "";

            $pre_sql = "SELECT DISTINCT DATE_FORMAT(date_time, '%Y%m%d') FROM sale_order WHERE YEAR(date_time)=".$year." AND MONTH(date_time)=".$month." ";


	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($d_row = mysqli_fetch_array($pre_result)){   
		    $selected_column = $selected_column.", t".$d_row[0].".sale_value".$d_row[0]." ";		
		    $sql_repeat = $sql_repeat." LEFT JOIN (SELECT c.customer_id, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value".$d_row[0]." 
		      FROM customer c LEFT JOIN sale_order s ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' AND DATE_FORMAT(s.date_time, '%Y%m%d') = '".$d_row[0]."' ".$sql_where."
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t".$d_row[0]." 
		      ON t.customer_id = t".$d_row[0].".customer_id"; 
		}
    	    }
	    $sql = $sql1.$selected_column.$sql2.$sql_repeat." ORDER BY t.sale_value ".$order_seq;
    }
    if($report_mode == "allmonth"){
	    if($year == NULL || $year == "none"){
		$year = "YEAR(CURDATE())";
	    }else{
		$year = "'".$year."'";
	    }
	    $sql_where = " AND YEAR(s.date_time) = ".$year." ";
	    $sql1 = "SELECT t.customer_id, t.customer_name, t.payment_history, t.num_order, t.num_item, t.sale_value ";
    	    $selected_column = "";
	    $sql2 = " FROM (SELECT c.customer_id, c.customer_name, GROUP_CONCAT(DISTINCT s.payment_type, '|') as payment_history, 
		      COUNT(DISTINCT si.order_id) as num_order, COUNT(DISTINCT si.prod_id) as num_item, 
 		      SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value FROM customer c LEFT JOIN sale_order s 
		      ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." 
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t ";
	    $sql_repeat = "";

            $pre_sql = "SELECT DISTINCT MONTH(s.date_time) FROM sale_order s WHERE YEAR(s.date_time)=".$year." ";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($m_row = mysqli_fetch_array($pre_result)){      
		    $selected_column = $selected_column.", t".$m_row[0].".sale_value".$m_row[0]." ";					           			
		    $sql_repeat = $sql_repeat." LEFT JOIN (SELECT c.customer_id, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value".$m_row[0]." 
		      FROM customer c LEFT JOIN sale_order s ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' AND MONTH(s.date_time) = '".$m_row[0]."' ".$sql_where."
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t".$m_row[0]." 
		      ON t.customer_id = t".$m_row[0].".customer_id"; 
		}
    	    }
	    $sql = $sql1.$selected_column.$sql2.$sql_repeat." ORDER BY t.sale_value ".$order_seq;
    }
    if($report_mode == "allyear"){
	    $sql_where = "";
	    $sql1 = "SELECT t.customer_id, t.customer_name, t.payment_history, t.num_order, t.num_item, t.sale_value ";
	    $selected_column = "";
	    $sql2 = " FROM (SELECT c.customer_id, c.customer_name, GROUP_CONCAT(DISTINCT s.payment_type, '|') as payment_history, 
		      COUNT(DISTINCT si.order_id) as num_order, COUNT(DISTINCT si.prod_id) as num_item, 
 		      SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value FROM customer c LEFT JOIN sale_order s 
		      ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." 
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t ";
	    $sql_repeat = "";

            $pre_sql = "SELECT DISTINCT YEAR(s.date_time) FROM sale_order s WHERE 1";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($y_row = mysqli_fetch_array($pre_result)){     
		    $selected_column = $selected_column.", t".$y_row[0].".sale_value".$y_row[0]." ";					           			
		    $sql_repeat = $sql_repeat." LEFT JOIN (SELECT c.customer_id, SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value".$y_row[0]." 
		      FROM customer c LEFT JOIN sale_order s ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		      WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' AND YEAR(s.date_time) = '".$y_row[0]."' ".$sql_where."
		      GROUP BY c.customer_id ORDER BY c.customer_id) as t".$y_row[0]." 
		      ON t.customer_id = t".$y_row[0].".customer_id"; 
		}
    	    }
	    $sql = $sql1.$selected_column.$sql2.$sql_repeat." ORDER BY t.sale_value ".$order_seq;	
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