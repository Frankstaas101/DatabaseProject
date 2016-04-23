

<?php
   include('session.php');
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Welcome</title>
</head>

<body>
<h1>Welcome <?php echo $login_session; ?></h1>
<h1><a href = "logout.php">Sign Out</a> <a href = "products.php">View Products</a></h1>

</body>
</html>