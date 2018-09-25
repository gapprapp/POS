<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	$name = $_POST['name'];	
	$pass = $_POST['pass'];	
    
	// Check pass match 
	$sql_pass = "SELECT user_id,pwd,role FROM user WHERE user_name = '$name'";
	$res_pass = mysqli_query($conn, $sql_pass);

	if(mysqli_num_rows($res_pass) > 0){
		while($row = mysqli_fetch_array($res_pass)){
			if(password_verify($pass, $row['pwd'])){
				$output[] = $row;
				echo json_encode($output);
			}else{
				echo "fail";
			}		
		}		
	}else{
		echo "fail";
	}

	mysqli_close($conn);
?>