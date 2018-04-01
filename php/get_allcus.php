<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");	
	$name = $_POST['name'];	
	$start = $_POST['start'];
	$sort_by = $_POST['sort_by'];
	$order = $_POST['order'];

	$sql;
	if($sort_by == "none" && $order == "none"){
		if($name){			
			$sql = "SELECT customer_id,customer_name,address,tel FROM customer 
            WHERE customer_name LIKE '%$name%' AND tel LIKE '%$tel%' LIMIT $start,100";
		}else
		{			
			$sql = "SELECT customer_id,customer_name,address,tel FROM customer LIMIT $start,100";
		}		
	}else if($sort_by != "none" && $order != "none"){
		if($name){
			if($order == "asc"){
                $sql = "SELECT customer_id,customer_name,address,tel FROM customer 
                WHERE customer_name LIKE '%$name%' AND tel LIKE '%$tel%' ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
				$sql = "SELECT customer_id,customer_name,address,tel FROM customer 
                WHERE customer_name LIKE '%$name%' AND tel LIKE '%$tel%' ORDER BY $sort_by DESC LIMIT $start,100";
			}
		}else{
			if($order == "asc"){
                $sql = "SELECT customer_id,customer_name,address,tel FROM customer 
                ORDER BY $sort_by ASC LIMIT $start,100";
			}else if($order == "desc"){
				$sql = "SELECT customer_id,customer_name,address,tel FROM customer
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