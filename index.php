<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Banking Portal | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">


<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand" href="index.php">Online Banking Portal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="importDrop" role="button" data-bs-toggle="dropdown">
                        Imports
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="import_customer_data.php">Customer Import</a></li>
                        <li><a class="dropdown-item disabled" href="#">Staff Import (Coming Soon)</a></li>
                        <li><a class="dropdown-item disabled" href="#">Services Import (Coming Soon)</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pizza_data_report.php">Reports</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

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
                    <h5 class="card-title">Core Banking</h5>
                    <p class="card-text text-muted">Customer, Account, and Transaction data.</p>
                    <a href="pizza_data_report.php" class="btn btn-primary">View Report</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Staff & Branches</h5>
                    <p class="card-text text-muted">Branch locations, Employees, and Dependents.</p>
                    <a href="#" class="btn btn-outline-primary disabled">Coming Soon</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Services</h5>
                    <p class="card-text text-muted">Manage Loans, ATMs, and Currency rates.</p>
                    <a href="#" class="btn btn-outline-primary disabled">Coming Soon</a>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="py-4 my-5 border-top text-center text-muted">
    <p>Copyright &copy; 2026 Abuwa | CS 370 Database Management Systems</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>