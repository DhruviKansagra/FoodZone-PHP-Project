
<?php
session_start();

$conn = new mysqli("localhost", "root", "", "food");

if ($conn->connect_error) {
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $new_password = $_POST["new_password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if (empty($username) || empty($new_password) ||
        empty($confirm_password)) {

        $message = "Please fill in all fields.";
        $messageType = "error";

    } elseif ($new_password !== $confirm_password) {

        $message = "New password and confirm password do not match.";
        $messageType = "error";

    } elseif (strlen($new_password) < 8) {

        $message = "Password must contain at least 8 characters.";
        $messageType = "error";

    } else {

        $check = $conn->prepare(
            "SELECT username FROM user WHERE username = ?"
        );

        if ($check) {
            $check->bind_param("s", $username);
            $check->execute();
            $check->store_result();

            if ($check->num_rows === 1) {

                $check->close();

                $hashedPassword = password_hash(
                    $new_password,
                    PASSWORD_DEFAULT
                );

                $update = $conn->prepare(
                    "UPDATE user SET password = ? WHERE username = ?"
                );

                if ($update) {
                    $update->bind_param(
                        "ss",
                        $hashedPassword,
                        $username
                    );

                    if ($update->execute() &&
                        $update->affected_rows === 1) {

                        $message = "Password updated successfully! You can now log in.";
                        $messageType = "success";

                    } elseif ($update->affected_rows === 0) {

                        $message = "Password is unchanged or the account was not updated.";
                        $messageType = "error";

                    } else {

                        $message = "Failed to update password.";
                        $messageType = "error";
                    }

                    $update->close();

                } else {
                    $message = "Unable to prepare the password update.";
                    $messageType = "error";
                }

            } else {
                $message = "Username not found.";
                $messageType = "error";
                $check->close();
            }

        } else {
            $message = "Unable to check the username.";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | FoodieHub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;

            background:
                linear-gradient(
                    rgba(0, 0, 0, .45),
                    rgba(0, 0, 0, .45)
                ),
                url("images/food1.jpg");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Glass Card */

        .form-container {
            width: 420px;
            max-width: 100%;
            padding: 35px;

            border-radius: 20px;

            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);

            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);

            box-shadow: 0 8px 35px rgba(0, 0, 0, .3);
            color: #fff;
        }

        /* Back Link */

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            transition: .3s;
        }

        .back-link:hover {
            color: #FFD54F;
            transform: translateX(-3px);
        }

        /* Logo */

        .logo {
            text-align: center;
            font-size: 50px;
            margin-bottom: 8px;
        }

        /* Heading */

        h2 {
            text-align: center;
            font-size: 29px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #eee;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        /* Inputs */

        .input-box {
            margin-bottom: 18px;
        }

        .input-box label {
            display: block;
            font-size: 13px;
            margin-bottom: 7px;
            color: #fff;
        }

        .input-box input {
            width: 100%;
            padding: 13px 17px;

            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, .35);

            background: rgba(255, 255, 255, .12);
            outline: none;

            color: #fff;
            font-size: 14px;
            transition: .3s;
        }

        .input-box input::placeholder {
            color: #eee;
        }

        .input-box input:focus {
            border-color: #ff9800;
            background: rgba(255, 255, 255, .20);
            box-shadow: 0 0 8px rgba(255, 152, 0, .25);
        }

        /* Password visibility */

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 55px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);

            width: auto;
            padding: 0;
            background: transparent;
            border: none;
            box-shadow: none;

            color: #fff;
            font-size: 12px;
            cursor: pointer;
        }

        .toggle-password:hover {
            background: transparent;
            transform: translateY(-50%);
            box-shadow: none;
            color: #FFD54F;
        }

        /* Button */

        .reset-btn {
            width: 100%;
            padding: 14px;

            border: none;
            border-radius: 50px;

            background: #ff6b00;
            color: #fff;

            font-size: 16px;
            font-weight: 600;

            cursor: pointer;
            transition: .3s;
        }

        .reset-btn:hover {
            background: #ff8c1a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 0, .5);
        }

        /* Messages */

        .message {
            margin-top: 18px;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-size: 13px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .error {
            background: rgba(255, 221, 221, .95);
            color: #a40000;
        }

        .success {
            background: rgba(220, 255, 225, .95);
            color: #12672a;
        }

        .message a {
            display: inline-block;
            margin-top: 5px;
            color: #12672a;
            font-weight: 700;
        }

        /* Login Link */

        .login-link {
            margin-top: 22px;
            text-align: center;
            font-size: 13px;
            color: #eee;
        }

        .login-link a {
            color: #FFD54F;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Responsive */

        @media (max-width: 450px) {
            .form-container {
                padding: 25px 22px;
            }

            h2 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<div class="form-container">

    <a href="login.php" class="back-link">
        &#8592; Back to Login
    </a>

    <h2>Reset Password</h2>
    <form method="POST" action="" autocomplete="off">

        <div class="input-box">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                placeholder="Enter your username"
                value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
                autocomplete="username">
        </div>

        <div class="input-box">
            <label for="new_password">New Password</label>

            <div class="password-wrapper">
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    placeholder="Enter new password"
                    minlength="8"
                    required
                    autocomplete="new-password">

                <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword('new_password', this)">
                    Show
                </button>
            </div>
        </div>

        <div class="input-box">
            <label for="confirm_password">Confirm Password</label>

            <div class="password-wrapper">
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm new password"
                    minlength="8"
                    required
                    autocomplete="new-password">

                <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword('confirm_password', this)">
                    Show
                </button>
            </div>
        </div>

        <button type="submit" class="reset-btn">
            Reset Password
        </button>

    </form>

    <?php if ($message !== ""): ?>
        <div class="message <?= $messageType === 'success' ? 'success' : 'error' ?>"
             role="status">

            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>

            <?php if ($messageType === "success"): ?>
                <br>
                <a href="login.php">Login Now</a>
            <?php endif; ?>

        </div>
    <?php endif; ?>

    <div class="login-link">
        Remember your password?
        <a href="login.php">Login Here</a>
    </div>

</div>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);

        if (input.type === "password") {
            input.type = "text";
            button.textContent = "Hide";
        } else {
            input.type = "password";
            button.textContent = "Show";
        }
    }

    const passwordForm = document.querySelector("form");

    passwordForm.addEventListener("submit", function (event) {
        const password = document.getElementById("new_password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        if (password !== confirmPassword) {
            event.preventDefault();
            alert("New password and confirm password do not match.");
        }
    });
</script>

</body>
</html>
