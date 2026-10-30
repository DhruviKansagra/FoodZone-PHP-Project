<?php
session_start();
$conn = new mysqli("localhost", "root", "", "food");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (!empty($username) && !empty($password)) {

        // ✅ Admin login directly (without database)
        if ($username === 'admin' && $password === 'admin') {
            $_SESSION['username'] = 'admin';
            $_SESSION['role'] = 'admin';
            header("Location: /food/admin/admin.php");
            exit();
        }

        // ✅ Regular user login from database
        $stmt = $conn->prepare("SELECT id, password FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = 'user';

                // ✅ Check if redirected from specific page (like add_to_cart.php)
                if (isset($_GET['redirect'])) {
                    header("Location: " . $_GET['redirect']);
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $message = "❌ Incorrect password.";
            }
        } else {
            $message = "❌ Username not found.";
        }

        $stmt->close();
    } else {
        $message = "❌ All fields are required.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Food Ordering Login</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{

height:100vh;

display:flex;
justify-content:center;
align-items:center;

background:
linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),
url("images/food1.jpg");

background-size:cover;
background-position:center;
background-repeat:no-repeat;

overflow:hidden;

}

/* Glass Card */

.form-container{

width:390px;

padding:40px;

border-radius:20px;

background:rgba(255,255,255,.15);

border:1px solid rgba(255,255,255,.25);

backdrop-filter:blur(18px);

-webkit-backdrop-filter:blur(18px);

box-shadow:0 8px 35px rgba(0,0,0,.3);

color:#fff;

}

/* Logo */

.logo{

font-size:55px;
text-align:center;
margin-bottom:10px;

}

/* Heading */

h2{

text-align:center;

font-size:32px;

margin-bottom:30px;

font-weight:600;

}

/* Inputs */

.input-box{

margin-bottom:18px;

}

.input-box input{

width:100%;

padding:15px 20px;

border-radius:50px;

border:1px solid rgba(255,255,255,.35);

background:rgba(255,255,255,.12);

outline:none;

color:white;

font-size:15px;

transition:.3s;

}

.input-box input::placeholder{

color:#eee;

}

.input-box input:focus{

border-color:#ff9800;

background:rgba(255,255,255,.18);

}

/* Options */

.options{

display:flex;

justify-content:space-between;

align-items:center;

font-size:13px;

margin-bottom:25px;

}

.options label{

display:flex;

align-items:center;

gap:5px;

}

.options a{

color:#fff;

text-decoration:none;

}

.options a:hover{

text-decoration:underline;

}

/* Button */

button{

width:100%;

padding:15px;

border:none;

border-radius:50px;

background:#ff6b00;

color:white;

font-size:17px;

font-weight:600;

cursor:pointer;

transition:.3s;

}

button:hover{

background:#ff8c1a;

transform:translateY(-2px);

box-shadow:0 5px 15px rgba(255,107,0,.5);

}

/* Register */

.register{

margin-top:20px;

text-align:center;

font-size:14px;

}

.register a{

color:#FFD54F;

text-decoration:none;

font-weight:600;

}

.register a:hover{

text-decoration:underline;

}

/* Message */

.message{

margin-top:20px;

padding:12px;

border-radius:10px;

background:#ffdddd;

color:#b30000;

text-align:center;

}

/* Responsive */

@media(max-width:450px){

.form-container{

width:90%;

padding:30px;

}

}

</style>

</head>

<body>

<div class="form-container">


<h2>Food Login</h2>

<form method="POST">

<div class="input-box">
<input
type="text"
name="username"
placeholder="Enter Username"
required
autocomplete="off">
</div>

<div class="input-box">
<input
type="password"
name="password"
placeholder="Enter Password"
required
autocomplete="off">
</div>

<div class="options">

<label>
<input type="checkbox">
Remember Me
</label>

<a href="forgotpassword.php">Forgot Password?</a>

</div>

<button type="submit">
Login
</button>

</form>

<div class="register">
Don't have an account?
<a href="register.php">Register</a>
</div>

<?php if(!empty($message)){ ?>
<div class="message">
<?php echo htmlspecialchars($message); ?>
</div>
<?php } ?>

</div>

</body>
</html>