<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos"); 
    $cus_id = $_POST['cus_id'];  
    $prod_id = $_POST['prod_id'];   
 
    $sql = "SELECT special_prod_price FROM detail_customer WHERE prod_id = '$prod_id' AND customer_id = '$cus_id'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            echo $row['special_prod_price'];             
        }		
    }else{
        echo "fail";
    }  
    mysqli_close($conn);
?>