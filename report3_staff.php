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
          ORDER BY b.BranchName, j.Job_Description ASC, j.Salary DESC";

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
                    $last_job_name = null;

                    // Convert result to an array so we can easily count groups for pluralization
                    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    foreach ($data as $index => $row) {
                        $current_job_name = trim($row['Job_Description']);

                        // LEVEL 1: BRANCH HEADER
                        if ($last_branch != $row['BranchID']) {
                            echo "<tr class='table-dark'>
                <td colspan='3'><i class='bi bi-building'></i> <strong>" . $row['BranchName'] . "</strong></td>
              </tr>";
                            $last_branch = $row['BranchID'];
                            $last_job_name = null;
                        }

                        // LEVEL 2: JOB TITLE HEADER
                        if ($last_job_name != $current_job_name) {
                            // Count how many people in THIS branch have THIS job title
                            $count = 0;
                            foreach ($data as $item) {
                                if ($item['BranchID'] == $row['BranchID'] && trim($item['Job_Description']) == $current_job_name) {
                                    $count++;
                                }
                            }

                            // Only pluralize if there's more than 1 person and it doesn't already end in 's'
                            $display_title = $current_job_name;
                            if ($count > 1 && substr($current_job_name, -1) !== 's') {
                                $display_title .= "s";
                            }

                            echo "<tr class='table-secondary'>
                <td style='width: 30px;'></td>
                <td colspan='2'>
                    <i class='bi bi-briefcase-fill'></i> <strong>" . $display_title . "</strong>
                </td>
              </tr>";
                            $last_job_name = $current_job_name;
                        }

                        // LEVEL 3: EMPLOYEE ROW
                        echo "<tr>
            <td style='width: 30px;'></td>
            <td style='width: 30px;'></td>
            <td>
                <i class='bi bi-person'></i> " . $row['Fname'] . " " . $row['Lname'] . " 
                <small class='text-muted ms-2'>(SSN: " . $row['Essn'] . ")</small>
                <span class='float-end text-success small'>$" . number_format($row['Salary'], 2) . "</span>
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