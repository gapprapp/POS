<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	
	$barcode = $_POST['barcode'];
	$name = $_POST['name'];
	$type = $_POST['type'];
	$start = $_POST['start'];
	$sort_by = $_POST['sort_by'];
	$order = $_POST['order'];

	$sql;
	if($sort_by == "none" && $order == "none"){
		if($name || $barcode || $type){
			// MEW CHECK TEE
			$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
			FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
			INNER JOIN unit u ON p.unit_id = u.unit_id WHERE p.barcode LIKE '%$barcode%' AND p.prod_name LIKE '%$name%' 
	        AND t.prod_type_name LIKE '%$type%' LIMIT $start,100";
		}else
		{
			/*$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
			FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
			INNER JOIN unit u ON p.unit_id = u.unit_id LIMIT $start,100";*/

			$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price FROM (SELECT prod_id,prod_name,barcode,prod_type,unit_id,prod_cost,prod_price FROM product LIMIT $start,100) p INNER JOIN prod_type t ON p.prod_type = t.prod_type INNER JOIN unit u ON p.unit_id = u.unit_id";
		}
		
	}else if($sort_by != "none" && $order != "none"){
		if($name || $barcode || $type){
			if($order == "asc"){
			$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
			FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
			INNER JOIN unit u ON p.unit_id = u.unit_id WHERE p.barcode LIKE '%$barcode%' AND p.prod_name LIKE '%$name%' 
	        AND t.prod_type_name LIKE '%$type%' ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
				$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
				FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
				INNER JOIN unit u ON p.unit_id = u.unit_id WHERE p.barcode LIKE '%$barcode%' AND p.prod_name LIKE '%$name%' 
	        AND t.prod_type_name LIKE '%$type%' ORDER BY $sort_by DESC LIMIT $start,100";
			}
		}else{
			if($order == "asc"){
				$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
				FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
				INNER JOIN unit u ON p.unit_id = u.unit_id ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
				$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
				FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
				INNER JOIN unit u ON p.unit_id = u.unit_id ORDER BY $sort_by DESC LIMIT $start,100";
			}
		}
	}
	
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0) {
		while($row = $result->fetch_assoc()){						
			$prod_id = $row['prod_id'];

			$query = "SELECT amount FROM product_branch WHERE prod_id = '$prod_id'";
			$result1 = mysqli_query($conn, $query);	
			
			if(mysqli_num_rows($result1) > 0){    
			  while($row1 = mysqli_fetch_array($result1)){
				$amt = $row1['amount']; 
				array_push($row,$amt);       
			  }         
			}			
			$output[] = $row;  
		}
		//usort($output, "asc_prodname");
	}

	if($result){
		echo json_encode($output);		   
	}else{
		echo "fail";
	}
	
	mysqli_close();
?>