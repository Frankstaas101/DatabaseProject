<?php
	include('session.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {
 
    	$UPC_in = mysqli_real_escape_string($connection, $_POST['UPC']);
		
		$sql = "DELETE FROM contains WHERE UPC = '$UPC_in' AND orderID = '$current_orderID';";
	
		if (mysqli_query($connection, $sql)) {
			header("Location: edit_order.php");
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($con);
		}
		$con->close(); 
	}
?>