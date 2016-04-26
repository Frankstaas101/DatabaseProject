<?php
	include('session.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {
 
    	$quantity_in = mysqli_real_escape_string($connection, $_POST['quantity']);
    	$UPC_in = mysqli_real_escape_string($connection, $_POST['UPC']);
		
		if ($quantity_in <= 0) {
			$sql = "DELETE FROM contains WHERE UPC = '$UPC_in' AND orderID = '$current_orderID';";
	
			if (mysqli_query($connection, $sql)) {
				echo "Removed";
				header("Location: edit_order.php");
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($con);
			}
		$con->close(); 
		} else {
		
			$sql = "UPDATE contains SET quantity ='$quantity_in' WHERE UPC = '$UPC_in' AND orderID = '$current_orderID';";
		
			if (mysqli_query($connection, $sql)) {
				header("Location: edit_order.php");
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($con);
			}
			$con->close(); 
		}
	}
?>