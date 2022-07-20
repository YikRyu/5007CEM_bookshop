<script type="text/javascript">
    function cartAlerts(cart){
        alert("Please login first!");
        window.location = "login.php"; // Redirecting to other page.
    }
</script>

<?php
    error_reporting(E_ALL);  //give warning if session cannot start
    session_start(); //starting the session

    //connect to db
    $dbServername = "localhost";
    $dbUsername = "bookshop";
    $dbPassword = "webDevBookshop";
    $dbName = "bookshop";
    $conn = mysqli_connect($dbServername ,$dbUsername, $dbPassword, $dbName);

    //redirect user to login if found no user logged in
    if(!isset($_SESSION['userID'])){
        echo '<script type="text/javascript">cartAlerts(1)</script>';
    }
    else{
        //fetch user ID for display user data
        $userID = $_SESSION['userID'];
    }

    //check if session for cart value storing is empty or not, for book amount updating
    if(isset($_SESSION['cartArray'])){ 
        $cart = array(); //initialize empty cart array
        foreach ($_SESSION['cartArray'] as $key => $value) {
            array_push($cart, $value); //if not assign values to the array
        }
    }
    else{
        $cart = array(); //initialize empty cart array
    }


    //if edit payment button was clicked
    if(isset($_POST['editPayment'])){
        if(!isset($_SESSION['userID'])){
            echo '<script type="text/javascript">cartAlerts(1)</script>';
        }
        else{
            ob_start();
            header('Location:edit_payment.php');
            ob_end_flush();
            die();
        }
    }

    //if confirm purchase button was clicked(did not get disabled)
    if(isset($_POST['purchase'])){
        if(count($cart)>0){
            //loop to update book amount according to book ID stored
            foreach($cart as $value){
                $bookID = $value;

                $update_book_amount = "UPDATE book SET bookAmount = (bookAmount-1) WHERE bookID = $bookID";
                $update_book_retval = mysqli_query($conn,$update_book_amount);
                if(!$update_book_retval){
                    echo "<script type=\"text/javascript\">alert(\"Something went wrong....\");</script>";
                }
            }
        }

        //redirect to thank you page
        ob_start();
        header('Location:post_payment.php');
        ob_end_flush();
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookshop  - Payment</title>
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
    <div class="content-wrapper-wider">
        <h1 class="payment">Payment and Shipping</h1>
        <p class="warning">***Please check your payment and shipping information before confirming!!</p>
        <p class="warning">***Please do note that purchase button will be disabled if any of these fields are empty!(card number, bank name, address)</p>
        <div>
            <?php
                //fetching total price from link param
                if (isset($_GET['total'])) {
                    $total = $_GET['total'];
                }

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
        </div>

        <!--Total and confirmation/ edit-->
        <form method="POST" action="">
            <div class="payment-content">
                <table class="payment">
                    <tr>
                        <td class="payment"><h2 class="payment-total">Total: </h2></td>
                        <td class="payment">
                            <h2 class="payment-price">
                                <?php echo"RM ".number_format((float)$total, 2, '.', ''); ?>
                            </h2>
                        </td>
                    </tr>
                </table>

                <table class="payment-button">
                    <tr>
                        <td>
                            <div class="payment-button">
                                <input class="button" name="editPayment" type="submit" id="btnEditPayment" value="Edit Payment Info"/>
                            </div>
                        </td>

                        <td>
                            <form method="POST" action="">
                                <div class="payment-button">
                                    <?php
                                        //disable confirm purchase button if any of these fields are blank
                                        if ($user_cardNo==null || $user_bankName==null || $user_address==null){
                                            echo "<button class=\"button-disabled\" type=\"button\" disabled>";
                                            echo "<input name=\"purchase-disabled\" type=\"hidden\" id=\"btnEditPayment\"/>";
                                            echo "Confirm Purchase";
                                            echo "</button>";
                                        }
                                        //display working confirm purchase button
                                        else{
                                            echo "<button type=\"submit\">";
                                            echo "<input name=\"purchase\" type=\"hidden\" id=\"btnEditPayment\" value=\"true\"/>";
                                            echo "Confirm Purchase";
                                            echo "</button>";
                                        } 
                                    ?>
                                </div>
                            </form>
                        </td>
                    </tr>
                </table>
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