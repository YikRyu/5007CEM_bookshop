<script type="text/javascript">
    function editAlert(edit_status){
        switch(edit_status){
            case 1:
                alert("Payment information successfully edited! \nRedirecting back to user profile...");
                window.location = "user.php";
                break;
            case 2:
                alert("Unable to edit user info!");
                break;
            case 3:
                alert("Please do not left any field blank!!!!");
                break;
        }
    }
</script>

<?php
    error_reporting(E_ALL);  //give warning if session cannot start
    session_start(); //starting the session

    //fetch user ID for display user data
    if(isset($_SESSION['userID'])){
        $userID = $_SESSION['userID'];
    }

    //for submit edit payment info button
    if(isset($_POST['submitted'])){
        //getting the data and sanitizing
        $fullname = trim(htmlspecialchars(strip_tags($_POST['fullname'])));
        $cardNo = trim(htmlspecialchars(strip_tags($_POST['cardNo'])));
        $bankName = trim(htmlspecialchars(strip_tags($_POST['bankName'])));
        $address = trim(htmlspecialchars(strip_tags($_POST['address'])));

        //connect to db
        $dbServername = "localhost";
        $dbUsername = "bookshop";
        $dbPassword = "webDevBookshop";
        $dbName = "bookshop";
        $conn = mysqli_connect($dbServername ,$dbUsername, $dbPassword, $dbName);

        //if any of the field blank
        if($cardNo == null || $bankName == null || $address == null){
            echo "<script type=\"text/javascript\">editAlert(3);</script>";
        }
        else{
            $query = "UPDATE
                        user
                    SET
                        fullname = '$fullname',
                        cardNo = '$cardNo',
                        bankName = '$bankName',
                        address = '$address'
                    WHERE
                        userID = $userID";
            $retval = mysqli_query($conn,$query);
            //if cannot update
            if(!$retval){
                echo "<script type=\"text/javascript\">editAlert(2);</script>";
            }
            else{ //update success
                echo "<script type=\"text/javascript\">editAlert(1);</script>";
            }
        }

        mysqli_close($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookshop  - Edit Payment</title>
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
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
        <form action="edit_payment.php" method="POST">
        <?php
            //connect to db api for data fetching, pass the assigned data into link for api function
            $api_url = 'http://localhost/bookshop/db_api/user/read_one.php?userID='. urlencode($userID) ;

            //fetched the data, make it into php array from json array
            $user_details = file_get_contents($api_url);
            $user_array = json_decode($user_details, true);

            //store the data in variables
            $user_fullname = $user_array['fullname'];
            $user_cardNo = $user_array['cardNo'];
            $user_bankName = $user_array['bankName'];
            $user_address = $user_array['address'];

            //display form data 
            echo "<h1>Edit Payment Info</h1>";
            echo "<div><p>Name:&emsp;<input class=\"login-regis\" type=\"text\" name=\"fullname\" id=\"fullname\" value=\"$user_fullname\"/></p></div>";
            echo "<div><p>Card No.:&emsp;<input class=\"login-regis\" type=\"text\" name=\"cardNo\" id=\"cardNo\" value=\"$user_cardNo\"/></p></div>";
            echo "<div><p>Bank Name:&emsp;<input class=\"login-regis\" type=\"text\" name=\"bankName\" id=\"bankName\" value=\"$user_bankName\"/></p></div>";
            echo "<div>";
            echo "<p>Address: </p>";
            echo "<div class=\"edit-address\"><textarea id=\"address\" name=\"address\" rows=\"5\" cols=\"120\"/>$user_address</textarea></div>";
            echo "</div>";
        ?>
            <div class="edit-payment">
                <input type="submit" name="submitted" class="button" id="btnEdit" value="Edit"/>
            </div>
        </form>
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