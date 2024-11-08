<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <style>
        /* Custom Styles for Contact Section */
        body {
            background-color: #f8f9fa;
        }
        .contact {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: linear-gradient(to bottom right, rgba(70, 130, 180, 0.5), rgba(255, 255, 255, 0.8));
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .heading {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            color: #343a40;
            margin-bottom: 1.5rem;
        }
        .heading span {
            color: rgba(0, 123, 255, 0.5); /* Transparent color for "Us" */
        }
        .input-card {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .input-card input,
        textarea {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 5px;
            outline: none;
            font-size: 1rem;
        }
        .input-card input:focus,
        textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.2);
        }
        textarea {
            width: 100%;
            resize: none;
            margin-bottom: 1rem;
        }
        .button {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            background-color: rgba(0, 123, 255, 0.5); /* Transparent color for the submit button */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: rgba(0, 123, 255, 0.7); /* Darker on hover */
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
                    <li class="nav-item"><a class="nav-link" href="mortgage_calculator.php">Payment Estimator</a></li>
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
<section class="contact" id="contact">
      <h2 class="heading">Contact <span>Us</span></h2>

      <form action="https://api.web3forms.com/submit" method="POST">
        <input
          type="hidden"
          name="access_key"
          value="f821c1f7-3569-4e1e-9a9e-3f365d5d639c"
        />
        <div class="input-card">
          <input
            type="text"
            name="name"
            placeholder="Enter Your Name"
            required
          />
          <input
            type="email"
            name="email"
            placeholder="Enter Your Email"
            required
          />
        </div>

        <div class="input-card">
          <input
            type="tel"
            name="number"
            placeholder="Enter Your Mobile Number"
            required
          />
          <input
            type="text"
            name="subject"
            placeholder="Enter Subject"
            required
          />
        </div>

        <textarea
          name="message"
          cols="30"
          rows="5"
          placeholder="Enter Your Message Here..."
          required
        ></textarea>
        <button type="submit" class="button">Submit</button>
      </form>
    </section>
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

<!-- Add Bootstrap Icons if not already included -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

            
</body>

</html>
