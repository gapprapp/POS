<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $date_from  = $_POST['date_from']; // Ex: "2018-09-01";
    $date_to  = $_POST['date_to']; // Ex: "2018-09-19";
    $year_list = $_POST['year_list'];  //{view_by_date|view_by_month|view_by_year}
    $year = $_POST['year'];           //selected year for view_by_month; Ex: "2018";
    $order_seq = $_POST['order_seq']; //{ASC|DESC} *UPPERCASE TEXT

    date_default_timezone_set('Asia/Bangkok');

    if($year_list == "view_by_date" && $date_from != "none" && $date_to != "none"){
	    $sql_where = " AND DATE(s.date_time) >= '".$date_from."' AND DATE(s.date_time) <= '".$date_to."' ";
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
    if($year_list == "view_by_year"){
	    $sql_where = "";
	    $sql1 = "SELECT t.prod_id, t.barcode, t.prod_name, t.prod_price, t.unit_name, t.amt";
	    $selected_column = "";
	    $sql2 = " FROM (SELECT p.prod_id, p.barcode, p.prod_name, si.prod_price, u.unit_name, SUM(si.prod_amount) as amt 
        	     FROM product p LEFT JOIN unit u ON p.unit_id = u.unit_id LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id 
		     LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' 
		     AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY p.prod_id ORDER BY p.prod_id) as t ";
	    $sql_repeat = "";

            $pre_sql = "SELECT DISTINCT YEAR(s.date_time) FROM sale_order s WHERE 1";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($y_row = mysqli_fetch_array($pre_result)){      
		    $selected_column = $selected_column.", t".$y_row[0].".amt".$y_row[0]." ";
		    $sql_repeat = $sql_repeat." LEFT JOIN (SELECT p.prod_id, SUM(si.prod_amount) as amt".$y_row[0]." 
		      FROM product p LEFT JOIN unit u ON p.unit_id = u.unit_id LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id 
		      LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' 
		      AND YEAR(s.date_time) = '".$y_row[0]."' ".$sql_where." GROUP BY p.prod_id ORDER BY p.prod_id) as t".$y_row[0]." 
		      ON t.prod_id = t".$y_row[0].".prod_id";
		}
    	    }
	    $sql = $sql1.$selected_column.$sql2.$sql_repeat." ORDER BY t.amt ".$order_seq;	
    }
    if($year_list == "view_by_month"){
	    if($year == NULL || $year == "none"){
		$year = "YEAR(CURDATE())";
	    }else{
		$year = "'".$year."'";
	    }
	    $sql_where = " AND YEAR(s.date_time) = ".$year." ";
    	    $sql1 = "SELECT t.prod_id, t.barcode, t.prod_name, t.prod_price, t.unit_name, t.amt";
    	    $selected_column = "";
    	    $sql2 = " FROM (SELECT p.prod_id, p.barcode, p.prod_name, si.prod_price, u.unit_name, SUM(si.prod_amount) as amt 
               	      FROM product p LEFT JOIN unit u ON p.unit_id = u.unit_id LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id 
		      LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' 
		      AND si.order_id NOT LIKE '%refund%' ".$sql_where." GROUP BY p.prod_id ORDER BY p.prod_id) as t ";
	    $sql_repeat = "";

            $pre_sql = "SELECT DISTINCT MONTH(s.date_time) FROM sale_order s WHERE YEAR(s.date_time)=".$year." ";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($m_row = mysqli_fetch_array($pre_result)){      
		    $selected_column = $selected_column.", t".$m_row[0].".amt".$m_row[0]." ";
		    $sql_repeat = $sql_repeat." LEFT JOIN (SELECT p.prod_id, SUM(si.prod_amount) as amt".$m_row[0]." 
	      		FROM product p LEFT JOIN unit u ON p.unit_id = u.unit_id LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id 
			LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' 
		        AND MONTH(s.date_time) = '".$m_row[0]."' ".$sql_where." GROUP BY p.prod_id ORDER BY p.prod_id) as t".$m_row[0]." ON t.prod_id = t".$m_row[0].".prod_id";
		}
    	    }
	    $sql = $sql1.$selected_column.$sql2.$sql_repeat." ORDER BY t.amt ".$order_seq;
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