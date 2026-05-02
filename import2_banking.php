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

        // Skip the header row
        fgetcsv($handle);

        while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            /*check for customer (natural key: Email)*/
            $checkCustomer = $conn->prepare("
                SELECT CustomerID
                FROM Customer
                WHERE Email = ?
            ");
            if (!$checkCustomer) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $checkCustomer->bind_param("s", $data[4]);
            $checkCustomer->execute();
            $checkCustomer->bind_result($newCustomerID);

            /*if customer exists -> update fields */
            if($checkCustomer->fetch()) {
                $checkCustomer->close();

                $updateCustomer = $conn->prepare("
                    UPDATE Customer
                    SET Fname = ?,
                        Lname = ?,
                        Address = ?,
                        PhoneNumber = ?,
                        Ssn = ?,
                        DateOfBirth = ?
                    WHERE CustomerID = ?
                ");

                if (!$updateCustomer) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }

                $updateCustomer->bind_param("ssssssi", $data[0], $data[1], $data[2], $data[3], $data[5], $data[6], $newCustomerID);

                if(!$updateCustomer->execute()) {
                    throw new Exception("Database error: " . $updateCustomer->error);
                }
            }
            /*or insert new customer*/
            else {
                $checkCustomer->close();

                $stmt1 = $conn->prepare("
                    INSERT INTO Customer
                    (Fname, Lname, Address, PhoneNumber, Email, Ssn, DateOfBirth)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                if (!$stmt1) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }

                $stmt1->bind_param("sssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);

                if(!$stmt1->execute()) {
                    throw new Exception("Database error: " . $stmt1->error);
                }

                $newCustomerID = $conn->insert_id;
            }


            /*get account type (import 1)*/
            $getAccountType = $conn->prepare("
                SELECT AccountTypeID
                FROM Account_Type
                WHERE TypeName = ?
            ");
            if (!$getAccountType) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $getAccountType->bind_param("s", $data[7]);
            $getAccountType->execute();
            $getAccountType->bind_result($accountTypeID);

            if(!$getAccountType->fetch()) {
                throw new Exception("Run Import 1 first. Missing account type: " . $data[7]);
            }

            $getAccountType->close();


            /* check for account (CustomerID + AccountTypeID natural key)*/
            $checkAccount = $conn->prepare("
                SELECT AccountID
                FROM Account
                WHERE CustomerID = ?
                AND AccountTypeID = ?
            ");
            if (!$checkAccount) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $checkAccount->bind_param("ii", $newCustomerID, $accountTypeID);
            $checkAccount->execute();
            $checkAccount->bind_result($newAccountID);

            /* if account exists -> update fields*/
            if($checkAccount->fetch()) {
                $checkAccount->close();

                $updateAccount = $conn->prepare("
                    UPDATE Account
                    SET Balance = ?,
                        DateOpened = ?
                    WHERE AccountID = ?
                ");
                if (!$updateAccount) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }

                $updateAccount->bind_param("dsi", $data[8], $data[9], $newAccountID);

                if(!$updateAccount->execute()) {
                    throw new Exception("Database error: " . $updateAccount->error);
                }
            }
            /*or insert new account*/
            else {
                $checkAccount->close();

                $stmt2 = $conn->prepare("
                    INSERT INTO Account
                    (CustomerID, AccountTypeID, Balance, DateOpened)
                    VALUES (?, ?, ?, ?)
                ");
                if (!$stmt2) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }

                $stmt2->bind_param("iids", $newCustomerID, $accountTypeID, $data[8], $data[9]);

                if(!$stmt2->execute()) {
                    throw new Exception("Database error: " . $stmt2->error);
                }

                $newAccountID = $conn->insert_id;
            }


            /*get currency (import 1)*/
            $getCurrency = $conn->prepare("
                SELECT CurrencyID
                FROM Currency
                WHERE CurrencyCode = ?
            ");
            if (!$getCurrency) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $getCurrency->bind_param("s", $data[11]);
            $getCurrency->execute();
            $getCurrency->bind_result($currencyID);

            if(!$getCurrency->fetch()) {
                throw new Exception("Run Import 1 first. Missing currency: " . $data[11]);
            }

            $getCurrency->close();

            /*get the TransactionTypeID*/
            $getTransType = $conn->prepare("SELECT TransactionTypeID FROM transaction_type WHERE TypeName = ?");
            if (!$getTransType) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $getTransType->bind_param("s", $data[10]); //TypeName
            $getTransType->execute();
            $getTransType->bind_result($transactionTypeID);
            $getTransType->fetch();
            $getTransType->close();

            /*check for transaction*/
            $checkTransaction = $conn->prepare("
                SELECT TransactionID
                FROM `Transaction`
                WHERE AccountID = ?
                AND TransactionTypeID = ?
                AND Amount = ?
                AND Date = ?
            ");

            if(!$checkTransaction) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $checkTransaction->bind_param("iids", $newAccountID, $transactionTypeID, $data[12], $data[13]);

            $checkTransaction->execute();
            $checkTransaction->bind_result($transactionID);

            /* if transaction exists -> update fields*/
            if($checkTransaction->fetch()) {
                $checkTransaction->close();

                $updateTransaction = $conn->prepare("
                    UPDATE `Transaction`
                    SET CurrencyID = ?
                    WHERE TransactionID = ?
                ");

                if(!$updateTransaction) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }

                $updateTransaction->bind_param("ii", $currencyID, $transactionID);

                if(!$updateTransaction->execute()) {
                    throw new Exception("Database error: " . $updateTransaction->error);
                }
            }
            /*or insert new transaction*/
            else {
                $checkTransaction->close();

                $stmt3 = $conn->prepare("
                    INSERT INTO `Transaction`
                    (AccountID, CurrencyID, TransactionTypeID, Amount, Date)
                    VALUES (?, ?, ?, ?, ?)
                ");

                if(!$stmt3) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }

                $stmt3->bind_param("iiids", $newAccountID, $currencyID, $transactionTypeID, $data[12], $data[13]);

                if(!$stmt3->execute()) {
                    throw new Exception("Database error: " . $stmt3->error);
                }
            }

            $rows_inserted++;
        }

        fclose($handle);
        $import_succeeded = true;

    } catch(Exception $e) {
        $import_succeeded = false;
        $import_error_message = $e->getMessage();
    }
}
?>

<?php $current_page = 'import2'; ?>
<?php include('_header.php'); ?>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Import Banking</h4>
            </div>

            <div class="card-body">

                <?php if($import_attempted): ?>
                    <?php if($import_succeeded): ?>
                        <div class="alert alert-success">
                            <strong>Success!</strong>
                            Imported or updated <?php echo $rows_inserted; ?> records into the Customer, Account, & Transaction tables.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <strong>Error:</strong>
                            <?php echo $import_error_message; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <p class="text-muted">
                    Select a CSV file to populate the
                    <strong>Customer, Account, and Transaction</strong> tables.
                    Run Import 1 first so Account Types and Currency already exist.
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