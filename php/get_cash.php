<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $b_id = $_POST['b_id'];
    $date = $_POST['date'];
    $last_date;

    $sql = "SELECT date_time FROM cash_record WHERE date_time = (SELECT max(date_time) FROM cash_record 
    WHERE date_time < '$date') AND branch_id = '$b_id'";
    $result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
            $last_date = $row['date_time'];			
		}		
	}
    //เงินสด
	$sql = "SELECT SUM(total_price) total_price FROM sale_order WHERE branch_id='$b_id' AND payment_type='เงินสด' 
    AND order_number NOT LIKE '%can%' AND date_time BETWEEN '$last_date' AND '$date'";
	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
            array_push($row,$last_date);
			$output[] = $row;
		}		
	}else{
        $output[] = "none";
    }
	//เงินโอน 
	$sql = "SELECT SUM(total_price) total_price FROM sale_order WHERE branch_id='$b_id' AND payment_type='เงินโอน' 
    AND order_number NOT LIKE '%can%' AND date_time BETWEEN '$last_date' AND '$date'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$output[] = $row;
		}		
	}else{
        $output[] = "none";
    }

	//ลูกหนี้
	$sql = "SELECT SUM(total_price) total_price FROM sale_order WHERE branch_id='$b_id' AND payment_type='ลูกหนี้' 
    AND order_number NOT LIKE '%can%' AND date_time BETWEEN '$last_date' AND '$date'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$output[] = $row;
		}		
	}else{
        $output[] = "none";
    }
	
	//ยอดก่อนหักส่วนลด
	$sql = "SELECT SUM(sum_price) sum_price FROM sale_order WHERE branch_id='$b_id' AND order_number NOT LIKE '%can%' 
    AND date_time BETWEEN '$last_date' AND '$date'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$output[] = $row;
		}		
	}else{
        $output[] = "none";
    }

	//หักส่วนลด
	$sql = "SELECT SUM(total_discount) discount FROM sale_order WHERE branch_id='$b_id' AND order_number NOT LIKE '%can%' 
    AND date_time BETWEEN '$last_date' AND '$date'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$output[] = $row;
		}		
	}else{
        $output[] = "none";
    }

	//คงเหลือ
    $sql = "SELECT SUM(total_price) total_price FROM sale_order WHERE branch_id='$b_id' AND order_number NOT LIKE '%can%' 
    AND date_time BETWEEN '$last_date' AND '$date'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$output[] = $row;
		}		
	}else{
        $output[] = "none";
    }

    if($result){
		echo json_encode($output);		   
	}else{
		echo "fail";
	}

	mysqli_close($conn);
?>