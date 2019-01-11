<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $selected_date = $_POST['selected_date']; // Ex: "2018-10-16";
    //$selected_date = "2018-10-16";
    $report_mode = $_POST['report_mode']; //{overview|distinct_overview|detail}
    //$report_mode = "distinct_overview";
    $order_seq = $_POST['order_seq']; //{ASC|DESC} *UPPERCASE TEXT
    //$order_seq = "ASC"; //{ASC|DESC} *UPPERCASE TEXT
    //$order_seq = "DESC"; //{ASC|DESC} *UPPERCASE TEXT

    date_default_timezone_set('Asia/Bangkok');

    if($report_mode == "detail" && $selected_date != "none" && $selected_date != NULL){
	    $sql_where = " AND DATE(s.date_time) = '".$selected_date."' ";
	    $sql1 = "SELECT t.prod_id, t.barcode, t.prod_name, t.prod_price, t.unit_name, t.amt";
	    $selected_column = "";
	    $sql2 = " FROM (SELECT p.prod_id, p.barcode, p.prod_name, si.prod_price, u.unit_name, SUM(si.prod_amount) as amt 
        	      FROM product p LEFT JOIN unit u ON p.unit_id = u.unit_id LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id 
		      LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' 
		      AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY p.prod_id ORDER BY p.prod_id) as t ";
	    $sql_repeat = "";

            $pre_sql = "SELECT branch_id FROM branch";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($b_row = mysqli_fetch_array($pre_result)){   
		    $selected_column = $selected_column.", t".$b_row[0].".amt".$b_row[0]." ";					           			
		    $sql_repeat = $sql_repeat." LEFT JOIN (SELECT p.prod_id, SUM(si.prod_amount) as amt".$b_row[0]." 
		      FROM product p LEFT JOIN unit u ON p.unit_id = u.unit_id LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id 
		      LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' 
		      AND s.branch_id = '".$b_row[0]."' ".$sql_where." GROUP BY p.prod_id ORDER BY p.prod_id) as t".$b_row[0]." 
		      ON t.prod_id = t".$b_row[0].".prod_id";
       	    	}
    	    }
	    $sql = $sql1.$selected_column.$sql2.$sql_repeat." ORDER BY t.amt ".$order_seq;	    
    }
    if($report_mode == "overview" && $selected_date != "none" && $selected_date != NULL){
	    $sql_where = " AND DATE(s.date_time) = '".$selected_date."' ";
	    $sql = " SELECT DATE(s.date_time) as selected_date, SUM(si.prod_amount) as amt 
        	     FROM product p LEFT JOIN unit u ON p.unit_id = u.unit_id LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id 
		     LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' 
		     AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY selected_date ";	    
    }
    if($report_mode == "distinct_overview" && $selected_date != "none" && $selected_date != NULL){
	    $sql_where = " AND DATE(s.date_time) = '".$selected_date."' ";
	    $sql = " SELECT DATE(s.date_time) as selected_date, COUNT(DISTINCT p.prod_id) as amt 
        	     FROM product p LEFT JOIN unit u ON p.unit_id = u.unit_id LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id 
		     LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' 
		     AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY selected_date ";	    
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
	echo json_encode($output);
    }else{
	echo "fail";
    }	
    mysqli_close($conn);
?>