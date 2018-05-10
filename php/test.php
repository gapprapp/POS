<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
		
	mysqli_begin_transaction($conn);		
	$sql = "INSERT INTO test (number_id,count) VALUE ('1','1')"; 
	$result = mysqli_query($conn, $sql);	
	if(!$result){
		mysqli_rollback($conn);
		echo "fail";
		exit;
	}
	$sql = "INSERT INTO test2 (temp,temp2) VALUE ('1','1')"; 
	$result = mysqli_query($conn, $sql);
	if(!$result){
		mysqli_rollback($conn);
		echo "fail";
		exit;
	}
	for($i = 0;$i < 3;$i++){
		if($i == 1){
			$sql = "INSERT INTO test2 (temp) VALUE ('$i')"; 
			$result = mysqli_query($conn, $sql);
		}else{
			$sql = "INSERT INTO test2 (temp,temp2) VALUE ('$i','$i')"; 
			$result = mysqli_query($conn, $sql);
		}		
		if(!$result){
			mysqli_rollback($conn);
			echo "fail";
			exit;
		}
	}		
	mysqli_commit($conn);          
	echo "success";
    mysqli_close($conn);	
?>