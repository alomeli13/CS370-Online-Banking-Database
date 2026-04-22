<?php include('_header.php'); ?>

<div class="container mt-5">
    <div class="p-5 mb-4 bg-white border rounded-3 shadow-sm">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-5 fw-bold">Secure Banking Management</h1>
            <p class="col-md-8 fs-4 mx-auto">
                Welcome to the Administrative Interface for the Online Banking System.
                Use the navigation above to manage data imports and view financial reports.
            </p>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">1. Services & Types</h5>
                    <p class="card-text text-muted">ATMs, Currency Exchange, and Account Types.</p>

                    <a href="report1_services.php" class="btn btn-outline-primary">View Report 1</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">2. Core Banking</h5>
                    <p class="card-text text-muted">Customer, Account, and Transaction data.</p>
                    <a href="report2_banking.php" class="btn btn-outline-primary">View Report 2</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">3. Staff & Branches</h5>
                    <p class="card-text text-muted">Branch Locations, Employees, and Dependents.</p>
                    <a href="report3_staff.php" class="btn btn-outline-primary">View Report 3</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include('_footer.php'); ?>