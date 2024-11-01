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

// Signup function
function signup($username, $password) {
    $users = load_users();
    
    if (isset($users[$username])) {
        return "Username already exists!";
    }
    
    $users[$username] = password_hash($password, PASSWORD_DEFAULT);
    save_users($users);
    return "Signup successful!";
}

// Login function
function login($username, $password) {
    $users = load_users();

    if (isset($users[$username]) && password_verify($password, $users[$username])) {
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
        $message = signup($_POST['username'], $_POST['password']);
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
    <div id="signup-form" class="card mb-3" style="display: none;">
        <div class="card-body">
            <h4>Signup</h4>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="signup-username">Username</label>
                    <input type="text" name="username" id="signup-username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="signup-password">Password</label>
                    <input type="password" name="password" id="signup-password" class="form-control" required>
                </div>
                <button type="submit" name="signup" class="btn btn-primary">Sign Up</button>
            </form>
            <p class="mt-3">Already have an account? <a href="#" onclick="toggleForm('login')">Click here to login</a></p>
        </div>
    </div>

    <!-- Login Form -->
    <div id="login-form" class="card">
        <div class="card-body">
            <h4>Login</h4>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="login-username">Username</label>
                    <input type="text" name="username" id="login-username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" name="password" id="login-password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-success">Login</button>
            </form>
            <p class="mt-3">Need to create an account? <a href="#" onclick="toggleForm('signup')">Click here</a></p>
        </div>
    </div>
</div>

</body>
</html>
