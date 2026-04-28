<?php
$current_page = 'report1';
include('_header.php');
include('db_config.php');

// 1. Get ATM Data
$atm_query = "SELECT ATMID, StreetAddress, City, State, CurrentCash FROM atm ORDER BY City";
$atm_result = mysqli_query($conn, $atm_query);

// 2. Get Currency Data
$curr_query = "SELECT CurrencyCode, CurrencyName, ExchangeRateToUSD, Symbol FROM currency ORDER BY CurrencyName";
$curr_result = mysqli_query($conn, $curr_query);

// 3. Get Account Type Data
$acc_query = "SELECT TypeName, InterestRate, MonthlyFee FROM account_type ORDER BY TypeName";
$acc_result = mysqli_query($conn, $acc_query);
?>

    <div class="container mt-5">
        <h2 class="mb-4">Bank Services & Infrastructure</h2>

        <div class="card shadow mb-5">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-cash-stack"></i> ATM</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Address</th>
                        <th>City, State</th>
                        <th class="text-end">Current Cash</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($atm = mysqli_fetch_assoc($atm_result)): ?>
                        <tr>
                            <td><?php echo $atm['ATMID']; ?></td>
                            <td><?php echo $atm['StreetAddress']; ?></td>
                            <td><?php echo $atm['City'] . ", " . $atm['State']; ?></td>
                            <td class="text-end">$<?php echo number_format($atm['CurrentCash'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mb-5">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="bi bi-currency-exchange"></i> Currency</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th class="text-end">Rate to USD</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($curr = mysqli_fetch_assoc($curr_result)): ?>
                        <tr>
                            <td><strong><?php echo $curr['CurrencyCode']; ?></strong></td>
                            <td><?php echo $curr['CurrencyName']; ?></td>
                            <td><?php echo $curr['Symbol']; ?></td>
                            <td class="text-end"><?php echo number_format($curr['ExchangeRateToUSD'], 4); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mb-5">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><i class="bi bi-piggy-bank"></i> Account Types</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Type Name</th>
                        <th>Interest Rate</th>
                        <th class="text-end">Monthly Fee</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($acc = mysqli_fetch_assoc($acc_result)): ?>
                        <tr>
                            <td><strong><?php echo $acc['TypeName']; ?></strong></td>
                            <td><?php echo ($acc['InterestRate'] * 100); ?>%</td>
                            <td class="text-end">$<?php echo number_format($acc['MonthlyFee'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include('_footer.php'); ?>