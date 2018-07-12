<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");    
    $input  = $_POST['JSON'];
    $obj = json_decode($input,true); 
    $order_id  = $_POST['order_id'];
    $sum  = $_POST['sum']; 	
    $discount  = $_POST['discount'];  
    $total  = $_POST['total'];
    $get = $_POST['get'];
    $change = $_POST['change'];
    $branch_id = $_POST['branch_id'];
    $comment = $_POST['comment'];
    $dt = $_POST['dt'];

    $query = "SELECT * FROM sale_order WHERE order_id = '$order_id'";  
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){
            $summ = $row['sum_price'];            
            $diss = $row['total_discount'];
            $totall = $row['total_price'];
            $gett = $row['get_money'];
            $chngg = $row['change_money'];
        }   
    }


    mysqli_begin_transaction($conn);
    $query = "INSERT INTO order_record(order_id,datetime,sum_price,total_price,note,branch_id,total_discount,get_money,change_money) 
    VALUES ('$order_id','$dt','$summ','$totall','$comment','$branch_id','$diss','$gett','$chngg')";  
    $result = mysqli_query($conn, $query);  
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }      
    $record_id = mysqli_insert_id($conn);	  

    $query = "UPDATE sale_order SET sum_price='$sum',total_discount='$discount',total_price='$total'
    ,get_money='$get',change_money='$change' WHERE order_id = '$order_id'";  
    $result = mysqli_query($conn, $query);
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }

    foreach ($obj as $data)
    {
        $no = $data['item_id'];
        $amt = $data['amt'];  
        $price = $data['price'];  
        $prod_id = $data['prod_id'];  

        $query = "SELECT * FROM sale_order_item WHERE order_id = '$order_id' AND item_id='$no'";  
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){    
            while($row = mysqli_fetch_array($result)){
                $amt_old = $row['prod_amount'];
                $prod_id_old = $row['prod_id'];
                $price_old = $row['prod_price'];       
                $dis_old = $row['prod_discount'];         
                $bonus_old = $row['bonus'];                    
            }   
        } 
        
        if($amt_old > $amt){
            $dif = $amt_old - $amt;
            $query = "UPDATE product_branch SET amount=amount+'$dif' WHERE branch_id = '$branch_id' AND prod_id = '$prod_id'";  
        }else if($amt > $amt_old){
            $dif = $amt - $amt_old;
            $query = "UPDATE product_branch SET amount=amount-'$dif' WHERE branch_id = '$branch_id' AND prod_id = '$prod_id'";  
        }       
        $result = mysqli_query($conn, $query);
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }

        $query = "INSERT INTO order_record_item(order_id,item_id,prod_id,prod_price,prod_amount,prod_discount,bonus) 
        VALUES ('$record_id','$no','$prod_id_old','$price_old','$amt_old','$dis_old','$bonus_old')";
        $result = mysqli_query($conn, $query); 
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }  

        $query = "UPDATE sale_order_item SET prod_amount='$amt',prod_price='$price' WHERE order_id = '$order_id' AND item_id='$no'";  
        $result = mysqli_query($conn, $query);
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