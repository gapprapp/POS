<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");	
    $name = $_POST['name'];	
    $tel = $_POST['tel'];	
	$start = $_POST['start'];
	$sort_by = $_POST['sort_by'];
	$order = $_POST['order'];
	$type = $_POST['type'];

	$sql;
	if($sort_by == "none" && $order == "none"){
		if($name || $tel || $type){			
			$sql = "SELECT customer_id,customer_name,address,tel,customer_type FROM customer 
            WHERE customer_name LIKE '%$name%' AND tel LIKE '%$tel%' AND customer_type LIKE '%$type%' LIMIT $start,100";
		}else{			
			$sql = "SELECT customer_id,customer_name,address,tel,customer_type FROM customer LIMIT $start,100";
		}		
	}else if($sort_by != "none" && $order != "none"){
		if($name || $tel || $type){
			if($order == "asc"){
                $sql = "SELECT customer_id,customer_name,address,tel,customer_type FROM customer 
                WHERE customer_name LIKE '%$name%' AND tel LIKE '%$tel%' AND customer_type LIKE '%$type%' 
				ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
				$sql = "SELECT customer_id,customer_name,address,tel,customer_type FROM customer 
                WHERE customer_name LIKE '%$name%' AND tel LIKE '%$tel%' AND customer_type LIKE '%$type%' 
				ORDER BY $sort_by DESC LIMIT $start,100";
			}
		}else{
			if($order == "asc"){
                $sql = "SELECT customer_id,customer_name,address,tel,customer_type FROM customer 
                ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
				$sql = "SELECT customer_id,customer_name,address,tel,customer_type FROM customer
                ORDER BY $sort_by DESC LIMIT $start,100";
			}
		}
	}
	
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0) {
		while($row = $result->fetch_assoc()){		
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