<!DOCTYPE HTML>  
<html>
<head>
	<title> Tippy </title>
	<meta charset="utf-8" />
<style>
.error {color: #FF0000;} 
</style>
</head>
<body>  

<?php
// define variables and set to empty values
$subtotalError = $tipError = "";
$persons = 1;
$valid = true;
$subtotal = $tipPercentage = $tip = $total = $personTip = $personTotal = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  //check if subtotal exist, if it exist, check if its valid
  if (empty($_POST["subtotal"])) {
    $subtotalError = "Subtotal is required";
	$valid = false;
  } else {
	 $subtotal = trim_input($_POST["subtotal"]);
	//check if the input is a number
	if (!preg_match("([0-9]*\.?[0-9]+)", $subtotal)) {
      $subtotalError = "Invalid Subtotal";
	  $subtotal = 0;
	  $valid = false;
    } else {
		//else round the subtotal to two decimal places
		$subtotal = round(trim_input($_POST["subtotal"]), 2);
	}
  }
  
  if(empty($_POST["tipPercent"])) {
	$tipError = "Tip Percentage Required";
	$valid = false;
  } elseif (!strcmp($_POST["tipPercent"], "custom")){
	$tipPercentage = trim_input($_POST["custom"]); 
	if (!preg_match("([0-9]*\.?[0-9]+)", $tipPercentage)) {
      $tipError = "Invalid Subtotal";
	  $tipPercentage = 0;
	  $valid = false;
    } else {
		//else round the subtotal to two decimal places
		$tipPercentage = round(trim_input($_POST["custom"]/100), 4);
	}
  } else {
	//else round the subtotal to two decimal places
	$tipPercentage = round(floatval($_POST["tipPercent"]), 2);
  }
}

function trim_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>Tippy the Tip Calculator</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  
  Bill Subtotal: $<input type="text" name="subtotal" value="<?php echo $subtotal;?>">
  <span class="error"><?php echo $subtotalError;?></span>
  <br><br>
  Tip Percent:
  <br><br>
  <?php
	//if the tip percent is not currently empty, get the current percentage
	if(!empty($_POST["tipPercent"])) $prevTip = $_POST["tipPercent"];
    else $prevTip = 0.15; //set default value to be 15%
    $tips = [10, 15, 20]; //array of tip values
	//for each value of the array, output the coorsponding redio button
    foreach($tips as &$tipPercent){
        printf("<input type=\"radio\" name=\"tipPercent\" value=\"%0.2f\" %s /> %d%% \t", 
            $tipPercent/100,(intval($prevTip*100)==intval($tipPercent)) ? "checked" : "", $tipPercent);
	}
	print "<br>";
	printf("<input type=\"radio\" name=\"tipPercent\" value=\"custom\" %s /> Custom" , 
		(!strcmp($prevTip,"custom")) ? "checked" : "");
	
	//if custom is selected, keep track of the value
	if (!strcmp($prevTip, "custom")){
		$value = doubleval($_POST['custom']);
	} else {
		$value = "";
	}

	printf("<input type=\"text\" name=\"custom\" value=\"%s\" />%%", $value);
	print "<br>";
	
	?>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
<?php
if ($valid && !empty($_POST["subtotal"])){
	echo "<h3>Tippy says:</h3>";
	print "Tip: $";
	$tip = $subtotal * $tipPercentage;
	echo number_format($tip, 2);
	echo "<br>";
	print "Total: $";
	$total = $subtotal + $tip;
	echo number_format($total, 2);
	echo "<br>";
}
?>

</body>
</html>
