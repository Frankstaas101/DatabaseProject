<?php
	include('session.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {
 
    	$ammount_in = mysqli_real_escape_string($connection, $_POST['ammountEdit']);
    	$UPC_in = mysqli_real_escape_string($connection, $_POST['UPCedit']);
		
		if ($ammount_in <= 0) {
			$sql = "DELETE FROM product WHERE UPC = '$UPC_in';";
	
			if (mysqli_query($connection, $sql)) {
				echo "Removed";
				header("Location: manage_inventory.php");
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($con);
			}
		$con->close(); 
		} else {
		
			$sql = "UPDATE product SET ammount ='$ammount_in' WHERE UPC = '$UPC_in';";
		
			if (mysqli_query($connection, $sql)) {
				header("Location: manage_inventory.php");
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($con);
			}
			$con->close(); 
		}
	}
?>