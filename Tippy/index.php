<!DOCTYPE HTML> 
<!--
    - Tip Calculator - Spring 2017 CodePath Web Security Pre-work
    by Tianhao Qiu @UCSD
    Started: December 11th, 2016 
--> 
<html>
    <head>
        <title> Tippy </title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="mystyle.css">
    </head>
    <body style="background-color: yellow">  
        <div id="main">
            <?php
            // define variables and set to empty values
            $subtotalError = $tipError = $splitError = "";
            $split = 1;
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
                    if (!preg_match("(^[0-9]*\.?[0-9]+$)", $subtotal)) {
                        $subtotalError = "Invalid Subtotal";
                        $subtotal = 0;
                        $valid = false;
                    } else {
                        //else round the subtotal to two decimal places
                        $subtotal = round(trim_input($_POST["subtotal"]), 2);
                    }
                }

                if (empty($_POST["tipPercent"])) {
                    $tipError = "Tip Percentage Required";
                    $valid = false;
                } elseif (!strcmp($_POST["tipPercent"], "custom")) {
                    $tipPercentage = trim_input($_POST["custom"]);

                    if (!preg_match("(^[0-9]*\.?[0-9]+$)", $tipPercentage)) {
                        $tipError = "Invalid Tip Percentage";
                        $tipPercentage = 0;
                        $valid = false;
                    } elseif (!$tipPercentage) {
                        $tipError = "Please enter a tip percentage greater than 0.";
                        $tipPercentage = 0;
                        $valid = false;
                    } else {
                        //else round the subtotal to two decimal places
                        $tipPercentage = round(trim_input($_POST["custom"] / 100), 4);
                    }
                } elseif (!strcmp($_POST["tipPercent"], "random")) {
                    //else randomly generate a tip preentage between 20 and 50
                    $tipPercentage = round(rand(2000, 5000) / 10000, 4);
                } else {
                    //else round the subtotal to two decimal places
                    $tipPercentage = round(floatval($_POST["tipPercent"]), 2);
                }

                //check if split persons exist, if it exist, check if its valid
                if (empty($_POST["split"])) {
                    $splitError = "Split number is required";
                    $valid = false;
                } else {
                    $split = trim_input($_POST["split"]);
                    //check if the input is a number
                    if (!preg_match("(^[0-9]+$)", $split)) {
                        $splitError = "Invalid split";
                        $split = 1;
                        $valid = false;
                    } else {
                        //else round the subtotal to two decimal places
                        $split = trim_input($_POST["split"]);
                    }
                }
            }

            //used to trim the inputs before checking equality
            function trim_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            ?>

            <h2 id="title">Tippy the Tip Calculator</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  

                Bill Subtotal: $<input type="text" name="subtotal" value="<?php echo $subtotal; ?>">
                <br>
                <span class="error"><?php echo $subtotalError; ?></span>
                <br><br>
                Tip Percent:
                <br><br>
                <?php
                //if the tip percent is not currently empty, get the current percentage
                if (!empty($_POST["tipPercent"]))
                    $prevTip = $_POST["tipPercent"];
                else
                    $prevTip = 0.15; //set default value to be 15%
                $tips = [10, 15, 20]; //array of tip values
                //for each value of the array, output the coorsponding redio button
                foreach ($tips as &$tipPercent) {
                    printf("<input type=\"radio\" name=\"tipPercent\" value=\"%0.2f\" %s /> %d%% \t", $tipPercent / 100, (intval($prevTip * 100) == intval($tipPercent)) ? "checked" : "", $tipPercent);
                }
                print "<br>";
                printf("<input type=\"radio\" name=\"tipPercent\" value=\"custom\" %s /> Custom\t", (!strcmp($prevTip, "custom")) ? "checked" : "");

                //if custom is selected, keep track of the value
                if (!strcmp($prevTip, "custom")) {
                    $value = ($tipPercentage) ? doubleval($_POST['custom']) : 0;
                } else {
                    $value = "";
                }

                printf("<input type=\"text\" name=\"custom\" value=\"%s\" />%%", $value);
                print "<br>";
                //generate random value
                printf("<input type=\"radio\" name=\"tipPercent\" value=\"random\" %s /> I am feeling generous", (!strcmp($prevTip, "random")) ? "checked" : "");
                print "<br>";
                ?>
                <span class="error"><?php echo $tipError; ?></span>
                <br><br>
                Split: <input type="text" name="split" value="<?php echo $split; ?>"> person(s)
                <br>
                <span class="error"><?php echo $splitError; ?></span> 
                <br><br>
                <div align="center">
                    <input type="reset" class="button" value="Reset" onclick="reload_page()"/>
                    <input type="submit" class="button" name="submit" value="Submit">  
                </div>   
            </form> 

            <!-- use javascript to reset the page -->
            <script>
                function reload_page() {
                    window.location = "";
                }
            </script>

            <?php
            if ($valid && !empty($_POST["subtotal"])) {
                echo "<h3>Tippy says:</h3>";
                echo "<div id=\"result\">";
                echo "Tip: $";
                $tip = $subtotal * $tipPercentage;
                echo number_format($tip, 2);
                echo "<br><br>";
                echo "Total: $";
                $total = $subtotal + $tip;
                echo number_format($total, 2);
                echo "<br><br>";
                //if there is more than 1 person splitting
                if ($split > 1) {
                    echo "Tip each: $";
                    $personTip = $tip / $split;
                    echo number_format($personTip, 2);
                    echo "<br><br>";
                    echo "Total each: $";
                    $personTotal = $total / $split;
                    echo number_format($personTotal, 2);
                    echo "<br><br>";
                }
                echo "</div>";
            }
            ?>

        </div>
    </body>
</html>
