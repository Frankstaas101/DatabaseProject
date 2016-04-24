<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>


<?php
	include('session.php');
	echo $nav_bar_and_logo;
	
	if ($current_orderID != null) {
		
	} else {
		echo "NO ORDER ID FOUND!";
	}
	
?>
    <h1>Order #<?php echo $current_orderID ?> Contains: </h1>
    <div class="form-div">
    <form  action="edit_contains.php" method="post">
        <p>
            
           
            <label for="UPC"> UPC: </label>     
            <input type="text" name="UPC" id="UPC" readonly> 
            
            <label for="quantity">Quantity:</label>
            <input type="text" name="quantity" id="quantity"> 
            
            <input class="loginBtn" type="submit" value="EDIT" >
        </p>
       
    </form>
    </div>
    <h2 class="result"></h2>
            <table>
                <tr>
                    <th>UPC</th>
                    <th>Product</th>
                    <th>Total Price</th>
                    <th>Quantity</th>
                    <th>Edit</th>
                </tr>
            <?php
                
            $query = "SELECT UPC, quantity, Pname, price from contains left outer join product using(UPC) where orderID = " . $current_orderID . ";";
            
            if ($stmt = $connection->prepare($query)) {
                $stmt->execute();
                $stmt->bind_result($UPC, $quantity, $Pname, $price);
                 while ($stmt->fetch()) {
                    echo "<tr>";
                    echo "<td class=\"tooltip\"><span class=\"tooltiptext\">This item is on Sale!</span>". $UPC . "</td>";
                    echo "<td>" . $Pname . "</td>";
                    echo "<td>$" . $price * $quantity . ".00</td>";
                    echo "<td>" . $quantity . "</td>";
                    echo "<td><button class=\"edit-button\" onclick=\"update_fields('". $UPC ."','". $quantity ."' )\"></button>
                              <button class=\"remove-button\" onclick=\"removeFromOrder('". $UPC ."')\"></button></td>";
                                     
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

function update_fields(UPC_in, quantity_in) {
   $("#UPC").val(UPC_in);  
   $("#quantity").val(quantity_in); 
}

function removeFromOrder(UPC_in) {

   $.ajax({
      type: "POST",
      url: 'edit_contains.php',
      data: {UPC : UPC_in, quantity : 0 }, // Set quantity to 0 for deletion
      
	  success: function(data) {	
	  	 if(!data.error) location.reload(true);
      }
	  
    });

}
  
</script>
