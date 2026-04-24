<?php
$current_page = 'report3';
include('_header.php');
include('db_config.php');

// We need to join 4 tables now: Branch -> Employee -> Job_Title -> Dependent
$query = "SELECT b.BranchID, b.BranchName, b.City, b.State,
                 e.EmployeeID, e.Fname, e.Lname, 
                 j.Job_Description, j.Salary,
                 d.Fname as DepFname, d.Lname as DepLname
          FROM branch b
          LEFT JOIN employee e ON b.BranchID = e.BranchID
          LEFT JOIN job_title j ON e.Job_TitleID = j.Job_TitleID
          LEFT JOIN dependent d ON e.EmployeeID = d.EmployeeID
          ORDER BY b.BranchName, b.BranchID, e.Lname, e.Fname";

$result = mysqli_query($conn, $query);
?>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Branch | Employee | Dependents Report</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                    <?php
                    $last_branch = null;
                    $last_employee = null;

                    while($row = mysqli_fetch_assoc($result)) {

                        // LEVEL 1: BRANCH HEADER
                        if ($last_branch != $row['BranchID']) {
                            echo "<tr class='table-dark'>
                                <td colspan='3'>
                                    <i class='bi bi-building'></i> <strong>Branch: " . $row['BranchName'] . "</strong> 
                                    <small class='ms-2'>(" . $row['City'] . ", " . $row['State'] . ")</small>
                                </td>
                              </tr>";
                            $last_branch = $row['BranchID'];
                            $last_employee = null; // Reset employee tracking for new branch
                        }

                        // LEVEL 2: EMPLOYEE ROW
                        if ($row['EmployeeID'] && $last_employee != $row['EmployeeID']) {
                            echo "<tr class='table-success'>
            <td style='width: 50px;'></td>
            <td colspan='2'>
                <strong>" . $row['Fname'] . " " . $row['Lname'] . "</strong> 
                <span class='badge bg-secondary ms-2'>" . $row['Job_Description'] . "</span>
                <span class='float-end text-muted small'>Salary: $" . number_format($row['Salary'], 2) . "</span>
            </td>
          </tr>";
                            $last_employee = $row['EmployeeID'];
                        }

                        // LEVEL 3: DEPENDENT DETAIL
                        echo "<tr>
                            <td></td>
                            <td style='width: 50px;'></td>
                            <td class='small text-secondary'>";
                        if ($row['DepFname']) {
                            echo "<strong>Dependent:</strong> " . $row['DepFname'] . " " . $row['DepLname'] . " 
                              <em class='ms-2'>(" . $row['Relationship'] . ")</em>";
                        } else if ($row['EmployeeID']) {
                            echo "<span class='text-muted'>No dependents listed for this employee.</span>";
                        } else {
                            echo "<span class='text-muted'>No employees assigned to this branch.</span>";
                        }
                        echo "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include('_footer.php'); ?>