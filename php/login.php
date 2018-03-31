<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	$name = $_POST['name'];	
	$pass = $_POST['pass'];
	$match = false; 
    
	// Check pass match 
	$sql_pass = "SELECT user_id,pwd FROM user WHERE user_name = '$name'";
	$res_pass = mysqli_query($conn, $sql_pass);

	if($mysqli_num_rows($res_pass) > 0){
		while($row = $res_pass->fetch_assoc()){
			if(password_verify($pass, $row['pwd'])){
				echo $row['user_id'];
			}		
		}		
	}else{
		echo "fail";
	}

	mysqli_close($conn);
?>