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
            // 1. Insert into BRANCH
            // Updated to include City, State, Zip, and RoutingNumber
            $stmt1 = $conn->prepare("INSERT INTO branch (BranchName, StreetAddress, City, State, ZipCode, PhoneNumber, RoutingNumber) 
                        VALUES (?, ?, ?, ?, ?, ?, ?) 
                        ON DUPLICATE KEY UPDATE StreetAddress = VALUES(StreetAddress)");

            // Updated bind_param: "sssssss" (7 strings)
            // You'll need to make sure your CSV has these extra columns!
            $stmt1->bind_param("sssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);

            if (!$stmt1->execute()) {
                throw new Exception("Branch Error: " . $stmt1->error);
            }

            // If it's a new branch, we get the ID. If it exists, we might need a SELECT or use a Natural Key.
            // For this exercise, we'll assume new records:
            $newBranchID = $conn->insert_id;

            // 2. Insert into EMPLOYEE (using the new BranchID)
            // CSV columns: Fname (3), Lname (4), Position (5), Salary (6), SSN (7)
            $stmt2 = $conn->prepare("INSERT INTO employee (BranchID, Fname, Lname, Position, Salary, SSN) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("isssds", $newBranchID, $data[3], $data[4], $data[5], $data[6], $data[7]);
            $stmt2->execute();
            if (!$stmt2->execute()) {
                // This will force the catch block to trigger if the database rejects a row
                throw new Exception("Database error: " . $stmt2->error);
            }
            $newEmployeeID = $conn->insert_id;

            // 3. Insert into DEPENDENT (using the new EmployeeID)
            // CSV columns: DepFname (8), DepLname (9), Relationship (10), DepDOB (11)
            $stmt3 = $conn->prepare("INSERT INTO dependent (EmployeeID, Fname, Lname, Relationship, DateOfBirth) VALUES (?, ?, ?, ?, ?)");
            $stmt3->bind_param("issss", $newEmployeeID, $data[8], $data[9], $data[10], $data[11]);

            if (!$stmt3->execute()) {
                // This will force the catch block to trigger if the database rejects a row
                throw new Exception("Database error: " . $stmt3->error);
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

<?php $current_page = 'import3'; ?>
<?php include('_header.php'); ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Import Staff</h4>
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
                Select a CSV file to populate the <strong>Branch, Employee, and Dependent</strong> tables.
                Ensure your CSV includes all necessary columns for this 3-tier hierarchy.
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