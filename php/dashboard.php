<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $b_id  = $_POST['b_id']; //branch_id;
    //$b_id  = "1";

    date_default_timezone_set('Asia/Bangkok');
    
    if($b_id != NULL && $b_id != "none"){
	    $sql_where = $sql_where." AND s.branch_id = '".$b_id."' ";
    }

    $sql = "SELECT DATE(s.date_time) as d, SUM(s.total_price) as total_price FROM sale_order s WHERE s.order_number NOT LIKE '%can%' 
	    AND DATE(s.date_time)=CURDATE() ".$sql_where." GROUP BY DATE(s.date_time) ";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
       while($row = mysqli_fetch_array($result)){      
       		$output["sale_value"] = $row["total_price"];
       	}   
    }

    $sql = "SELECT DISTINCT s.payment_type, SUM(s.total_price) as total_price FROM sale_order s WHERE s.order_number NOT LIKE '%can%' 
            AND DATE(s.date_time)=CURDATE() ".$sql_where." GROUP BY s.payment_type ";    
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
       while($row = mysqli_fetch_array($result)){      
       		$output[$row["payment_type"]] = $row["total_price"];
       	}   
    }  

    $sql = "SELECT DATE(s.date_time) as d, COUNT(DISTINCT si.prod_id) as amt FROM sale_order_item si LEFT JOIN sale_order s ON si.order_id = s.order_id 
	    WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' AND DATE(s.date_time)=CURDATE() ".$sql_where." 
	    GROUP BY DATE(s.date_time) ";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
       while($row = mysqli_fetch_array($result)){      
       		$output["sku_item"] = $row["amt"];
       	}   
    } 

    $sql = "SELECT DATE(s.date_time) as d, SUM(si.prod_amount) as amt FROM sale_order_item si LEFT JOIN sale_order s ON si.order_id = s.order_id 
	    WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' AND DATE(s.date_time)=CURDATE() ".$sql_where." 
	    GROUP BY DATE(s.date_time) ";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
       while($row = mysqli_fetch_array($result)){      
       		$output["num_item"] = $row["amt"];
       	}   
    }  

    $sql = "SELECT DATE(s.date_time) as d, COUNT(s.order_id) as num_order FROM sale_order s WHERE s.order_number NOT LIKE '%can%' AND DATE(s.date_time)=CURDATE() ".$sql_where." 
	    GROUP BY DATE(s.date_time) ";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
       while($row = mysqli_fetch_array($result)){      
       		$output["num_order"] = $row["num_order"];
       	}   
    }  

    $sql = "SELECT DATE(s.date_time) as d, COUNT(DISTINCT s.customer_id) as num_customer FROM sale_order s WHERE s.order_number NOT LIKE '%can%' AND DATE(s.date_time)=CURDATE() ".$sql_where." 
	    GROUP BY DATE(s.date_time) ";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
       while($row = mysqli_fetch_array($result)){      
       		$output["num_customer"] = $row["num_customer"];
       	}   
    }  
    
    if($result){
	echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }else{
	echo "fail";
    }	
    mysqli_close($conn);
?>