<?php
   include('config.php');
   session_start();
   setlocale(LC_MONETARY, 'en_US');
   
   $current_orderID = $_SESSION['current_Order'];
      
	 
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($connection,"select username, customerID, name from user left outer join account_type using(ATID) where username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];
   
   $login_session_id = $row['customerID'];
   
   $login_session_privlages = $row['name'];


   
	if ($login_session != null) {
	   $nav_bar_and_logo = "
		<img class=\"logo\" src=\"images/Logo.png\" alt=\"Fancy Retailer Logo\" ></img>
		<ul class=\"navBar\">
			<li><a href=\"welcome.php\">Home</a></li>
			<li><a href=\"products.php\">Shop</a></li>
			
			". ($login_session_privlages == 'Admin' ? "<li><a href=\"admin.php\">Admin</a></li>" : "") ."
			
			<li style=\"float:right\"><a href=\"logout.php\" class=\"logoutLink\" >Logout</a></li>
			<li style=\"float:right\"><a href=\"user.php\" class=\"active\" >" . $login_session . "</a></li>
			<li style=\"float:right\"><a href=\"orders.php\">My Orders</a></li>
			
	
		</ul>";
	} else {
		$nav_bar_and_logo = "
		<img class=\"logo\" src=\"images/Logo.png\" alt=\"Fancy Retailer Logo\" ></img>
		<ul class=\"navBar\">
			<li><a href=\"welcome.php\">Home</a></li>
			<li style=\"float:right\"><a href=\"index.php\">Login</a></li>
		</ul>";
	}
	
	
	
	if(!isset($_SESSION['login_user'])){
     // header("location:index.php");
   }
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">

var lastScrollTop = 0;

$(window).scroll(function () {

var st = $(this).scrollTop();
        if (st < 5){
          $('.logo').fadeIn();
		  $('.navBar').css("top","40");
        } else {
          $('.logo').fadeOut();
		  $('.navBar').css("top","0");
        }

  })
</script>