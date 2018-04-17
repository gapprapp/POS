<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");	
    $name = $_POST['name'];
    $tel = $_POST['tel'];	
	$start = $_POST['start'];
	$sort_by = $_POST['sort_by'];
	$order = $_POST['order'];

	$sql;
	if($sort_by == "none" && $order == "none"){
		if($name || $tel){			
			$sql = "SELECT customer_id,customer_name,address,tel FROM customer 
            WHERE customer_name LIKE '%$name%' AND tel LIKE '%$tel%'";
		}else
		{			
			$sql = "SELECT customer_id,customer_name,address,tel FROM customer";
		}		
	}else if($sort_by != "none" && $order != "none"){
		if($name || $tel){
			if($order == "asc"){
                $sql = "SELECT customer_id,customer_name,address,tel FROM customer 
                WHERE customer_name LIKE '%$name%' AND tel LIKE '%$tel%' ORDER BY $sort_by ASC";
			}else if($order == "desc"){
				$sql = "SELECT customer_id,customer_name,address,tel FROM customer 
                WHERE customer_name LIKE '%$name%' AND tel LIKE '%$tel%' ORDER BY $sort_by DESC";
			}
		}else{
			if($order == "asc"){
                $sql = "SELECT customer_id,customer_name,address,tel FROM customer 
                ORDER BY $sort_by ASC";
			}else if($order == "desc"){
				$sql = "SELECT customer_id,customer_name,address,tel FROM customer
                ORDER BY $sort_by DESC";
			}
		}
	}
	
	$result = mysqli_query($conn, $sql);
	$number_cus = mysqli_num_rows($result);

	if($result){
		echo $number_cus;		   
	}else{
		echo "fail";
	}
	
	mysqli_close($conn);
?>