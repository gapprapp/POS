<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $cus_id = $_POST['cus_id'];    
    $sprice = $_POST['sprice'];
    $prod_id = $_POST['prod_id'];    

    if(isset($_POST['prod_id_old'])){
        $prod_id_old = $_POST['prod_id_old'];
        $sql = "UPDATE detail_customer SET special_prod_price = '$sprice',prod_id = '$prod_id' 
        WHERE customer_id = '$cus_id' AND prod_id = '$prod_id_old' AND customer_id != 0";     
    }else{
        $sql = "INSERT INTO detail_customer (prod_id,customer_id,special_prod_price) 
        VALUE ('$prod_id','$cus_id','$sprice')";     
    }
    $result = mysqli_query($conn, $sql);  

    if($result){
        echo "success";
    }else{
        echo "fail";
    }
    mysqli_close($conn);
?>