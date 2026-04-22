<?php

error_reporting(1);
mysqli_report(MYSQLI_REPORT_ERROR);

$connection_error = false;
$connection_error_message = "";

$cpm = mysqli_connect("localhost", "banking_user",
    "banking_user", "bank_db");


if(mysqli_connect_errno()){
    $connection_error = true;
    $connection_error_message = "Failed to connect to MySQL: " . mysqli_connect_error();

}

function output_error($title, $error){
    echo "<span style='color: red;'>\n";
    echo "<h2>" . $title . "</h2>\n";
    echo "<h4>" . $error . "</h4>\n";
    echo "</span>\n";
}

function output_table_open(){
    echo "<tr class='pizzaDataHeaderRow'>\n";
    echo " <td>Name</td>\n";
    echo " <td>Age</td>\n";
    echo " <td>Gender</td>\n";
    echo " </tr>\n";
}

function output_table_close(){
    echo "</table>\n";
}

function output_person_row($name, $age, $gender){
    echo "<tr class='pizzaDataRow'>\n";
    echo " <td>" . $name . "</td>\n";
    echo " <td>" . $age . "</td>\n";
    echo " <td>" . $gender . "</td>\n";
    echo "</tr>\n";
}

function output_person_details_row($pizzas, $pizzerias){
    $pizzas_str = "None";
    $pizzerias_str = "None";
    if(sizeof($pizzas) > 0){
        $pizzas_str = implode(",", $pizzas);
    }
    if(sizeof($pizzerias) > 0){
        $pizzas_str = implode(",", $pizzerias);

    }
    echo "<tr>\n";
    echo "    <td colspan='3' class='pizzaDataDetailsCell'>\n";
    echo "      Pizzas Eaten: " . $pizzas_str . "<br/>\n";
    echo "      Pizzerias Frequented: " . $pizzerias_str . "\n";
    echo "    </to>\n";
    echo "</tr>\n";
}


?>


<html lang="en">
<head>
    <title>Pizza Data Report</title>
</head>
<style>
    .pizzaDataTable{font-family: Consolas, monospace;
                    font-size: larger;
                    border-spacing: 0;}
    .pizzaDataHeaderRow{font-weight: bold;
                        padding-right: 20px;}
    .pizzaDataRow td{border-bottom: 1px solid = 888888;
                    padding-left : 10px;}
    .pizzaDataDetailsCell{padding-left: 20px;
                            font-size: medium;}
    .pizzaDataTable tr:nth-child(2n+2){background-color: #cccccc;}

</style>
<body>


<h1>Pizza Data Report</h1>

<?php
if($connection_error){
    output_error("Database connection failure!", $connection_error_message);
}
else{

$query = " SELECT t0.name, t0.age, t0.gender, t1.pizza, t2.pizzeria"
        ." FROM person t0"
        ." LEFT OUTER JOIN eats t1 ON t0.name = t1.name"
        ." LEFT OUTER JOIN frequents t2 on t0.name = t2.name";

$result = mysqli_query($cpm, $query);
    if(!$result){
        if(mysqli_errno($cpm)){
            output_error("Data retrieval failure!", mysqli_error($cpm));
        }
        else{
            echo "No Pizza Data Found";
        }
    } else{
        output_table_open();

        $last_name = null;
        $pizzas = array();
        $pizzerias = array();
        while($row = $result->fetch_array()){
            if($last_name != $row["name"]){
                if($last_name != null){
                    output_person_details_row($pizzas, $pizzerias);
                }

                    output_person_row($row["name"], $row["age"], $row["gender"]);
                    $pizzas = array();
                    $pizzerias = array();
                }

                if(!in_array($row["pizza"], $pizzas)){
                    $pizzas[] = $row["pizza"];
                }
                if(!in_array($row["pizzeria"], $pizzerias)){
                    $pizzerias[] = $row["pizzeria"];
                }

                $last_name = $row["name"];
            }

            if($last_name != null){
                output_person_details_row($pizzas, $pizzerias);
            }


        output_table_close();
    }
}
?>







</body>
</html>

