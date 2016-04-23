<?php 

include('session.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
 
  // Escape user inputs for security
  $UPC_in = mysqli_real_escape_string($connection, $_POST['UPC']);

  // attempt insert query execution
  $sql = "SELECT username, password FROM user WHERE username = '$username_in' AND password = '$password_in'";
  $result = mysqli_query($connection, $sql);
  
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  
  $orderID = $row['orderID'];
  
  $count = mysqli_num_rows($result);
  
  if($count == 1) {
	   
	  // session_register("username_in");
	   
	   $_SESSION['login_user'] = $username_in;
	   
	   header("Location: welcome.php");
	  
	}else {
		
	   echo "Your Login Name or Password is invalid";
		echo "<h2><a href = \"index.php\">Try Again</a></h2>";
	   
	}
	
	
	
	$sql = "insert contains (product_UPC, customer_CID) values(\"" . $UPC . "\", \"" . $login_session_id . "\") where";
	
		if (mysqli_query($connection, $sql)) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($con);
		}
		$con->close(); 
	
} else {
		 
}
?>