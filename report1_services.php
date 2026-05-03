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
        <h1 class="mb-4">Bank Services</h1>

        <div class="card shadow mb-5">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><i class="bi bi-cash-stack"></i> ATM</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Address</th>
                        <th class="text-center">City</th>
                        <th class="text-center">State</th>
                        <th class="text-center">Current Cash</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($atm = mysqli_fetch_assoc($atm_result)): ?>
                        <tr>
                            <td class="text-center"><?php echo $atm['ATMID']; ?></td>
                            <td class="text-center"><?php echo $atm['StreetAddress']; ?></td>
                            <td class="text-center"><?php echo $atm['City']; ?></td>
                            <td class="text-center"><?php echo $atm['State']; ?></td>
                            <td class="text-center text-success"><strong>$<?php echo number_format($atm['CurrentCash'], 2); ?></strong></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mb-5">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-currency-exchange"></i> Currency</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center">Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Symbol</th>
                        <th class="text-center">Rate to USD</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($curr = mysqli_fetch_assoc($curr_result)): ?>
                        <tr>
                            <td class="text-center"><strong><?php echo $curr['CurrencyCode']; ?></strong></td>
                            <td class="text-center"><?php echo $curr['CurrencyName']; ?></td>
                            <td class="text-center"><?php echo $curr['Symbol']; ?></td>
                            <td class="text-center"><?php echo number_format($curr['ExchangeRateToUSD'], 4); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mb-5">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-piggy-bank"></i> Account Types</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center">Type Name</th>
                        <th class="text-center">Interest Rate</th>
                        <th class="text-center">Monthly Fee</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($acc = mysqli_fetch_assoc($acc_result)): ?>
                        <tr>
                            <td class="text-center"><strong><?php echo $acc['TypeName']; ?></strong></td>
                            <td class="text-center"><?php echo ($acc['InterestRate'] * 100); ?>%</td>
                            <td class="text-center">$<?php echo number_format($acc['MonthlyFee'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include('_footer.php'); ?>