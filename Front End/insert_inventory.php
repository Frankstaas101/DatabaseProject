<?php
	include('session.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$ammount_in = mysqli_real_escape_string($connection, $_POST['ammount']);
    	$UPC_in = mysqli_real_escape_string($connection, $_POST['UPC']);
		$rolevel_in = mysqli_real_escape_string($connection, $_POST['rolevel']);
		$price_in = mysqli_real_escape_string($connection, $_POST['price']);
		$Pname_in = mysqli_real_escape_string($connection, $_POST['product']);
		$Sname_in = mysqli_real_escape_string($connection, $_POST['supplier']);
		
		if ($price_in > 99) { $price_in = 99; } 
		else if ($price_in < 0) { $price_in = 0; }
		
		$sql = "INSERT INTO product(UPC,Pname,price,Sname,ammount,reorderlevel) VALUES('$UPC_in', '$Pname_in', $price_in, '$Sname_in', $ammount_in, $rolevel_in);";

		if (mysqli_query($connection, $sql)) {
			echo "Added New Entry!";
			header("Location: manage_inventory.php");
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($con);
		}
		
	}
?>