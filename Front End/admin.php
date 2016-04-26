<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>


<?php
	include('session.php');
	echo $nav_bar_and_logo;
?>

<ul class="adminNavBar">
    <li><a href="manage_inventory.php">Manage Inventory</a></li>
    <li><a href="manage_customers.php">Manage Customer Info</a></li>
    <li><a href="manage_suppliers.php">Manage Supplier Info</a></li>
    <li><a href="manage_products.php">Manage Product Info</a></li>
    <li><a href="load_from_files.php">Load Files</a></li>
</ul>
 
  
</form>
</head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="notify.js"></script>
<script type="text/javascript">
</script>