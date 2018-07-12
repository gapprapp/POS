<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $order_id  = $_POST['order_id'];
    
    $sql = "SELECT return_id,datetime,sum_price,note FROM return_order WHERE order_id = '$order_id'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){  
            $re_id = $row['return_id']; 
            $type = array('type' => "refund");
            array_push($row,$type);
            $output2 = [];
            $sql = "SELECT p.prod_name,r.prod_price,r.prod_amount FROM return_order_item r INNER JOIN product p ON 
            r.prod_id = p.prod_id WHERE r.return_id = '$re_id'";
            $result1 = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result1) > 0){    
                while($row1 = mysqli_fetch_array($result1)){           
                    $output2[] = $row1;
                } 
                array_push($row,$output2);       
            }    
            $output[] = $row;
        }   
    }   

    $sql = "SELECT record_id,datetime,sum_price,note FROM order_record WHERE order_id = '$order_id'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){ 
            $re_id = $row['record_id'];           
            $type = array('type' => "edit");
            array_push($row,$type);
            $output2 = [];
            $sql = "SELECT r.prod_id,p.prod_name,r.prod_price,r.prod_amount FROM order_record_item r INNER JOIN product p ON 
            r.prod_id = p.prod_id WHERE r.order_id = '$re_id'";
            $result1 = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result1) > 0){    
                while($row1 = mysqli_fetch_array($result1)){           
                    $output2[] = $row1;
                } 
                array_push($row,$output2);       
            }    
            $output[] = $row;
        }   
    }
    function build_sorter($key) {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }
    
    usort($output, build_sorter('datetime'));

    if($result){    
        echo json_encode($output);
    }else{
        echo "fail";
    }    
    mysqli_close($conn);
?>