<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mortgage Calculator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <style>
        /* Make sure the body and html fill the full height of the page */
        html, body {
            height: 100%;
            margin: 0;
        }

        /* Use flexbox layout for the page content */
        .content {
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        /* Style for the mortgage calculator form */
        .calculator {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin: 20px auto;
            position: relative;
            z-index: 1;
        }

        .result {
            margin-top: 15px;
            font-weight: bold;
        }

        /* Footer styling */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto; /* Push footer to the bottom */
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">R E A L - E S T A T E</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addListingModal">Add
                            Listing</a>
                    </li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="mortgage_calculator.php">Payment Estimator</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    </ul>
                    <form action="" method="POST" class="d-inline">
                        <button type="submit" name="logout" class="btn btn-outline-danger btn-sm">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Mortgage Calculator Form -->
        <div class="calculator">
            <h2>Mortgage Calculator</h2>
            <form method="POST" action="mortgage_calculator.php">
                <label for="loan_amount">Loan Amount:</label>
                <input type="number" name="loan_amount" id="loan_amount" required class="form-control"><br><br>

                <label for="interest_rate">Interest Rate (%):</label>
                <input type="number" step="0.01" name="interest_rate" id="interest_rate" required class="form-control"><br><br>

                <label for="loan_term">Loan Term (Years):</label>
                <input type="number" name="loan_term" id="loan_term" required class="form-control"><br><br>

                <button type="submit" name="calculate" class="btn btn-primary">Calculate</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calculate'])) {
                // Fetch the form data
                $loan_amount = $_POST['loan_amount'];
                $interest_rate = $_POST['interest_rate'];
                $loan_term = $_POST['loan_term'];

                // Calculate monthly interest rate
                $monthly_interest_rate = ($interest_rate / 100) / 12;

                // Calculate the number of payments (in months)
                $num_payments = $loan_term * 12;

                // Calculate monthly payment using the formula
                $monthly_payment = $loan_amount * $monthly_interest_rate / (1 - pow(1 + $monthly_interest_rate, -$num_payments));

                // Display the result
                echo "<div class='result'>Monthly Payment: ₹" . number_format($monthly_payment, 2) . "</div>";
            }
            ?>
        </div>

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <h5>Your Trusted Real Estate Partner</h5>
                        <p>Providing exceptional service in residential and commercial real estate.</p>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <h5>Follow Us</h5>
                        <ul class="list-unstyled d-flex justify-content-center">
                            <li class="mx-2"><a href="https://www.facebook.com/login.php/" class="text-light"><i class="bi bi-facebook"></i></a></li>
                            <li class="mx-2"><a href="https://x.com/login-to/" class="text-light"><i class="bi bi-twitter"></i></a></li>
                            <li class="mx-2"><a href="https://www.instagram.com/" class="text-light"><i class="bi bi-instagram"></i></a></li>
                            <li class="mx-2"><a href="https://www.linkedin.com/home" class="text-light"><i class="bi bi-linkedin"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <h5>Stay Connected</h5>
                        <p>Join our community for the latest updates and property listings.</p>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p>&copy; 2024 Your Real Estate Company. All rights reserved.</p>
                    <p><small>Privacy Policy | Terms of Service</small></p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
