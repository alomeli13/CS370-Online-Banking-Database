<?php
$current_page = 'report2';
include('_header.php');
include('db_config.php');

// customer

$customer_query = "SELECT CustomerID, Fname, Lname, Address, Email, PhoneNumber, DateOfBirth
                   FROM customer
                   ORDER BY Lname, Fname";

$customer_result = mysqli_query($conn, $customer_query);
?>

    <div class="container mt-4">
        <h2 class="mb-3">Customer Banking Activity</h2>

        <?php while($customer = mysqli_fetch_assoc($customer_result)): ?>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white py-2">
                    <strong>
                        <?php echo $customer['Fname'] . " " . $customer['Lname']; ?>
                    </strong>

                    <small class="d-block text-white-50">
                        Customer ID: <?php echo $customer['CustomerID']; ?> |
                        <?php echo $customer['Email']; ?> |
                        <?php echo $customer['PhoneNumber']; ?> |
                        <?php echo $customer['Address']; ?> |
                        DOB: <?php echo $customer['DateOfBirth']; ?>
                    </small>
                </div>

                <div class="card-body p-2">

                    <?php
                    // account
                    $account_query = "SELECT a.AccountID, a.Balance, DATE(a.DateOpened) AS DateOpened, at.TypeName AS AccountType
                                  FROM account a, account_type at
                                  WHERE a.AccountTypeID = at.AccountTypeID
                                  AND a.CustomerID = " . $customer['CustomerID'] . "
                                  ORDER BY a.AccountID";

                    $account_result = mysqli_query($conn, $account_query);
                    ?>

                    <?php if(mysqli_num_rows($account_result) == 0): ?>
                        <p class="text-muted text-center mb-0">
                            No account found for this customer.
                        </p>
                    <?php endif; ?>

                    <?php while($account = mysqli_fetch_assoc($account_result)): ?>

                        <div class="border rounded mb-2">
                            <div class="bg-light px-2 py-2 border-bottom">
                                <strong>
                                    Account #<?php echo $account['AccountID']; ?>
                                </strong>

                                <span class="badge bg-info text-dark ms-2">
                                <?php echo $account['AccountType']; ?>
                            </span>

                                <span class="badge bg-success ms-2">
                                Current Balance: $<?php echo number_format($account['Balance'], 2); ?>
                            </span>

                                <small class="text-muted float-end">
                                    Opened: <?php echo $account['DateOpened']; ?>
                                </small>
                            </div>

                            <?php
                            // transaction
                            $transaction_query = "SELECT t.TransactionID, t.TypeName, t.Amount, DATE(t.Date) AS Date,
                                                     c.CurrencyCode, c.Symbol
                                              FROM `transaction` t, currency c
                                              WHERE t.CurrencyID = c.CurrencyID
                                              AND t.AccountID = " . $account['AccountID'] . "
                                              ORDER BY t.Date DESC";

                            $transaction_result = mysqli_query($conn, $transaction_query);
                            ?>

                            <?php if(mysqli_num_rows($transaction_result) == 0): ?>
                                <p class="text-muted text-center my-2">
                                    No transaction history for this account.
                                </p>
                            <?php else: ?>

                                <table class="table table-sm table-bordered table-hover mb-0">
                                    <thead class="table-secondary">
                                    <tr>
                                        <th class="text-center">Transaction Type</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Currency</th>
                                        <th class="text-center">Date</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php while($transaction = mysqli_fetch_assoc($transaction_result)): ?>
                                        <tr>
                                            <td class="text-center">
                                            <span class="badge bg-primary">
                                                <?php echo $transaction['TypeName']; ?>
                                            </span>
                                            </td>

                                            <td class="text-center text-success">
                                                <strong>
                                                    <?php echo $transaction['Symbol']; ?><?php echo number_format($transaction['Amount'], 2); ?>
                                                </strong>
                                            </td>

                                            <td class="text-center">
                                                <?php echo $transaction['CurrencyCode']; ?>
                                            </td>

                                            <td class="text-center">
                                                <?php echo $transaction['Date']; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>

                            <?php endif; ?>

                        </div>

                    <?php endwhile; ?>

                </div>
            </div>

        <?php endwhile; ?>

    </div>

<?php include('_footer.php'); ?>