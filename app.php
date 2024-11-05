<?php
session_start();

define('USER_DATA_FILE', 'users.json');
define('INACTIVITY_LIMIT', 900); // 15 minutes in seconds

// Check session timeout and redirect if needed
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > INACTIVITY_LIMIT)) {
    session_unset();
    session_destroy();
    header("Location: app.php?message=Session expired. Please login again.");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

// Load users from JSON file
function load_users() {
    if (!file_exists(USER_DATA_FILE)) {
        file_put_contents(USER_DATA_FILE, json_encode([]));
    }
    return json_decode(file_get_contents(USER_DATA_FILE), true);
}

// Save users to JSON file
function save_users($users) {
    file_put_contents(USER_DATA_FILE, json_encode($users));
}

// Signup function with additional fields
function signup($username, $password, $email, $phone, $address) {
    $users = load_users();
    
    if (isset($users[$username])) {
        return "Username already exists!";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format!";
    }

    if (!preg_match('/^\d{10}$/', $phone)) {
        return "Invalid phone number! Must be 10 digits.";
    }
    
    $users[$username] = [
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'email' => $email,
        'phone' => $phone,
        'address' => $address
    ];
    save_users($users);
    return "Signup successful!";
}

// Login function
function login($username, $password) {
    $users = load_users();

    if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['LAST_ACTIVITY'] = time();
        header("Location: index.php");
        exit;
    }
    return "Invalid username or password!";
}

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        $message = signup($_POST['username'], $_POST['password'], $_POST['email'], $_POST['phone'], $_POST['address']);
    } elseif (isset($_POST['login'])) {
        $message = login($_POST['username'], $_POST['password']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function toggleForm(formType) {
            if (formType === 'signup') {
                document.getElementById('signup-form').style.display = 'block';
                document.getElementById('login-form').style.display = 'none';
            } else {
                document.getElementById('signup-form').style.display = 'none';
                document.getElementById('login-form').style.display = 'block';
            }
        }
    </script>
</head>
<body onload="toggleForm('login')">

<div class="container mt-5">
    <h4 class="text-center">R &nbsp E &nbsp A &nbsp L &nbsp-&nbsp E &nbsp S &nbsp T &nbsp A &nbsp T &nbsp E</h4>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

<!-- Signup Form -->
<div id="signup-form" class="card shadow-lg border-0 rounded-lg mt-5" style="display: none;">
    <div class="card-header bg-primary text-white">
        <h3 class="text-center font-weight-light my-4">Create Account</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3 mb-md-0">
                        <input class="form-control" id="signup-username" name="username" type="text" placeholder="Enter your username" required />
                        <label for="signup-username">Username</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input class="form-control" id="signup-email" name="email" type="email" placeholder="name@example.com" required />
                        <label for="signup-email">Email address</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3 mb-md-0">
                        <input class="form-control" id="signup-password" name="password" type="password" placeholder="Create a password" required />
                        <label for="signup-password">Password</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3 mb-md-0">
                        <input class="form-control" id="signup-phone" name="phone" type="tel" placeholder="1234567890" pattern="\d{10}" required />
                        <label for="signup-phone">Phone number</label>
                    </div>
                </div>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="signup-address" name="address" type="text" placeholder="Enter your address" required />
                <label for="signup-address">Address</label>
            </div>
            <div class="mt-4 mb-0">
                <div class="d-grid">
                    <button class="btn btn-primary btn-block" type="submit" name="signup">Create Account</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer text-center py-3">
        <div class="small"><a href="#" onclick="toggleForm('login')">Have an account? Go to login</a></div>
    </div>
</div>

<!-- Login Form -->
<div id="login-form" class="card shadow-lg border-0 rounded-lg mt-5">
    <div class="card-header bg-success text-white">
        <h3 class="text-center font-weight-light my-4">Login</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="form-floating mb-3">
                <input class="form-control" id="login-username" name="username" type="text" placeholder="Enter your username" required />
                <label for="login-username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="login-password" name="password" type="password" placeholder="Password" required />
                <label for="login-password">Password</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                <label class="form-check-label" for="inputRememberPassword">Remember password</label>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                <a class="small" href="#">Forgot Password?</a>
                <button class="btn btn-success" type="submit" name="login">Login</button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center py-3">
        <div class="small"><a href="#" onclick="toggleForm('signup')">Need an account? Sign up!</a></div>
    </div>
</div>
</div>

</body>
</html>
