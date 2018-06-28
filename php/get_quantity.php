<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $output = array();

	$sql = "SELECT q.prod_id,p.prod_name,p.barcode,u.unit_name FROM detail_quantity q
    INNER JOIN product p ON q.prod_id = p.prod_id INNER JOIN unit u ON p.unit_id = u.unit_id 
    GROUP BY q.prod_id";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0) {
		while($row = $result->fetch_assoc()){
            $prod_id = $row['prod_id'];
            $sql1 = "SELECT q.prod_id_sub,q.quantity,p.prod_name,p.barcode,u.unit_name FROM detail_quantity q
            INNER JOIN product p ON q.prod_id_sub = p.prod_id INNER JOIN unit u ON p.unit_id = u.unit_id 
            WHERE q.prod_id = '$prod_id' ORDER BY q.quantity ASC";
            $result1 = mysqli_query($conn, $sql1);
            $output2 = array();
            if(mysqli_num_rows($result1) > 0) {
                while($row1 = $result1->fetch_assoc()){
                    $output2[] = $row1;                    
                }
                array_push($row,$output2);
            }               
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