<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<?php
	include('session.php');
	echo $nav_bar_and_logo;
?>


<h2 class="result"></h2>
    <h1>Products</h1>
        <table>
            <tr>
                <th>UPC</th>
                <th>Product</th>
                <th>Price</th>
                <th>Add to Order</th>
                <th>Average Rating</th>
             </tr>
        <?php	
        $query = "select UPC, Pname, price, avg( rating) as avg_rating from product left outer join rated using(UPC) group by UPC order by avg_rating desc;";
        
        if ($stmt = $connection->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($UPC, $Pname, $price, $avg_rating);
             while ($stmt->fetch()) {
                echo "<tr>";
                echo "<td class=\"tooltip\"><span class=\"tooltiptext\">This item is on Sale!</span>". $UPC . "</td>";
                echo "<td>" . $Pname . "</td>";
                echo "<td>$" . $price . ".00</td>";
                echo "<td><button class=\"add-cart-button\" onclick=\"add('". $Pname ."')\"></button></td>";
				
				if ($avg_rating == 5) 
				{
					echo "<td> <img class =\"stars\" title=\"". $avg_rating ."\"  src=\"images/5Stars.png\" alt=\"5 Stars\"></td>";
					}
				else if ($avg_rating >= 4.0) 
				{
					echo "<td> <img class =\"stars\" title=\"". $avg_rating ."\"  src=\"images/4Stars.png\" alt=\"4 Stars\"></td>";
				}
				else if ($avg_rating >= 3.0) 
				{
					echo "<td> <img class =\"stars\" title=\"". $avg_rating ."\"  src=\"images/3Stars.png\" alt=\"3 Stars\"></td>";
				}
				else if ($avg_rating >= 2.0) 
				{
					echo "<td> <img class =\"stars\" title=\"". $avg_rating ."\"  src=\"images/2Stars.png\" alt=\"2 Stars\"></td>";
				}
				else if ($avg_rating >= 1.0) 
				{
					echo "<td> <img class =\"stars\" title=\"". $avg_rating ."\"  src=\"images/1Stars.png\" alt=\"1 Star\"></td>";
				}
				else if ($avg_rating >= 0 ) 
				{
					echo "<td> <img class =\"stars\" title=\"". $avg_rating ."\"  src=\"images/0Stars.png\" alt=\"No Stars\"></td>";
				}
				
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

function add(name) {
	$.notify("Added " + name, "success", { positoin: "bottom right"});
}

</script>