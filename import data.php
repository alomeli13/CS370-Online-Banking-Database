<?php
error_reporting(1);
mysqli_report(MYSQLI_REPORT_ERROR);

$import_attempted = false;
$import_succeeded = false;
$import_error_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $import_attempted = true;

    $cpm = mysqli_connect("localhost", "banking_user",
        "banking_user", "bank_db");


    if(mysqli_connect_errno()){
        $connection_error = true;
        $connection_error_message = "Failed to connect to MySQL: " . mysqli_connect_error();

    }
    else{

        try {
            $contents = file_get_contents($_FILES['importFile']['tmp_name']);
            $lines = explode("\n", $contents);

            for($i = 0; $i < count($lines); $i++){
                if(empty(trim($lines[$i]))) continue; // Skip empty lines
                $parsed_csv_line = str_getcsv($lines[$i], ",", "\"", "\\");
                // TODO: do something with the parsed data
                // ex: $parsed_csv_line[0] is the first column
                //      $parsed_csv_line[1] is the second column
                //      etc...

                $import_succeeded = true;
            }

        }
        catch(Exception $e){
            $import_error_message = $e->getMessage()
                    . "at: " . $e->getFile()
                    . "on line " . $e->getLine();
        }
    }

}


?>

<html lang="en">
<head>
    <title>Bank Data Import</title>
</head>
<body>

<h1>Bank Data Import</h1>

<?php
    if( $import_attempted){
        if($import_succeeded){
            ?>

            <h1><span style="color: green;">Import Succeeded!</span></h1>
            <?php
        } else{
            ?>

            <h1><span style="color: red;">Import Failed!</span></h1>
            <?php echo $import_error_message; ?>
            <br/><br/>
        <?php

        }
    }


?>



<form method="post" enctype="multipart/form-data">
    File: <input type="file" name="importFile" />
    <br/><br/>
    <input type="submit" value="Upload Data" />



</form>

</body>
</html>