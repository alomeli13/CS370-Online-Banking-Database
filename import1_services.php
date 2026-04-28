<?php
include('db_config.php');
error_reporting(1);
mysqli_report(MYSQLI_REPORT_ERROR);

$import_attempted = false;
$import_succeeded = false;
$rows_inserted = 0;
$import_error_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['importFile'])) {
    $import_attempted = true;

    try {
        $file = $_FILES['importFile']['tmp_name'];
        $handle = fopen($file, "r");

        // Skip the header row of the CSV
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $rowType = $data[0];

            switch ($rowType) {
                case 'CUR':
                    $stmt = $conn->prepare("INSERT INTO currency (CurrencyCode, CurrencyName, ExchangeRateToUSD, Symbol) 
                            VALUES (?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE ExchangeRateToUSD = VALUES(ExchangeRateToUSD)");
                    $stmt->bind_param("ssds", $data[1], $data[2], $data[3], $data[4]);

                    if (!$stmt->execute()) {
                        // This will force the catch block to trigger if the database rejects a row
                        throw new Exception("Database error: " . $stmt->error);
                    }
                    $rows_inserted++; // Update counter
                    break;

                case 'ATM':
                    $stmt = $conn->prepare("INSERT INTO atm (StreetAddress, City, State, ZipCode, CurrentCash) 
                            VALUES (?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE CurrentCash = VALUES(CurrentCash)");
                    $stmt->bind_param("ssssd", $data[1], $data[2], $data[3], $data[4], $data[5]);

                    if (!$stmt->execute()) {
                        // This will force the catch block to trigger if the database rejects a row
                        throw new Exception("Database error: " . $stmt->error);
                    }
                    $rows_inserted++; // Update counter
                    break;

                case 'TYP':
                    $stmt = $conn->prepare("INSERT INTO account_type (TypeName, InterestRate, MonthlyFee) 
                            VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE InterestRate = VALUES(InterestRate)");
                    $stmt->bind_param("sdd", $data[1], $data[2], $data[3]);

                    if (!$stmt->execute()) {
                        // This will force the catch block to trigger if the database rejects a row
                        throw new Exception("Database error: " . $stmt->error);
                    }
                    $rows_inserted++; // Update counter
                    break;
            }
        }
        fclose($handle);
        $import_succeeded = true;
    } catch(Exception $e) {
        $import_succeeded = false;
        $import_error_message = $e->getMessage();
    }

}
?>

<?php $current_page = 'import1'; ?>
<?php include('_header.php'); ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Import Services</h4>
        </div>
        <div class="card-body">

            <?php if($import_attempted): ?>
                <?php if($import_succeeded): ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong> Imported <?php echo $rows_inserted; ?> records into the ATM, Currency, & Account_Type tables.
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?php echo $import_error_message; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <p class="text-muted">
                Select a CSV file to populate the <strong>ATM, Currency, and Account Type </strong>tables.
                Use prefixes CUR, ATM, or TYP.
            </p>

            <form method="post" enctype="multipart/form-data" class="mt-4">
                <div class="mb-3">
                    <label for="importFile" class="form-label">Choose CSV File</label>
                    <input type="file" name="importFile" id="importFile" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Execute Upload</button>
                <a href="index.php" class="btn btn-link text-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include('_footer.php'); ?>