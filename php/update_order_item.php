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

    mysqli_autocommit($conn,FALSE); 
    $query = "INSERT INTO order_record(order_id,sum_price,total_price,total_discount,get_money,change_money) 
    VALUES ('$order_id','$sum','$total','$discount','$get','$change')";  
    $result = mysqli_query($conn, $query);        
    
    $query = "UPDATE sale_order SET sum_price='$sum',total_discount='$discount',total_price='$total'
    ,get_money='$get',change_money='$change' WHERE order_id = '$order_id'";  
    $result = mysqli_query($conn, $query);

    foreach ($obj as $data)
    {
        $no = $data['item_id'];
        $amt = $data['amt'];  
        $prod_id = $data['prod_id'];  

        $query = "SELECT prod_amount FROM sale_order_item WHERE order_id = '$order_id' AND item_id='$no'";  
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){    
            while($row = mysqli_fetch_array($result)){
                $amt_old = $row['prod_amount'];               
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

        $query = "INSERT INTO order_record_item(order_id,item_id,prod_id,prod_amount) 
        VALUES ('$order_id','$no','$prod_id','$amt')";  
        $result = mysqli_query($conn, $query);   

        $query = "UPDATE sale_order_item SET prod_amount='$amt' WHERE order_id = '$order_id' AND item_id='$no'";  
        $result = mysqli_query($conn, $query);
    }

    if($result){
        echo "success";
        mysqli_commit($conn);          
    }else{
        echo "fail";
        mysqli_rollback($conn);
    }
    
    mysqli_close($conn);
?>