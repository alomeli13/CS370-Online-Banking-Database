<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Banking Portal</title>
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
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="importDrop" role="button" data-bs-toggle="dropdown">Imports</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="import_customer_data.php">Customer Import</a></li>
                        <li><a class="dropdown-item disabled" href="#">Staff Import</a></li>
                        <li><a class="dropdown-item disabled" href="#">Services Import</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="pizza_data_report.php">Reports</a></li>
            </ul>
        </div>
    </div>
</nav>