<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $user_id = $_POST['user_id'];        
	 
	$sql_pass = "SELECT role FROM user WHERE user_id = '$user_id'";
	$res_pass = mysqli_query($conn, $sql_pass);

	if(mysqli_num_rows($res_pass) > 0){
		while($row = mysqli_fetch_array($res_pass)){		
			echo $row['role'];		
		}		
	}

	mysqli_close($conn);
?>