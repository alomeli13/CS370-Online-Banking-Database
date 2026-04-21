<?php
error_reporting(1);
mysqli_report(MYSQLI_REPORT_ERROR);

$import_attempted = false;
$import_succeeded = false;
$rows_inserted = 0;
$import_error_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['importFile'])) {
    $import_attempted = true;
    // Using your specific database credentials
    $conn = mysqli_connect("localhost", "banking_user", "banking_user", "bank_db");

    if(mysqli_connect_errno()) {
        $import_error_message = "Connection Failed: " . mysqli_connect_error();
    } else {
        try {
            $file = $_FILES['importFile']['tmp_name'];
            $handle = fopen($file, "r");

            // Skip the header row of the CSV
            fgetcsv($handle);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // inside the while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) loop:

                $rowType = $data[0]; // CUR, ATM, or TYP

                switch ($rowType) {
                    case 'CUR':
                        // Format: CUR, USD, Dollar, 1.00, $
                        $stmt = $conn->prepare("INSERT INTO currency (CurrencyCode, CurrencyName, ExchangeRateToUSD, Symbol) 
                                VALUES (?, ?, ?, ?) 
                                ON DUPLICATE KEY UPDATE ExchangeRateToUSD = VALUES(ExchangeRateToUSD)");
                        $stmt->bind_param("ssds", $data[1], $data[2], $data[3], $data[4]);
                        $stmt->execute();
                        break;

                    case 'ATM':
                        // Format: ATM, 123 Main St., Peoria, IL, 61615, 5000.00
                        $stmt = $conn->prepare("INSERT INTO atm (StreetAddress, City, State, ZipCode, CurrentCash) 
                                VALUES (?, ?, ?, ?, ?) 
                                ON DUPLICATE KEY UPDATE CurrentCash = VALUES(CurrentCash)");
                        $stmt->bind_param("ssssd", $data[1], $data[2], $data[3], $data[4], $data[5]);
                        $stmt->execute();
                        break;

                    case 'TYP':
                        // Format: TYP, Checking, 0.01, 5.00
                        // Based on your Account_Type table in the ERD
                        $stmt = $conn->prepare("INSERT INTO account_type (TypeName, InterestRate, MonthlyFee) 
                                VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE InterestRate = VALUES(InterestRate)");
                        $stmt->bind_param("sdd", $data[1], $data[2], $data[3]);
                        $stmt->execute();
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
                        <strong>Success!</strong> Imported <?php echo $rows_inserted; ?> records into the Customer table.
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