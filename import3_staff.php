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
            // 1. BRANCH: Look it up by name first
            $stmtCheck = $conn->prepare("SELECT BranchID FROM branch WHERE BranchName = ?");
            if (!$stmtCheck) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmtCheck->bind_param("s", $data[0]);
            $stmtCheck->execute();
            $res = $stmtCheck->get_result();

            if ($row = $res->fetch_assoc()) {
                $branchID = $row['BranchID']; // Use the existing ID
            } else {
                // Insert new branch if it doesn't exist
                $stmt1 = $conn->prepare("INSERT INTO branch (BranchName, StreetAddress, City, State, ZipCode, PhoneNumber, RoutingNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt1) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                $stmt1->bind_param("sssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
                $stmt1->execute();
                $branchID = $conn->insert_id;
            }

            // 2. JOB TITLE: Link to the branch we found/created
            $stmt2 = $conn->prepare("INSERT INTO Job_Title (BranchID, Job_Description)
                                            VALUES (?, ?)
                                            ON DUPLICATE KEY UPDATE Job_Description = VALUES(Job_Description)");
            if (!$stmt2) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt2->bind_param("is", $branchID, $data[8]);
            $stmt2->execute();
            // Get the ID for the Job Title (required for the Employee link)
            $stmtGetJob = $conn->prepare("SELECT Job_TitleID FROM Job_Title WHERE BranchID = ? AND Job_Description = ?");
            $stmtGetJob->bind_param("is", $branchID, $data[8]);
            $stmtGetJob->execute();
            $jobTitleID = $stmtGetJob->get_result()->fetch_assoc()['Job_TitleID'];

            // 3. EMPLOYEE: Now everyone will correctly point to the same $branchID
            $stmt3 = $conn->prepare("INSERT IGNORE INTO employee (BranchID, Job_TitleID, Fname, Lname, Salary, Essn)
                                            VALUES (?, ?, ?, ?, ?, ?)
                                            ON DUPLICATE KEY UPDATE 
                                            BranchID = VALUES(BranchID), 
                                            Job_TitleID = VALUES(Job_TitleID), 
                                            Fname = VALUES(Fname), 
                                            Lname = VALUES(Lname),
                                            Salary = VALUES(Salary)");
            if (!$stmt3) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            // Mapping: BranchID, JobTitleID, Fname(11), Lname(12), Salary(13), Essn(14)
            $stmt3->bind_param("iisssd", $branchID, $jobTitleID, $data[11], $data[12], $data[13], $data[14]);
            $stmt3->execute();

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
                        <strong>Success!</strong> Imported <?php echo $rows_inserted; ?> records into the Branch, Job_Title, & Employee tables.
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?php echo $import_error_message; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <p class="text-muted">
                Select a CSV file to populate the <strong>Branch, Job_Title, and Employee</strong> tables.
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