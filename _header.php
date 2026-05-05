<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Banking Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for dropdowns -->
    <style>
        /* Change the color and weight of the dropdown items */
        .dropdown-item {
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            padding: 10px 20px; /* Gives it more "breathability" */
        }

        /* Add a nice background and bold text on hover */
        .dropdown-item:hover {
            background-color: #007bff; /* Matches your Primary Blue */
            color: white !important;
            font-weight: 700;
            padding-left: 25px; /* Subtle "slide" effect */
        }

        /* Make the "Data Imports" main link bold since it's an action */
        #importDropdown {
            font-weight: 600;
        }
    </style>

</head>
<body class="bg-light">

<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand" href="index.php">Online Banking Portal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto pe-4">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="data_dictionary.php">Data Dictionary</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="banking_ERD_page.php">ERD</a>
                </li>

                <!-- Imports dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Imports
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item <?php echo (isset($current_page) && $current_page == 'import1') ? 'active' : ''; ?>"
                               href="import1_services.php">1. Import Services</a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo (isset($current_page) && $current_page == 'import2') ? 'active' : ''; ?>"
                               href="import2_banking.php">2. Import Banking</a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo (isset($current_page) && $current_page == 'import3') ? 'active' : ''; ?>"
                               href="import3_staff.php">3. Import Staff</a>
                        </li>
                    </ul>
                </li>

                <!--Reports dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Reports
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item <?php echo (isset($current_page) && $current_page == 'report1') ? 'active' : ''; ?>"
                               href="report1_services.php">1. Services Report</a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo (isset($current_page) && $current_page == 'report2') ? 'active' : ''; ?>"
                               href="report2_banking.php">2. Banking Report</a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo (isset($current_page) && $current_page == 'report3') ? 'active' : ''; ?>"
                               href="report3_staff.php">3. Staff Report</a>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>