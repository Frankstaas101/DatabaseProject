<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="edit_order.php" />

<?php
	include('session.php');
	echo $nav_bar_and_logo;
?>

<h2 class="result"></h2>
    <h1>My Orders</h1>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Ship Date</th>
                <th>Payment Type</th>
                <th>CCN</th>
                <th>Edit Order</th>
             </tr>
        <?php	
        $query = "select orderID, orderdate, shipdate, payment_type, CCN from orders where customerID in (select customerID from user where username = '$login_session')";
        
        if ($stmt = $connection->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($orderID, $orderdate, $shipdate, $payment_type, $CCN );
             while ($stmt->fetch()) {
                echo "<tr>";
                echo "<td>". $orderID . "</td>";
                echo "<td>" . $orderdate . "</td>";
                echo "<td>" . $shipdate . "</td>";
				echo "<td>" . $payment_type . "</td>";
				echo "<td>" . $CCN . "</td>"; // MAKE A BUTTON TO GO TO THE ORDER TO SEE SPECIFICS "CONTAINS"
           		echo "<td><a href=\"edit_order.php\"><button  class=\"edit-button\" onclick=\"edit_order('". $orderID ."')\"></button></a></td>"; //
                echo "</tr>";

            }
            $stmt->close();
        }   
        ?>
   </table>
</head>



<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="notify.js"></script>
<script type="text/javascript">

function edit_order(orderID_in) {
   
   $.ajax({
      type: "POST",
      url: 'set_orderID.php',
      data: {orderID : orderID_in },
      
	  success: function(data) {	

      }
	  
    });
    
}

</script>