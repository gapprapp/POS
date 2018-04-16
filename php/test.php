<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
 
    $sql_pass = "SELECT img_string FROM product WHERE prod_id = '4392'";
	$res_pass = mysqli_query($conn, $sql_pass);

	if(mysqli_num_rows($res_pass) > 0){
		while($row = mysqli_fetch_array($res_pass)){		
			echo $row['img_string'];		
		}		
	}


    mysqli_close($conn);	
?>