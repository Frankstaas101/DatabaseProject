<head>
<link rel="stylesheet" type="text/css" href="style.css">

<?php
    include('config.php'); 
	session_start();
	
	$error = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
	  // Escape user inputs for security
      $username_in = mysqli_real_escape_string($connection, $_POST['userName']);
  
      $password_in = mysqli_real_escape_string($connection, $_POST['pass']);
  
		  // attempt insert query execution
      $sql = "SELECT username, password FROM user WHERE username = '$username_in' AND password = '$password_in'";
      $result = mysqli_query($connection, $sql);
	  
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	  
      $active = $row['active'];
      
      $count = mysqli_num_rows($result);
	  
      if($count == 1) {
           
          // session_register("username_in");
           
           $_SESSION['login_user'] = $username_in;
           
           header("Location: welcome.php");
		  
        }else {
			
           echo "Your Login Name or Password is invalid";
		 	echo "<h2><a href = \"index.php\">Try Again</a></h2>";
		   
        }
	} else {
		 
	}
     
  ?>
  </head>