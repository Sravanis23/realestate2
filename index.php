<?php
// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

session_start();

// Define inactivity limit (15 minutes in seconds)
define('INACTIVITY_LIMIT', 900);

// Redirect to login if the user is not logged in or if the session has expired
if (!isset($_SESSION['username']) || 
    (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > INACTIVITY_LIMIT))) {
    
    // Clear the session and redirect to app.php with a message if session expired
    session_unset();
    session_destroy();
    header("Location: app.php?message=Please log in to access this page.");
    exit;
}

// Update last activity time for inactivity check
$_SESSION['LAST_ACTIVITY'] = time();

// Load existing listings from database.json
$file_path = __DIR__ . '/database.json';
$listings = file_exists($file_path) ? json_decode(file_get_contents($file_path), true) : [];
if ($listings === null) {
    error_log("Could not read or parse the listings data from database.json.");
    $listings = [];
}

function generateUniqueId($length = 8) {
    return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / 62))), 1, $length);
}


// Function to save listings
function saveListing($newListing) {
    global $listings, $file_path;
    $listings[] = $newListing;
    file_put_contents($file_path, json_encode($listings, JSON_PRETTY_PRINT));
}

// Function to get status counts
function getStatusCounts($listings) {
    $counts = [
        'hold' => 0,
        'available' => 0,
        'sold' => 0,
    ];

    foreach ($listings as $listing) {
        if (isset($listing['status'])) {
            $status = $listing['status'];
            if (array_key_exists($status, $counts)) {
                $counts[$status]++;
            }
        }
    }
    return $counts;
}
$statusCounts = getStatusCounts($listings);


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for uploaded image
    if (!empty($_FILES['image']['tmp_name'])) {
        $client_id = "1eff9231546a3d7";
        $headers = array("Authorization: Client-ID $client_id");
        
        // Read image file and convert to base64
        $image = $_FILES['image']['tmp_name'];
        $data = array('image' => base64_encode(file_get_contents($image)));
        
        // Imgur API request
        $ch = curl_init("https://api.imgur.com/3/image");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        
        $imgUrl = json_decode($response)->data->link ?? '';
        if (!$imgUrl) {
            error_log("Image upload failed: " . $response);
            die("Image upload failed.");
        }

        // Collect form data
        $newListing = [
            'id' => generateUniqueId(), // Add this line to generate a unique ID
            "title" => $_POST['title'] ?? '',
            "price" => !empty($_POST['price']) ? (float)$_POST['price'] : 0, // Set default to 0 if empty
            "description" => $_POST['description'] ?? '', // Default to empty string if not set
            "image_url" => $imgUrl,
            "bedrooms" => !empty($_POST['bedrooms']) ? (int)$_POST['bedrooms'] : 0, // Default to 0 if empty
            "bathrooms" => !empty($_POST['bathrooms']) ? (int)$_POST['bathrooms'] : 0, // Default to 0 if empty
            "square_feet" => !empty($_POST['square_feet']) ? (int)$_POST['square_feet'] : 0, // Default to 0 if empty
            "location" => $_POST['location'] ?? '', // Default to empty string if not set
            "contact_email" => $_POST['contact_email'] ?? '', // Default to empty string if not set
            'status' => "available", // Ensure you have a status field

        ];

        // Use try-catch to handle exceptions when formatting the price
        try {
            $formattedPrice = number_format($newListing['price'], 2); // Format price to 2 decimal places
        } catch (TypeError $e) {
            error_log("Error formatting price: " . $e->getMessage());
            $formattedPrice = "0.00"; // Set to a default value if an error occurs
        }

        // Save new listing and redirect
        saveListing($newListing);
        header("Location: index.php");
        exit;
    } else {
        die("Please upload an image.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Listings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <style>
        html {
            scroll-behavior: smooth;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 10px;
        }

        .carousel-item img {
            width: 100%;
            /* Full width */
            height: auto;
            /* Maintain aspect ratio */
            max-height: 400px;
            /* Set a max height for the images */
            object-fit: cover;
            /* Cover to fill the space without distortion */
        }

        .carousel {
            height: 400px;
            /* Height of the carousel */
        }

        .carousel-caption {
            bottom: 0;
            left: 0;
            text-align: left;
            padding: 1rem;
            /* Add some padding for better visibility */
        }

        .bg-grey {
            background-color: rgba(0, 0, 0, 0.5);
            /* Grey background with transparency */
            border-radius: 0.5rem;
            /* Round edges */
        }

        .image-container {
            width: 100%;
            /* Set the container width */
            height: 300px;
            /* Set a fixed height for the square */
            overflow: hidden;
            /* Hide any overflow */
            display: flex;
            /* Use flexbox to center the image */
            justify-content: center;
            /* Center the image horizontally */
            align-items: center;
            /* Center the image vertically */
            background-color: #f0f0f0;
            /* Optional: add a background color for empty space */
        }

        .image-container img {
            max-width: 100%;
            /* Ensure the image does not exceed the container's width */
            max-height: 100%;
            /* Ensure the image does not exceed the container's height */
            object-fit: contain;
            /* Fit the image within the container without cropping */
        }
    </style>
    <script>
        function searchListings() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const listings = document.getElementsByClassName('listing-card');

            for (let i = 0; i < listings.length; i++) {
                const title = listings[i].querySelector('.card-title').textContent.toLowerCase();
                const description = listings[i].querySelector('.card-text').textContent.toLowerCase();
                const location = listings[i].querySelector('.location').textContent.toLowerCase();

                if (title.includes(searchInput) || description.includes(searchInput) || location.includes(
                        searchInput)) {
                    listings[i].style.display = 'block';
                } else {
                    listings[i].style.display = 'none';
                }
            }
        }
    </script>
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
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addListingModal">Add
                            Listing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://ilead.net.in/wp-content/uploads/2023/04/shutterstock_743951458-2.jpg" class="d-block"
                    alt="First slide">
                <div class="carousel-caption" style="bottom: 0; left: 0; text-align: left;">
                    <div class="bg-grey rounded p-3" style="background-color: rgba(0, 0, 0, 0.5);">
                        <h5 class="text-white">Find Your Dream Home</h5>
                        <p class="text-white">Experience luxury living with stunning properties.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://www.vidyard.com/media/real-estate-video-marketing-1920x1080-1.jpg" class="d-block"
                    alt="Second slide">
                <div class="carousel-caption" style="bottom: 0; left: 0; text-align: left;">
                    <div class="bg-grey rounded p-3" style="background-color: rgba(0, 0, 0, 0.5);">
                        <h5 class="text-white">Your Perfect Investment Awaits</h5>
                        <p class="text-white">Invest in your future with our exclusive listings.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://static.rdc.moveaws.com/images/hero/default/2021-11/jpg/hp-hero-desktop.jpg"
                    class="d-block" alt="Third slide">
                <div class="carousel-caption" style="bottom: 0; left: 0; text-align: left;">
                    <div class="bg-grey rounded p-3" style="background-color: rgba(0, 0, 0, 0.5);">
                        <h5 class="text-white">Make Memories in Your New Home</h5>
                        <p class="text-white">Your journey to a beautiful home starts here.</p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <div class="container my-5">
        <h4 class="text-center">R &nbsp E &nbsp A &nbsp L &nbsp-&nbsp E &nbsp S &nbsp T &nbsp A &nbsp T &nbsp E
            &nbsp-&nbsp L &nbsp I &nbsp S &nbsp T &nbsp I &nbsp N &nbsp G &nbsp S</h4>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center mb-4 shadow border-light">
                        <div class="card-header"
                            style="background: linear-gradient(to right, #6ec1e4, #1a73e8); color: white;">
                            <i class="bi bi-hourglass-split" style="font-size: 2rem;"></i> <!-- Bootstrap Icons -->
                            <h5 class="my-2">Hold</h5>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title display-4"
                                style="background: linear-gradient(to right, #6ec1e4, #1a73e8); -webkit-background-clip: text; color: transparent;">
                                <?php echo $statusCounts['hold']; ?>
                            </h1>
                            <p class="card-text text-muted">Currently on hold</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center mb-4 shadow border-light">
                        <div class="card-header"
                            style="background: linear-gradient(to right, #a8e6cf, #28a745); color: white;">
                            <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                            <h5 class="my-2">Available</h5>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title display-4"
                                style="background: linear-gradient(to right, #a8e6cf, #28a745); -webkit-background-clip: text; color: transparent;">
                                <?php echo $statusCounts['available']; ?>
                            </h1>
                            <p class="card-text text-muted">Available for purchase</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center mb-4 shadow border-light">
                        <div class="card-header"
                            style="background: linear-gradient(to right, #ffccbc, #dc3545); color: white;">
                            <i class="bi bi-cart-check" style="font-size: 2rem;"></i>
                            <h5 class="my-2">Sold</h5>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title display-4"
                                style="background: linear-gradient(to right, #ffccbc, #dc3545); -webkit-background-clip: text; color: transparent;">
                                <?php echo $statusCounts['sold']; ?>
                            </h1>
                            <p class="card-text text-muted">Total sold listings</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="d-flex justify-content-between align-items-center my-3">
            <input type="text" id="searchInput" class="form-control me-2" placeholder="Search listings..."
                onkeyup="searchListings()" style="border-radius: 40px; border: 1px solid transparent; 
        background: linear-gradient(white, white), linear-gradient(to right, #ff0000, #2575fc); 
        background-clip: padding-box, border-box; 
        padding: 10px;">

            <button class="btn" data-bs-toggle="modal" data-bs-target="#addListingModal" style="border-radius: 20px; 
        background: linear-gradient(to right, #ff0000, #2575fc); 
        color: white; border: none; padding: 10px 15px;">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>


        <br>

        <!-- Listings Display -->
        <div class="row">
            <?php foreach ($listings as $listing): ?>
            <div class="col-md-3 mb-4 listing-card">
                <!-- Change to col-md-3 for smaller cards -->
                <div class="card h-100 shadow-sm" style="width: 100%; padding: 0.5rem;">
                    <!-- Add padding to reduce size -->
                    <div class="image-container">
                        <img src="<?= htmlspecialchars($listing['image_url']) ?>" class="card-img-top"
                            alt="Listing Image" style="height: 150px; object-fit: cover;"> <!-- Adjust image height -->
                    </div>
                    <!-- Status Display -->
                    <div class="status" style="
    <?php 
        echo ($listing['status'] == 'available') ? 'background: linear-gradient(to right, #aae6cf, #28a745);' : 
        (($listing['status'] == 'hold') ? 'background: linear-gradient(to right, #6ec1e4, #1a73e8);' : 
        (($listing['status'] == 'sold') ? 'background: linear-gradient(to right, #ffccbc, #dc3545);' : 'background: transparent;')); 
    ?>
">
                        <p style="color: white; margin: 0;"><?php echo ucfirst($listing['status']); ?></p>
                    </div>
                    <div class="card-body" style="padding: 0.5rem;">
                        <!-- Reduce padding -->
                        <h5 class="card-title" style="font-size: 1.25rem;"><?= htmlspecialchars($listing['title']) ?>
                        </h5>
                        <p class="card-text" style="font-size: 0.9rem;"><strong>Price:</strong>
                            $<?= number_format((float)$listing['price'], 2) ?></p>
                        <p class="card-text" style="font-size: 0.9rem;"><?= htmlspecialchars($listing['description']) ?>
                        </p>
                        <ul class="list-unstyled" style="font-size: 0.9rem;">
                            <li class="location"><strong>Location:</strong>
                                <?= htmlspecialchars($listing['location']) ?></li>
                            <li><strong>Bedrooms:</strong> <?= (int)($listing['bedrooms'] ?? 0) ?></li>
                            <li><strong>Bathrooms:</strong> <?= (int)($listing['bathrooms'] ?? 0) ?></li>
                            <li><strong>Square Feet:</strong> <?= number_format((int)($listing['square_feet'] ?? 0)) ?>
                                sqft</li>
                        </ul>
                        <p style="font-size: 0.9rem;"><strong>Contact:</strong> <a
                                href="mailto:<?= htmlspecialchars($listing['contact_email']) ?>"><?= htmlspecialchars($listing['contact_email']) ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- Modal for Adding Listings -->
    <div class="modal fade" id="addListingModal" tabindex="-1" aria-labelledby="addListingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addListingModalLabel">Add New Listing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="bedrooms" class="form-label">Bedrooms</label>
                            <input type="number" class="form-control" id="bedrooms" name="bedrooms" required>
                        </div>
                        <div class="mb-3">
                            <label for="bathrooms" class="form-label">Bathrooms</label>
                            <input type="number" class="form-control" id="bathrooms" name="bathrooms" required>
                        </div>
                        <div class="mb-3">
                            <label for="square_feet" class="form-label">Square Feet</label>
                            <input type="number" class="form-control" id="square_feet" name="square_feet" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Listing</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer id="footer" class="bg-light text-dark py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt"></i> 123 Main Street, City, Country</li>
                        <li><i class="bi bi-telephone"></i> +1 (555) 123-4567</li>
                        <li><i class="bi bi-envelope"></i> <a href="mailto:info@example.com"
                                class="text-white">info@example.com</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <ul class="list-unstyled d-flex justify-content-center">
                        <li class="mx-2"><a href="#" class="text-white"><i class="bi bi-facebook"></i></a></li>
                        <li class="mx-2"><a href="#" class="text-white"><i class="bi bi-twitter"></i></a></li>
                        <li class="mx-2"><a href="#" class="text-white"><i class="bi bi-instagram"></i></a></li>
                        <li class="mx-2"><a href="#" class="text-white"><i class="bi bi-linkedin"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p>We are committed to helping you find your dream home. Our team is dedicated to providing the best
                        service possible.</p>
                </div>
            </div>
            <div class="text-center mt-4">
                <small>&copy; 2024 Real Estate Listings. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>