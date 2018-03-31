<?php
	include "db.php";	
	$email = $_POST['email'];	
    $pass = $_POST['pass'];
    $acc_id;
    $exist = false;
    $match = false;
    $verify = false;

    //Check mail exist
    $sql_mail = "SELECT email,acc_id FROM account WHERE email = '$email'";
    $res_mail = mysqli_query($conn, $sql_mail);

    while($row = $res_mail->fetch_assoc()){
    	if($row["email"] == $email){
    		$exist = true;
    		$acc_id = $row["acc_id"];
    	}
    }
    
	// Check pass match 
	$sql_pass = "SELECT password FROM account WHERE email = '$email'";
	$res_pass = mysqli_query($conn, $sql_pass);

	if($res_pass){
		while($row = $res_pass->fetch_assoc()){
			if(password_verify($pass, $row['password']))
				$match = true;
		}
	}

	//Check verify
	$sql_verify = "SELECT verify FROM account WHERE email = '$email'";
	$res_verify = mysqli_query($conn, $sql_verify);

	if($res_verify){
		while($row = $res_verify->fetch_assoc()){
			if($row['verify'] == 1)
				$verify = true;
		}
	}

	// login check
	if($exist && $match && $verify){
		// login success
		//check in db account-info this email exist or not 
    	// if not -> go edit_profile.html
    	// if yes -> go home.html 

    	$sql = "SELECT acc_id FROM account_info WHERE acc_id = '$acc_id'";
    	$res = mysqli_query($conn, $sql);

    	if(mysqli_num_rows($res) > 0){
    		$output = array('msg' => 'home', 'acc_id' => $acc_id);
    		$sql = "SELECT * FROM account_info WHERE acc_id = '$acc_id'";
    		$res = mysqli_query($conn, $sql);

    		while($row = $res->fetch_assoc()){
    			if($row['mobile'] == NULL || $row['post'] == NULL || $row['address'] == NULL || $row['state'] == NULL){
    				$output = array('msg' => 'edit', 'acc_id' => $acc_id);
    				echo json_encode($output);
    			}else{
    				echo json_encode($output);
    			}
    		}
    	}else{
    		$output = array('msg' => 'edit', 'acc_id' => $acc_id);
    		echo json_encode($output);
    	}

	}else if($exist && $verify && !($match)){
		$output = array('msg' => 'wrong password');
		echo json_encode($output);
	}else if($exist && !($verify)){
		$output = array('msg' => 'please verify');
		echo json_encode($output);
	}else{
		$output = array('msg' => 'email not exist');
		echo json_encode($output);
	}
	mysqli_close($conn);
?>