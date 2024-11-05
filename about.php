<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Real Estate</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        /* Background Image */
        .about-header {
            background-image: url('real.jpeg'); /* Replace with your image URL */
            background-size: cover;
            background-position: center;
            color: #ffffff;
            padding: 80px 0;
            text-align: center;
        }
        .about-header h1 {
            font-size: 3em;
            font-weight: bold;
            margin-bottom: 0;
        }
        .about-header p {
            font-size: 1.2em;
            margin-top: 10px;
        }
        /* Section Styling */
        .section {
            padding: 60px 0;
        }
        .section h2 {
            font-weight: bold;
            margin-bottom: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">R E A L - E S T A T E</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addListingModal">Add
                            Listing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="profile.php">Profile</a>
                    </li>
                   
                </ul>
                &nbsp
                <form action="" method="POST" class="d-inline">
                    <button type="submit" name="logout" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>
<!-- About Us Header -->
<section class="about-header">
    <h1>About Our Real Estate Company</h1>
    <p>Your trusted partner in finding your dream home</p>
</section>

<!-- About Us Content -->
<div class="container">
    <!-- Our Mission Section -->
    <section class="section">
        <h2 class="text-center">Our Mission</h2>
        <p class="text-center">We aim to simplify the process of buying, selling, and renting properties. We provide a seamless experience through expert guidance and high-quality listings.We actively engage with local communities through events, sponsorships, and partnerships, reinforcing our commitment to being a responsible corporate citizen.</p>
    </section>

    <!-- Our Values Section -->
    <section class="section bg-light">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-4">
                    <h4>Integrity</h4>
                    <p>We prioritize honesty and transparency in all our transactions to build lasting relationships with our clients.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <h4>Commitment</h4>
                    <p>Our commitment to our clients drives us to go above and beyond to meet their unique needs and goals.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <h4>Innovation</h4>
                    <p>We embrace technology and innovation to offer the best experience for our clients in the modern real estate market.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section class="section">
        <h2 class="text-center">Our Services</h2>
        <p class="text-center">We offer a wide range of services to meet all your real estate needs, whether you’re buying, selling, or renting.</p>
        <div class="row mt-4">
            <div class="col-md-4 text-center">
                <div class="card p-4">
                    <h5>Property Buying</h5>
                    <p>Explore a wide variety of properties available for purchase, with expert guidance to help you find the perfect fit.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="card p-4">
                    <h5>Property Selling</h5>
                    <p>Maximize your property’s value with our tailored marketing strategies and extensive buyer network.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="card p-4">
                    <h5>Rental Services</h5>
                    <p>Find the ideal rental property that meets your needs, with flexible options and dedicated support.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Footer -->

<footer class="bg-dark text-light py-4">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
