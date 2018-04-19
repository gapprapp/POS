<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos"); 
    $data = $_POST['phrase'];   
      
    $query = "SELECT p.prod_id,p.prod_name,p.barcode,p.prod_price,u.unit_name,p.img_string,p.prod_cost 
        FROM product p INNER JOIN unit u ON p.unit_id = u.unit_id
        WHERE p.prod_name LIKE '%$data%' OR p.barcode LIKE '%$data%'";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){    
      while($row = mysqli_fetch_array($result)){ 
        $old = array('old_price' => $row['prod_price']);
        array_push($row,$old);
        $prod_id = $row['prod_id'];
        if(isset($_GET['cus_id'])){          
            $cus_id = $_GET['cus_id'];
            $sql = "SELECT special_prod_price FROM detail_customer WHERE prod_id = '$prod_id' AND customer_id = '$cus_id'";
            $result1 = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result1) > 0){
                while($row1 = mysqli_fetch_array($result1)){
                     $row['prod_price'] = $row1['special_prod_price'];                    
                }		
            }
        }
        $sql = "SELECT amt_threshold,prod_price FROM detail_customer WHERE prod_id = '$prod_id' AND customer_id = 0";
        $result2 = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result2) > 0){
                while($row2 = mysqli_fetch_array($result2)){
                    $output2[] = $row2;                         
                }
                array_push($row,$output2);              		
            }       
        $output[] = $row;
      }   
    }

    if($result){
        echo json_encode($output);		   
    }else{
        echo "fail";
    }
    mysqli_close($conn);

?>