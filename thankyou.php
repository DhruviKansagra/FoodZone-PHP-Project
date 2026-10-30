<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Order Success - FoodZone</title>


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

}



/* Success Card */

.box{

    width:420px;

    padding:45px 35px;

    text-align:center;

    border-radius:25px;

    background:rgba(255,255,255,.15);

    border:1px solid rgba(255,255,255,.25);

    backdrop-filter:blur(18px);

    -webkit-backdrop-filter:blur(18px);

    box-shadow:0 10px 40px rgba(0,0,0,.35);

    color:white;

}



/* Emoji */

.box h1{

    font-size:45px;

    margin-bottom:20px;

}



/* Text */

.box p{

    font-size:18px;

    margin-bottom:30px;

    color:#eee;

}



/* Button */

.box a{

    display:inline-block;

    padding:14px 35px;

    border-radius:50px;

    background:#ff6b00;

    color:white;

    text-decoration:none;

    font-size:17px;

    font-weight:600;

    transition:.3s;

}



.box a:hover{

    background:#ff9800;

    transform:translateY(-3px);

    box-shadow:0 8px 20px rgba(255,107,0,.5);

}



/* Mobile */

@media(max-width:450px){

    .box{

        width:90%;

        padding:35px 25px;

    }


    .box h1{

        font-size:36px;

    }

}

</style>


</head>


<body>


<div class="box">

    <h1>Thank You!</h1>


    <p>
        Your order has been placed successfully.
        <br>
        We are preparing your delicious food.
    </p>


    <a href="index.php">
        Go to Homepage
    </a>


</div>


</body>
</html>