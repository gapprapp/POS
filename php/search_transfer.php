<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos"); 
    $data = $_POST['phrase'];   
      
    $query = "SELECT prod_id,prod_name,barcode FROM product WHERE prod_name LIKE '%$data%' OR barcode LIKE '%$data%'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){    
      while($row = mysqli_fetch_array($result)){
        $prod_id = $row['prod_id'];      
        $output2 = [];
        $sql = "SELECT amount FROM product_branch WHERE prod_id = '$prod_id' ORDER BY branch_id ASC";
        $result2 = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result2) > 0){
                while($row2 = mysqli_fetch_array($result2)){
                    $output2[] = $row2;                         
                }
                array_push($row,$output2);              		
            }       
        $output[] = $row;
      }   
    }else{    
        $output = array('prod_name' => 'Not Found');
      }   

    if($result){
        echo json_encode($output);		   
    }else{
        echo "fail";
    }
    mysqli_close($conn);

?>