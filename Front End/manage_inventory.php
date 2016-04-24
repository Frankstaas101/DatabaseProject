<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<?php
	include('session.php');
	echo $nav_bar_and_logo;
	
?>

<h1>Inventory</h1>
<table class = "edit-table">
  
    <th>NEW</th>
    <th>EDIT</th>
  <tr class="edit">
    <td><form  action="insert_inventory.php" method="post">
        <label for="UPC"> UPC: </label>
        <input type="text" name="UPC" id="UPC" >
        <label for="product">Product Name:</label>
        <input type="text" name="product" id="product">
        <label for="price">Price:</label>
        <input type="text" name="price" id="price">
        <label for="ammount"># On Hand:</label>
        <input type="text" name="ammount" id="ammount">
        <label for="supplier">Supplier:</label>
        <select name="supplier">
          <?php            
                $query = "SELECT Sname FROM supplier;";
                
                if ($stmt = $connection->prepare($query)) {
                    $stmt->execute();
                    $stmt->bind_result($Sname);
                     while ($stmt->fetch()) {
                        echo "<option >$Sname</option>";
                    }
                    $stmt->close();
                }   
                ?>
        </select>
        <label for="rolevel">RO-LEVEL:</label>
        <input type="text" name="rolevel" id="rolevel">
        <input class="addInventory" type="submit" value="ADD" >
      </form>
    </td>
      
    <td><form  action="update_inventory.php" method="post">
        <label for="UPCedit"> UPC: </label>
        <input type="text" name="UPCedit" id="UPCedit" readonly>
        <label for="ammountEdit"># On Hand:</label>
        <input type="text" name="ammountEdit" id="ammountEdit">
        <input class="editInventory" type="submit" value="EDIT" >
      </form>
    </td>
  </tr >
</table>
</div>
<h2 class="result"></h2>
<table>
  <tr>
    <th>UPC</th>
    <th>Product</th> 
    <th>Supplier</th>
    <th>Price Per Unit</th>
    <th>On Hand</th>
    <th>Stock Total</th>
    <th>RO-Level</th>
    <th>Edit</th>
  </tr>
  <?php                 
	$query = "SELECT UPC, Pname, price, Sname, ammount, reorderlevel FROM product;";
	
	if ($stmt = $connection->prepare($query)) {
		$stmt->execute();
		$stmt->bind_result($UPC, $Pname, $price, $Sname, $ammount, $reorderlevel);
		 while ($stmt->fetch()) {
			
			if ($ammount <= $reorderlevel){
				echo "<tr class='rolevel-low'>";
				echo "<td class=\"tooltip-low\"><span class=\"tooltiptext-low\">Low Stock</span>". $UPC . "!</td>";
			} else {
				echo "<tr>";
				echo "<td>". $UPC . "</td>";
			}
			echo "<td>" . $Pname . "</td>";
			echo "<td>" . $Sname . "</td>";
			echo "<td>" . money_format('%(#10n',  $price ) . "</td>";
			echo "<td>" . $ammount . "</td>";
			echo "<td>" . money_format('%(#10n',  $ammount * $price) . "</td>";
			echo "<td>" . $reorderlevel . "</td>";
			echo "<td><button class=\"edit-button\" onclick=\"update_fields('". $UPC ."','". $ammount ."' )\"></button>
					  <button class=\"remove-button\" onclick=\"removeFromInventory('". $UPC ."')\"></button></td>";
							 
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

function update_fields(UPC_in, ammount_in) {
   $("#UPCedit").val(UPC_in);  
   $("#ammountEdit").val(ammount_in); 
}

function removeFromInventory(UPC_in) {

   $.ajax({
      type: "POST",
      url: 'update_inventory.php',
      data: {UPCedit : UPC_in, ammountEdit : -1 }, // Set quantity to 0 for deletion
      
	  success: function(data) {	
	  	 if(!data.error) location.reload(true);
      }
	  
    });

}
  
</script> 
