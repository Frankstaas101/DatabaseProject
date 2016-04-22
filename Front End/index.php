<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<?php
    // Load configuration as an array. Use the actual location of your configuration file
    $config = parse_ini_file('../config.ini'); 

    // Try and connect to the database
    $connection = mysqli_connect($host['host'],$config['username'],$config['password'],$config['dbname'], $config['port']);

    // If connection was not successful, handle the error
    if($connection === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
		echo "Could not establish MySQL connection: " + mysqli_connect_error();
    } else {
		echo "<img src=\"images/accept-database.png\" alt=\"Database Connected Successfully\" style=\"width:25px;height:25px;\">";
	}
?>
<img class="logo" src="images/Logo.png" alt="Fancy Retailer Logo" ></img>
<h2 class="result"></h2>
    <h1>Products</h1>
        <table>
            <tr>
                <th>UPC</th>
                <th>Product</th>
                <th>Price</th>
                <th>Add to Order</th>
             </tr>
        <?php	
        $query = "select UPC, Pname, price from product;";
        
        if ($stmt = $connection->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($UPC, $Pname, $price);
             while ($stmt->fetch()) {
                echo "<tr>";
                echo "<td class=\"tooltip\"><span class=\"tooltiptext\">This item is on Sale!</span>". $UPC . "</td>";
                echo "<td>" . $Pname . "</td>";
                echo "<td>$" . $price . "</td>";
                echo "<td><button class=\"add-cart-button\" onclick=\"add('". $Pname ."', '". $UPC ."')\"></button></td>";
                echo "</tr>";
                //printf("%s, %s, %s, %s, %s, %s, %s, %s\n", $building_id, $building_name, $street, $number, $country, $state, $town, $zip);
            }
            $stmt->close();
        }                        
        ?>
   </table>
</head>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="notify.js"></script>
<script type="text/javascript">
</script>