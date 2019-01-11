<?php
    	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
	date_default_timezone_set('Asia/Bangkok');	
	$exec_state = false;

/**
 * linear regression function
 * @param $x array x-coords
 * @param $y array y-coords
 * @returns array() m=>slope, b=>intercept
 */
function linear_regression($x, $y) {

  // calculate number points
  $n = count($x);
  
  // ensure both arrays of points are the same size
  if ($n != count($y)) {

    trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);
  
  }

  // calculate sums
  $x_sum = array_sum($x);
  $y_sum = array_sum($y);

  $xx_sum = 0;
  $xy_sum = 0;
  
  for($i = 0; $i < $n; $i++) {
  
    $xy_sum+=($x[$i]*$y[$i]);
    $xx_sum+=($x[$i]*$x[$i]);
    
  }
  
  // calculate slope
  $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));
  
  // calculate intercept
  $b = ($y_sum - ($m * $x_sum)) / $n;
    
  // return result
  return array("m"=>$m, "b"=>$b);

}

	$sql = "SELECT si.prod_id, p.prod_name, MONTH(s.date_time), YEAR(s.date_time), SUM(si.prod_amount) month_amount 
		FROM sale_order_item si LEFT JOIN sale_order s ON si.order_id = s.order_id LEFT JOIN product p ON si.prod_id = p.prod_id 
		GROUP BY si.prod_id, YEAR(s.date_time), MONTH(s.date_time) ORDER BY si.prod_id, YEAR(s.date_time), MONTH(s.date_time)";
    	$result = mysqli_query($conn, $sql);
    	if(mysqli_num_rows($result) > 0){    
       		while($row = mysqli_fetch_array($result)){      
       			$output[] = $row;
       		}
	} 
    	if($result){
		$exec_state = true;
    	}
    	mysqli_close($conn);

	if($exec_state){
		var_dump($output);
		//var_dump( linear_regression(array(1, 2, 3, 4), array(1.5, 1.6, 2.1, 3.0)) );
		die();
	}
?>