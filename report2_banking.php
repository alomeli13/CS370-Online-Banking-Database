<?php
$current_page = 'report2';
include('_header.php');

$conn = mysqli_connect("localhost", "banking_user", "banking_user", "bank_db");

$query = "SELECT c.CustomerID, c.Fname, c.Lname, 
                 a.AccountID, a.Balance, a.AccountTypeID,
                 t.TransactionID, t.Amount, t.Date
          FROM customer c
          LEFT JOIN account a ON c.CustomerID = a.CustomerID
          LEFT JOIN transaction t ON a.AccountID = t.AccountID
          ORDER BY c.Lname, c.CustomerID, a.AccountID, t.Date DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h3 class="mb-0">Customer | Account | Transaction Report</h3>
        </div>
        <div class="card-body p-0"> <table class="table table-hover mb-0">
                <tbody>
                <?php
                $last_customer = null;
                $last_account = null;

                while($row = mysqli_fetch_assoc($result)) {
                    // LEVEL 1: NEW CUSTOMER ROW
                    if ($last_customer != $row['CustomerID']) {
                        echo "<tr class='table-primary'>
                                <td colspan='3'><strong>Customer: " . $row['Fname'] . " " . $row['Lname'] . "</strong> (ID: " . $row['CustomerID'] . ")</td>
                              </tr>";
                        $last_customer = $row['CustomerID'];
                        $last_account = null; // Reset account tracking for new customer
                    }

                    // LEVEL 2: NEW ACCOUNT ROW
                    if ($row['AccountID'] && $last_account != $row['AccountID']) {
                        echo "<tr class='table-light'>
                                <td style='width: 50px;'></td>
                                <td colspan='2' class='text-primary'>
                                    <i class='bi bi-bank'></i> <strong>Account #" . $row['AccountID'] . "</strong> 
                                    <span class='badge bg-info text-dark ms-2'>Type: " . $row['AccountTypeID'] . "</span>
                                    <span class='float-end'>Current Balance: $" . number_format($row['Balance'], 2) . "</span>
                                </td>
                              </tr>";

                        // Small sub-header for transactions
                        echo "<tr>
                                <td></td><td></td>
                                <td class='fw-bold text-muted small'>Transaction History</td>
                              </tr>";
                        $last_account = $row['AccountID'];
                    }

                    // LEVEL 3: TRANSACTION DETAIL
                    echo "<tr>
                            <td></td>
                            <td style='width: 50px;'></td>
                            <td class='small'>";
                    if ($row['TransactionID']) {
                        echo "<span class='text-muted'>" . $row['Date'] . "</span> | 
                              <strong>$" . number_format($row['Amount'], 2) . "</strong> 
                              <span class='text-secondary'>(ID: " . $row['TransactionID'] . ")</span>";
                    } else {
                        echo "<span class='text-italic text-muted'>No transaction history for this account.</span>";
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