<?php
session_start();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if username already exists
        $userExists = false;
        if (file_exists("users.txt")) {
            $users = file("users.txt", FILE_IGNORE_NEW_LINES);
            foreach ($users as $user) {
                $userData = explode(",", $user);
                if ($userData[0] === $username) {
                    $userExists = true;
                    break;
                }
            }
        }

        if ($userExists) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Save user data to a file (format: username,email,hashedPassword)
            $userData = "$username,$email,$hashedPassword\n";
            file_put_contents("users.txt", $userData, FILE_APPEND);

            $success = "Registration successful!";
            $_SESSION['registered_username'] = $username;
            $_SESSION['registered_email'] = $email;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Register</title>
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
        </style>
    </head>
    <body>
        <h1 style="color: rgb(0, 0, 0); text-align: center;">Register</h1>
        
        <?php if ($error): ?>
            <div style="color: red; text-align: center; margin: 10px 0; font-weight: bold;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success && isset($_SESSION['registered_username'])): ?>
            <div style="color: green; text-align: center; margin: 20px 0; font-weight: bold;">
                <p><?php echo $success; ?></p>
                <div style="background-color: #f0f0f0; color: #333; padding: 20px; margin: 20px auto; width: 300px; border-radius: 8px;">
                    <p><strong>Your Account Details:</strong></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['registered_username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['registered_email']); ?></p>
                </div>
                <a href="login.html" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 18px; margin-top: 20px;">Go to Login</a>
            </div>
        <?php else: ?>
            <form action="register.php" method="post" style="text-align: center;">
                <label for="username" style="color: rgb(0, 0, 0);">Username:</label><br>
                <input type="text" id="username" name="username" required><br><br>
                <label for="email" style="color: rgb(0, 0, 0);">Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>
                <label for="password" style="color: rgb(0, 0, 0);">Password:</label><br>
                <input type="password" id="password" name="password" placeholder="At least 6 characters" required><br><br>
                <input type="submit" value="Register">
            </form>
            <p style="color: rgb(0, 0, 0); text-align: center;">
                Already have an account? <a href="login.html">Login here</a>
            </p>
        <?php endif; ?>
    </body>
</html>