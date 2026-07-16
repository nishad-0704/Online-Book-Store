<?php
session_start();            

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Read user data from file
        $userFound = false;
        if (file_exists("users.txt")) {
            $users = file("users.txt", FILE_IGNORE_NEW_LINES);
            foreach ($users as $user) { 
                $userData = explode(",", $user);
                if (count($userData) >= 3 && $userData[0] === $username) {
                    $storedUsername = $userData[0];
                    $storedEmail = $userData[1];
                    $storedHashedPassword = $userData[2];
                    
                    // Verify password
                    if (password_verify($password, $storedHashedPassword)) {
                        // Login successful - set session variables
                        $_SESSION['username'] = $storedUsername;
                        $_SESSION['email'] = $storedEmail;
                        $_SESSION['login_time'] = date('Y-m-d H:i:s');
                        
                        // Redirect to home page
                        header('Location: home.html');
                        exit();
                    } else {
                        $error = 'Invalid password.';
                    }
                    $userFound = true;
                    break;
                }
            }
        }
        
        if (!$userFound) {
            $error = 'Username not found. Please <a href="register.html">register first</a>.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <style>
            html {
                font-size: 25px;
                font-family: 'Times New Roman', Times, serif;
            }
            body {
                background-color: #333;
                margin: 0;
                padding: 0;
                background-image: url("html6.jpeg");
                background-size: cover;
            }
            .error {
                color: red;
                text-align: center;
                margin: 10px 0;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <h1 style="color: rgb(0, 0, 0); text-align: center;">Login</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="post" style="text-align: center;">
            <label for="username" style="color: rgb(0, 0, 0);">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password" style="color: rgb(0, 0, 0);">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>
        
        <p style="color: rgb(0, 0, 0); text-align: center;">
            Don't have an account? <a href="register.html">Register here</a>
        </p>
    </body>
</html>
