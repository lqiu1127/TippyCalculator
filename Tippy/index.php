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
<?php
if ($valid){
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
