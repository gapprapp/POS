<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	$b_id = $_POST['b_id'];	

	$sql = "SELECT SUM(s.total_price) total_price, DATE_FORMAT(s.date_time,'%d/%c/%Y') datetime, t.udt 
    FROM sale_order s, (SELECT c.date_time udt FROM cash_record c WHERE c.branch_id='$b_id'
	ORDER BY c.date_time DESC LIMIT 1) t 
    WHERE s.branch_id='$b_id' AND s.payment_type='เงินสด' AND s.order_number NOT LIKE '%can%' AND s.date_time >= udt GROUP BY datetime";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$output[] = $row;
		}		
	}
	//เงินโอน 
	$sql = "SELECT SUM(s.total_price) total_price, DATE_FORMAT(s.date_time,'%d/%c/%Y') datetime, t.udt 
    FROM sale_order s, (SELECT c.date_time udt FROM cash_record c WHERE c.branch_id='$b_id'
	ORDER BY c.date_time DESC LIMIT 1) t 
    WHERE s.branch_id='$b_id' AND s.payment_type='เงินโอน' AND s.order_number NOT LIKE '%can%' AND s.date_time >= udt GROUP BY datetime";
	$result = mysqli_query($conn, $sql);

	//ลูกหนี้
	$sql = "SELECT SUM(s.total_price) total_price, DATE_FORMAT(s.date_time,'%d/%c/%Y') datetime, t.udt 
    FROM sale_order s, (SELECT c.date_time udt FROM cash_record c WHERE c.branch_id='$b_id'
	ORDER BY c.date_time DESC LIMIT 1) t 
    WHERE s.branch_id='$b_id' AND s.payment_type='ลูกหนี้' AND s.order_number NOT LIKE '%can%' AND s.date_time >= udt GROUP BY datetime";
	$result = mysqli_query($conn, $sql);
	
	//ยอดก่อนหักส่วนลด
	$sql = "SELECT SUM(s.sum_price) sum_price, DATE_FORMAT(s.date_time,'%d/%c/%Y') datetime, t.udt 
    FROM sale_order s, (SELECT c.date_time udt FROM cash_record c WHERE c.branch_id='$b_id'
	ORDER BY c.date_time DESC LIMIT 1) t 
    WHERE s.branch_id='$b_id' AND s.order_number NOT LIKE '%can%' AND s.date_time >= udt GROUP BY datetime";
	$result = mysqli_query($conn, $sql);

	//หักส่วนลด
	$sql = "SELECT SUM(s.total_discount) discount, DATE_FORMAT(s.date_time,'%d/%c/%Y') datetime, t.udt 
    FROM sale_order s, (SELECT c.date_time udt FROM cash_record c WHERE c.branch_id='$b_id'
	ORDER BY c.date_time DESC LIMIT 1) t 
    WHERE s.branch_id='$b_id' AND s.order_number NOT LIKE '%can%' AND s.date_time >= udt GROUP BY datetime";
	$result = mysqli_query($conn, $sql);

	//คงเหลือ
    $sql = "SELECT SUM(s.total_price) total_price, DATE_FORMAT(s.date_time,'%d/%c/%Y') datetime, t.udt 
    FROM sale_order s, (SELECT c.date_time udt FROM cash_record c WHERE c.branch_id='$b_id'
	ORDER BY c.date_time DESC LIMIT 1) t 
    WHERE s.branch_id='$b_id' AND s.order_number NOT LIKE '%can%' AND s.date_time >= udt GROUP BY datetime";
	$result = mysqli_query($conn, $sql);

    if($result){
		echo json_encode($output);		   
	}else{
		echo "fail";
	}

	mysqli_close($conn);
?>