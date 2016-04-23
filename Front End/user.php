<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<?php
	include('session.php');
	echo $nav_bar_and_logo;
?>


	<h1>Hello <?php echo $login_session?></h1>
    <h1>Your Customer ID is: <?php echo $login_session_id?></h1>
    <h1>Your User Level is: <?php echo $login_session_privlages?></h1>

</head>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="notify.js"></script>
<script type="text/javascript">

function add(name) {
	$.notify("Added " + name, "success");
}

</script>