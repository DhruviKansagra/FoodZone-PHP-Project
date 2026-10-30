<?php
$conn = new mysqli("localhost", "root", "", "food");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $conn->real_escape_string($_POST['username']);
    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $city = $conn->real_escape_string($_POST['city']);
    $pincode = $conn->real_escape_string($_POST['pincode']);
    $address = $conn->real_escape_string($_POST['address']);

    if ($password_raw !== $confirm_password) {
        $message = "❌ Passwords do not match!";
    } else {
        $password = password_hash($password_raw, PASSWORD_BCRYPT);

        $check = $conn->query("SELECT * FROM user WHERE username = '$username'");
        if ($check->num_rows > 0) {
            $message = "❌ Username already exists!";
        } else {
            $sql = "INSERT INTO user (username, password, email_id, mobile_no, city, pincode, address) 
                    VALUES ('$username', '$password', '$email', '$mobile', '$city', '$pincode', '$address')";
            if ($conn->query($sql) === TRUE) {
                $message = "✅ Registration successful! <a href='login.php'>Login Now</a>";
            } else {
                $message = "❌ Error: " . $conn->error;
            }
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Food Registration</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{

display:flex;
justify-content:center;
align-items:center;
min-height:100vh;

background:
linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),
url("images/food1.jpg") center/cover no-repeat;

padding:30px;

}

.form-container{

width:450px;
padding:35px;

background:rgba(255,255,255,.15);
backdrop-filter:blur(20px);
-webkit-backdrop-filter:blur(20px);

border:1px solid rgba(255,255,255,.25);

border-radius:20px;

box-shadow:0 10px 35px rgba(0,0,0,.3);

color:white;

}

.logo{

font-size:50px;
text-align:center;
margin-bottom:10px;

}

h2{

text-align:center;
margin-bottom:25px;
font-size:30px;

}

.home-link{

display:inline-block;
margin-bottom:15px;
color:white;
text-decoration:none;
font-weight:600;

}

.home-link:hover{

color:#FFD54F;

}

input,
textarea{

width:100%;
padding:14px 18px;

margin-bottom:15px;

border-radius:40px;

border:1px solid rgba(255,255,255,.35);

background:rgba(255,255,255,.12);

outline:none;

color:white;

font-size:15px;

}

textarea{

border-radius:18px;
resize:none;
height:90px;

}

input::placeholder,
textarea::placeholder{

color:#eee;

}

input:focus,
textarea:focus{

border-color:#ff9800;
background:rgba(255,255,255,.18);

}

.error-message{

display:none;
color:#FFD54F;
font-size:13px;
margin-top:-10px;
margin-bottom:10px;

}

.btn-group{

display:flex;
justify-content:space-between;
gap:15px;

}

button{

flex:1;

padding:14px;

border:none;

border-radius:40px;

cursor:pointer;

font-size:16px;

font-weight:600;

transition:.3s;

}

.submit-btn{

background:#ff6b00;
color:white;

}

.submit-btn:hover{

background:#ff8c1a;
transform:translateY(-2px);

}

.reset-btn{

background:#555;
color:white;

}

.reset-btn:hover{

background:#777;

}

.message{

margin-top:20px;
padding:12px;
text-align:center;

border-radius:10px;

background:#ffdddd;
color:#b00020;

}

.success{

background:#d4edda;
color:#155724;

}

.login{

text-align:center;
margin-top:20px;

}

.login a{

color:#FFD54F;
text-decoration:none;
font-weight:600;

}

.login a:hover{

text-decoration:underline;

}

@media(max-width:500px){

.form-container{

width:100%;

}

}

</style>

</head>

<body>

<div class="form-container">

<h2>Create Account</h2>

<form method="POST" autocomplete="off" onsubmit="return validateForm()">

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<input type="password" name="confirm_password" placeholder="Confirm Password" required>

<input type="email" name="email" placeholder="Email Address">

<input type="text" id="mobile" name="mobile" placeholder="Mobile Number" maxlength="10" required>

<div id="mobile-error" class="error-message">
Mobile number must contain 10 digits.
</div>


<input type="text" name="city" placeholder="City">
<input type="text" id="pincode" name="pincode" placeholder="Pincode" maxlength="6" required>

<div id="pincode-error" class="error-message">
Pincode must contain 6 digits.
</div>


<textarea name="address" placeholder="Address"></textarea>

<div class="btn-group">

<button type="submit" name="submit" class="submit-btn">
Register
</button>

<button type="reset" class="reset-btn">
Reset
</button>

</div>

</form>

<div class="login">
Already have an account?
<a href="login.php">Login</a>
</div>

<?php if(!empty($message)){ ?>
<div class="message <?= strpos($message,'✅')!==false ? 'success' : '' ?>">
<?= $message ?>
</div>
<?php } ?>

</div>

<script>

function validateDigits(input,maxLength,errorId){

input.value=input.value.replace(/\D/g,'');

if(input.value.length!=maxLength){

document.getElementById(errorId).style.display='block';

}else{

document.getElementById(errorId).style.display='none';

}

}

document.getElementById("mobile").addEventListener("input",function(){

validateDigits(this,10,"mobile-error");

});

document.getElementById("pincode").addEventListener("input",function(){

validateDigits(this,6,"pincode-error");

});

function validateForm(){

let valid=true;

if(document.getElementById("mobile").value.length!=10){

document.getElementById("mobile-error").style.display="block";
valid=false;

}

if(document.getElementById("pincode").value.length!=6){

document.getElementById("pincode-error").style.display="block";
valid=false;

}

return valid;

}

</script>

</body>
</html>