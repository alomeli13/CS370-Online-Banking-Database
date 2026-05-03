<?php
$current_page = 'report3';
include('_header.php');
include('db_config.php');

// Updated Query: Joins Branch to Job_Title, then Job_Title to Employee
$query = "SELECT 
            b.BranchID, b.BranchName, b.City, b.State, b.PhoneNumber, b.ZipCode,
            j.Job_Description, 
            e.EmployeeID, e.Fname, e.Lname, e.Salary, e.Essn
          FROM branch b
          LEFT JOIN job_title j ON b.BranchID = j.BranchID
          LEFT JOIN employee e ON j.Job_TitleID = e.Job_TitleID
          ORDER BY b.BranchName, j.Job_Description ASC, e.Salary DESC";

$result = mysqli_query($conn, $query);
?>

    <div class="container mt-5">
        <h1 class="mb-4">Branch Staff Directory</h1>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
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
            <td colspan='5' class='p-2'>
                <div class='d-flex align-items-center'>
                    <h4 class='m-0 pe-3'>" . htmlspecialchars($row['BranchName']) . "</h4>
                    
                    <div class='ms-auto d-flex align-items-center small'>
                        <div class='px-2'><strong>Location:</strong> " . $row['City'] . ", " . $row['State'] . "</div>
                        <div class='px-2 border-start'><strong>ZipCode:</strong> " . $row['ZipCode'] . "</div>
                        <div class='px-2 border-start'><strong>Phone:</strong> " . $row['PhoneNumber'] . "</div>
                    </div>
                </div>
            </td>
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
                                    <td colspan='4'>
                                        <i class='bi bi-briefcase-fill' style='margin-inline-start: 80px'></i> <strong>" . $display_title . "</strong>
                                    </td>
                                  </tr>";
                            $last_job_name = $current_job_name;
                        }

                        // LEVEL 3: EMPLOYEE ROW

                        echo "<tr>
                                <td style='width: 30px;'></td>
                                <td style='width: 30px;'></td>
                                
                                <td class='text-center' style='width: 45%;'>
                                    <i class='bi bi-person'></i> " . $row['Fname'] . " " . $row['Lname'] . "
                                </td>
                                
                                <td class='text-center text-muted' style='width: 25%;'>
                                    <small>SSN: " . $row['Essn'] . "</small>
                                </td>
                                
                                <td class='text-center text-success' style='width: 25%;'>
                                    <strong>Salary: $" . number_format($row['Salary'], 2) . "</strong>
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