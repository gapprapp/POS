<?php
	include "db.php";	

	$headers = apache_request_headers();
	//echo $headers['register-csrf']; /
	
    $email = $_POST['email'];	
    $pass = $_POST['pass'];
    $pass = password_hash($pass, PASSWORD_DEFAULT);	
    $active = $_POST['active'];
    $token = bin2hex(openssl_random_pseudo_bytes(16));

    if($headers['register-csrf'] == '534ebf0ec9f72ed7ed0cfc1e4e3e8465'){
    	$sql = "INSERT INTO account(email,password,active, token) VALUES ('$email','$pass','$active', '$token')";
    	$result = mysqli_query($conn, $sql);
    	$acc_id =  mysqli_insert_id($conn);

    	// Email activate
    	$url = "http://fxbsol.com/mailverify.html?t=$token&account_id=$acc_id"; 
    	$link = '<p>To verify email from Wisely Application please </p><a href="' . $url . '">' . 'Click Here!!'. '</a> <p>-Wisely Team</p>';

    	$subject = "E-mail ativation from Wisely.com ";

    	// Always set content-type when sending HTML email
    	$headers = "MIME-Version: 1.0" . "\r\n";
    	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    	// More headers
        $headers .= 'From: <fxbsol@gmail.com>' . "\r\n";
        $headers .= 'Cc: fxbsol@gmail.com' . "\r\n";
    	
    	mail($email, $subject, $link, $headers); 
    	
    	mysqli_close($conn); 
    }else{
    	echo "You can't register from outer source";
    }
?>