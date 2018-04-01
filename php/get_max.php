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
	        AND t.prod_type_name LIKE '%$type%'";
		}else
		{
			$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
			FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
			INNER JOIN unit u ON p.unit_id = u.unit_id";
		}
		
	}else if($sort_by != "none" && $order != "none"){
		if($name || $barcode || $type){
			if($order == "asc"){
			$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
			FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
			INNER JOIN unit u ON p.unit_id = u.unit_id WHERE p.barcode LIKE '%$barcode%' AND p.prod_name LIKE '%$name%' 
	        AND t.prod_type_name LIKE '%$type%' ORDER BY $sort_by ASC";
			}else if($order == "desc"){
				$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
				FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
				INNER JOIN unit u ON p.unit_id = u.unit_id WHERE p.barcode LIKE '%$barcode%' AND p.prod_name LIKE '%$name%' 
	        AND t.prod_type_name LIKE '%$type%' ORDER BY $sort_by DESC";
			}
		}else{
			if($order == "asc"){
				$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
				FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
				INNER JOIN unit u ON p.unit_id = u.unit_id ORDER BY $sort_by ASC";
			}else if($order == "desc"){
				$sql = "SELECT p.prod_id,p.prod_name,p.barcode,t.prod_type_name,u.unit_name,p.prod_cost,p.prod_price 
				FROM product p INNER JOIN prod_type t ON p.prod_type = t.prod_type 
				INNER JOIN unit u ON p.unit_id = u.unit_id ORDER BY $sort_by DESC";
			}
		}
	}
	
	$result = mysqli_query($conn, $sql);
	$number_prod = mysqli_num_rows($result);

	if($result){
		echo $number_prod;		   
	}else{
		echo "fail";
	}
	
	mysqli_close($conn);
?>