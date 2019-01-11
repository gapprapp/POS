<?php
    	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	date_default_timezone_set('Asia/Bangkok');
	$branch_id = $_POST["branch_id"];
	$branch_id = '1';

	if(is_numeric($branch_id)){		
		$sql_where = "WHERE pb.branch_id = '".$branch_id."'";
	}else{
		$sql_where = "WHERE pb.branch_id = '1'";
	}

	$sql = "SELECT t.prod_id, p.prod_name, ROUND(AVG(t.day_amount),2) as average, pb.amount stock_amt 
		FROM (SELECT si.prod_id, MONTH(s.date_time), YEAR(s.date_time), SUM(si.prod_amount)/30 day_amount 
		FROM sale_order_item si LEFT JOIN sale_order s ON si.order_id = s.order_id 
		GROUP BY si.prod_id, MONTH(s.date_time), YEAR(s.date_time) ORDER BY si.prod_id 
		) as t LEFT JOIN product p ON t.prod_id = p.prod_id LEFT JOIN product_branch pb ON p.prod_id = pb.prod_id 
		".$sql_where." GROUP BY t.prod_id ORDER BY average DESC";
    	$result = mysqli_query($conn, $sql);
    	if(mysqli_num_rows($result) > 0){    
       		while($row = mysqli_fetch_array($result)){      
       			if($row["stock_amt"] <= 0)
				$row["runout_type"] = "0-gray";
       			else if($row["stock_amt"] < $row["average"]*3)
				$row["runout_type"] = "1-red";
			else if($row["stock_amt"] < $row["average"]*7)
				$row["runout_type"] = "2-orange";
			else if($row["stock_amt"] < $row["average"]*15)
				$row["runout_type"] = "3-pink";
			else $row["runout_type"] = "4-green";
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