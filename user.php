<?php
    error_reporting(E_ALL);  //give warning if session cannot start
    session_start(); //starting the session

    //fetch user ID for display user data
    if(isset($_SESSION['userID'])){
        $userID = $_SESSION['userID'];
    }

    //for edit payment info button
    if(isset($_POST['submitted'])){
        ob_start();
        header('Location:edit_payment.php');
        ob_end_flush();
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookshop  - User Profile</title>
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.1.min.js"></script>
</head>

<body class="custom">
    <!--Header-->
    <div class="header-wrapper">
        <div class="header-logo">
            <a href="index.php"><img class="header-logo" src="img/logo.png" alt="bookshop_logo" /></a>
        </div>

        <div class="header">
        <ul class="header">
                <li class="header"><img class="search" src="img/search.png" alt="search" /></li>
                <li class="header">
                    <!--form for the input to be able to function and pass data-->
                    <form method="GET" id="search_form" action="search.php">
                        <input class="search" type="text" name="search" id="search" placeholder="Enter keywords here to search" />
                    </form>
                </li>
                <li class="header"><a href="userProfileCheck.php"><img src="img/user.png" class="header" alt="user_profile" /></a></li>
                <li class="header"><a href="cartLoginCheck.php"><img src="img/cart.png" class="header" alt="shopping_cart" /></a> </li>
            </ul>
        </div>
    </div>

    <!--Navigation-->
    <div class="navbar">
        <ul id="nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="listing.php?genreID=1">Recent Populars</a></li>
            <li><a href="listing.php?genreID=2">Newly Arrived</a></li>
            <li>
                <a href="#">Books According to Genre</a>
                <ul>
                <li><a href="listing.php?genreID=3">Fiction</a></li>
                    <li><a href="listing.php?genreID=4">Non-Fiction</a></li>
                    <li><a href="listing.php?genreID=5">Textbook</a></li>
                    <li><a href="listing.php?genreID=5">Teenager</a></li>
                </ul>
            </li>
        </ul>
    </div>

    &nbsp; <!--spacing-->
    <!--Content-->
    <div class="content-wrapper">
        <h1>User Profile</h1>
        <br/> <!--spacing-->
        <?php
            //connect to db api for data fetching, pass the assigned data into link for api function
            $api_url = 'http://localhost/bookshop/db_api/user/read_one.php?userID='. urlencode($userID) ;

            //fetched the data, make it into php array from json array
            $user_details = file_get_contents($api_url);
            $user_array = json_decode($user_details, true);

            //store the data in variables
            $user_username = $user_array['username'];
            $user_fullname = $user_array['fullname'];
            $user_cardNo = $user_array['cardNo'];
            $user_bankName = $user_array['bankName'];
            $user_address = $user_array['address'];

            //display user data
            echo "<p><b>Username:&emsp;</b>$user_username</span></p>";
            echo "<p><b>Name:&emsp;</b>$user_fullname</p>";
            echo "<p><b>CardNo:&emsp;</b>$user_cardNo</p>";
            echo "<p><b>Bank Name:&emsp;</b>$user_bankName</p>";
            echo "<p><b>Address:&emsp;</b>$user_address</p>";
        ?>

        <div class="login-regis-button">
            <form method="POST" action="user.php">
            <button type="submit">
                <input type="hidden" name="submitted" value="true" />Edit Payment Info
            </button>
            </form>
        </div>
    </div>

    &nbsp; <!--spacing-->
    <!--Footer-->
    <footer>
        <div class="header-logo">
            <a href="index.php"><img class="footer-logo" src="img/logo.png" alt="bookshop_logo" /></a>
        </div>

        <div class="footer-payment">
            <h3>Payments Accepted</h3>
            <table>
                <tr>
                    <td><img class="footer-payment" src="img/visa.png" alt="visa" /></td>
                    <td><img class="footer-payment" src="img/debit.png" alt="debit" /></td>
                    <td><img class="footer-payment" src="img/master.png" alt="master" /></td>
                </tr>
            </table>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>