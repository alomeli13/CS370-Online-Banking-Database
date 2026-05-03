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

    <div class="container mt-5">
        <h1 class="mb-4">Customer Banking Activity</h1>

        <?php while($customer = mysqli_fetch_assoc($customer_result)): ?>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white p-2">
                    <div class="d-flex align-items-center">
                        <div class="pe-3">
                            <h4 class="m-0"><?php echo $customer['Fname'] . " " . $customer['Lname']; ?></h4>
                        </div>

                        <div class="ms-auto">
                            <div class="d-flex align-items-center small">
                                <div class="px-2"><strong>ID:</strong> <?php echo $customer['CustomerID']; ?></div>
                                <div class="px-2 border-start"><strong>Phone:</strong> <?php echo $customer['PhoneNumber']; ?></div>
                                <div class="px-2 border-start"><strong>Email:</strong> <?php echo $customer['Email']; ?></div>
                                <div class="px-2 border-start"><strong>DOB:</strong> <?php echo $customer['DateOfBirth']; ?></div>
                                <div class="px-2 border-start"><strong>Address:</strong> <?php echo $customer['Address']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-2">

                    <?php
                    // account
                    $safe_customer_id = mysqli_real_escape_string($conn, $customer['CustomerID']);
                    $account_query = "SELECT a.AccountID, a.Balance, DATE(a.DateOpened) AS DateOpened, at.TypeName AS AccountType
                                  FROM account a, account_type at
                                  WHERE a.AccountTypeID = at.AccountTypeID
                                  AND a.CustomerID = $safe_customer_id
                                  ORDER BY a.AccountID";

                    $account_result = mysqli_query($conn, $account_query);
                    ?>

                    <?php if(mysqli_num_rows($account_result) == 0): ?>
                        <p class="text-muted text-center mb-0">
                            No account found for this customer.
                        </p>
                    <?php endif; ?>

                    <?php while($account = mysqli_fetch_assoc($account_result)): ?>

                        <div class="border rounded mb-2 ms-5">
                            <div class="bg-light px-3 py-3 border-bottom d-flex align-items-center">
                                <h5 class="m-0">
                                    <strong>
                                     Account #<?php echo $account['AccountID']; ?>
                                    </strong>
                                </h5>

                                <span class="badge bg-info text-dark ms-3">
            <?php echo $account['AccountType']; ?>
        </span>

                                <span class="badge bg-success ms-2">
            Current Balance: $<?php echo number_format($account['Balance'], 2); ?>
        </span>

                                <small class="text-muted ms-auto">
                                    Opened: <?php echo $account['DateOpened']; ?>
                                </small>
                            </div>

                            <?php
                            // transaction
                            $safe_account_id = mysqli_real_escape_string($conn, $account['AccountID']);
                            $transaction_query = "SELECT t.TransactionID, tt.TypeName, t.Amount, DATE(t.Date) AS Date,
                                                         c.CurrencyCode, c.Symbol
                                                  FROM `transaction` t
                                                  JOIN transaction_type tt ON t.TransactionTypeID = tt.TransactionTypeID
                                                  JOIN currency c ON t.CurrencyID = c.CurrencyID
                                                  WHERE t.AccountID = $safe_account_id
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