<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	$name = $_POST['name'];	
	$pass = $_POST['pass'];
	$match = false; 
    
	// Check pass match 
	$sql_pass = "SELECT pwd FROM user WHERE user_name = '$name'";
	$res_pass = mysqli_query($conn, $sql_pass);

	if($res_pass){
		while($row = $res_pass->fetch_assoc()){
			if(password_verify($pass, $row['pwd']))
				$match = true;
		}
		echo $match;
	}else{
		echo "fail";
	}

	mysqli_close($conn);
?>