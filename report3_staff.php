<?php
$current_page = 'report3';
include('_header.php');
include('db_config.php');

// Updated Query: Joins Branch to Job_Title, then Job_Title to Employee
$query = "SELECT 
            b.BranchID, b.BranchName, b.City, b.State, 
            j.Job_Description, j.Salary, 
            e.EmployeeID, e.Fname, e.Lname, e.Essn
          FROM branch b
          LEFT JOIN job_title j ON b.BranchID = j.BranchID
          LEFT JOIN employee e ON j.Job_TitleID = e.Job_TitleID
          ORDER BY b.BranchName, j.Salary DESC, e.Lname";

$result = mysqli_query($conn, $query);
?>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Branch | Job Title | Employee Report</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                    <?php
                    $last_branch = null;

                    while($row = mysqli_fetch_assoc($result)) {

                        // LEVEL 1: BRANCH HEADER
                        if ($last_branch != $row['BranchID']) {
                            echo "<tr class='table-dark'>
                <td colspan='3'><i class='bi bi-building'></i> <strong>Branch: " . $row['BranchName'] . "</strong></td>
              </tr>";
                            $last_branch = $row['BranchID'];
                            $last_job = null; // Reset job tracking for new branch
                        }

                        // LEVEL 2: JOB TITLE HEADER
                        // This groups all employees who share the same Job Title ID under one header
                        if ($last_job != $row['Job_Description']) {
                            echo "<tr class='table-secondary'>
                <td style='width: 30px;'></td>
                <td colspan='2'>
                    <i class='bi bi-briefcase-fill'></i> <strong>" . $row['Job_Description'] . "</strong>
                    <span class='float-end text-muted small'>Base Salary: $" . number_format($row['Salary'], 2) . "</span>
                </td>
              </tr>";
                            $last_job = $row['Job_Description'];
                        }

                        // LEVEL 3: EMPLOYEE ROW
                        echo "<tr>
            <td style='width: 30px;'></td>
            <td style='width: 30px;'></td>
            <td>
                <i class='bi bi-person'></i> " . $row['Fname'] . " " . $row['Lname'] . " 
                <small class='text-muted ms-2'>(SSN: " . $row['Essn'] . ")</small>
            </td>
          </tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include('_footer.php'); ?>