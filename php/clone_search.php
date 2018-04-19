<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos"); 
    $data = $_GET['q'];   
      
    $query = "SELECT p.prod_id,p.prod_name,p.barcode,p.prod_price,u.unit_name,p.img_string,p.prod_cost 
        FROM product p INNER JOIN unit u ON p.unit_id = u.unit_id
        WHERE p.prod_name LIKE '%$data%' OR p.barcode LIKE '%$data%'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){    
      while($row = mysqli_fetch_array($result)){          
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
    /*$age = array("Peter"=>"$data", "Ben"=>"37", "Joe"=>"43");
    echo  json_encode($age);*/
    mysqli_close($conn);

?>