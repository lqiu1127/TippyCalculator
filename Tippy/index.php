<!DOCTYPE HTML>  
<html>
	<head>
		<title> Tippy </title>
		<meta charset="utf-8" />
	</head>
	<body>
		<h2>Tippy the Tip Calculator</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  
		  Bill Subtotal: $<input type="text" name="subtotal" value="<?php echo $subtotal;?>">
		  <span class="error"><?php echo $subtotalError;?></span>
		  <br><br>
		  Tip Percent:
		  <br><br>
		  <?php
			//if the tip percent is not currently empty
			if(!empty($_POST["tipPercent"])) $prevTip = $_POST["tipPercent"]*100;
		    	else $prevTip = 15; //set default value to be 15%
		    	$tips = [10, 15, 20]; //array of tip values
			//for each value of the array, output the coorsponding redio button
		    	foreach($tips as &$tipPercent)
				printf("<input type=\"radio\" name=\"tipPercent\" value=\"%0.2f\" %s /> %d%% \t", 
			    	$tipPercent/100,(intval($prevTip)==intval($tipPercent)) ? "checked" : "", $tipPercent);
		  ?>
		  <br><br>
		  <input type="submit" name="submit" value="Submit">  
		</form>
	</body>
</html>
