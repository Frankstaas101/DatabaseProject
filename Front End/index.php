<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

<div>
<?php
	include('session.php');
	echo $nav_bar_and_logo;
?>
</div>
<div class="form-div">

<body>
<form action="login.php" method="post">
    <p>
        <label for="userName">Username:</label>
        <input type="text" name="userName" id="userName"> 
        <label for="pass">    Password:</label>
        <input type="password" name="pass" id="pass"> 
        <input class="loginBtn" type="submit" value="Login" >
    </p>
   
</form>
</div>
</body>

</head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="notify.js"></script>
<script type="text/javascript">
</script>
