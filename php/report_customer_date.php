<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $selected_date  = $_POST['selected_date']; // Ex: "2018-10-16";
    //$selected_date  = "2018-10-16";
    $report_mode = $_POST['report_mode']; //{overview|detail}
    //$report_mode = "overview";
    //$report_mode = "detail";
    $order_seq = $_POST['order_seq']; //{ASC|DESC} *UPPERCASE TEXT
    //$order_seq = "ASC"; //{ASC|DESC} *UPPERCASE TEXT
    //$order_seq = "DESC"; //{ASC|DESC} *UPPERCASE TEXT

    date_default_timezone_set('Asia/Bangkok');

    if($report_mode == "detail" && $selected_date != "none" && $selected_date != NULL){
	    $sql_where = " AND DATE(s.date_time) = '".$selected_date."' ";
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
    if($report_mode == "overview" && $selected_date != "none" && $selected_date != NULL){
	    $sql_where = " AND DATE(s.date_time) = '".$selected_date."' ";
	    $sql = " SELECT DATE(t.date_time) as selected_date, COUNT(t.customer_id) as num_customer 
		     FROM (SELECT c.customer_id, c.customer_name, s.date_time, GROUP_CONCAT(DISTINCT s.payment_type, '|') as payment_history, 
		     COUNT(DISTINCT si.order_id) as num_order, COUNT(DISTINCT si.prod_id) as num_item, 
 		     SUM((si.prod_price*si.prod_amount)-si.prod_discount) as sale_value FROM customer c LEFT JOIN sale_order s 
		     ON c.customer_id = s.customer_id LEFT JOIN sale_order_item si ON s.order_id = si.order_id 
		     WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' ".$sql_where." 
		     GROUP BY c.customer_id ORDER BY c.customer_id) as t GROUP BY selected_date ";
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