<script type="text/javascript">
    function cartAlerts(cart){
        switch(cart){
            case 1:
                alert("Please login first!");
                window.location = "login.php"; // Redirecting to other page.
                break;
            case 2:
                alert("There is nothing in the cart, please browse the store and add some books for purchasing~");
                break;
        }
        
    }
</script>

<?php
    error_reporting(E_ALL);  //give warning if session cannot start
    session_start(); //starting the session

    //redirect user to login if found no user logged in
    if(!isset($_SESSION['userID'])){
        echo '<script type="text/javascript">cartAlerts(1)</script>';
    }

    if(isset($_POST['submitted'])){
        $total = $_POST['total'];

        //redirect user to login if found no user logged in
        if(!isset($_SESSION['userID'])){
            echo '<script type="text/javascript">cartAlerts(1)</script>';
        }
        else if($total == 0.0){
            echo '<script type="text/javascript">cartAlerts(2)</script>';
        }
        else{
            ob_start();
            header('Location:payment.php?total='.$total);
            ob_end_flush();
            die();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookshop  - Shopping Cart</title>
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
    <h1>Shopping Cart</h1>
    <?php //printing each book added into cart
        if(isset($_SESSION['cartArray'])){ //check if session for cart value storing is empty or not
            $cart = array(); //initialize empty cart array
            foreach ($_SESSION['cartArray'] as $key => $value) {
                array_push($cart, $value); //if not assign values to the array
            }
        }
        else{
            $cart = array(); //initialize empty cart array
        }

        //connect to db
        $dbServername = "localhost";
        $dbUsername = "bookshop";
        $dbPassword = "webDevBookshop";
        $dbName = "bookshop";
        $conn = mysqli_connect($dbServername ,$dbUsername, $dbPassword, $dbName);

        //variable for storing total book price
        $total = 0.0;

        if(count($cart)>0){
            //loop printing books added to the cart
            foreach($cart as $value){
                $bookID = $value;
                $query = "SELECT
                            *
                        FROM
                            book 
                        WHERE
                            bookID = $bookID";
                $retval = mysqli_query($conn,$query);

                if(!$retval){
                    echo "<h1>No book result returned!</h1>";
                }
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                    $bookID = $row['bookID'];
                    $bookCover = $row["bookCover"];
                    
                    echo "<div>";
                    echo "<div>";
                    echo "<div class=\"cart\">";
                    echo "<table>";
                    echo "<tr>";
                    echo "<td class=\"listing\">";
                    echo "<div class=\"img-book-table\"><img class=\"book-table\" src=\"data:bookCover/jpeg;base64,".base64_encode( $bookCover )."\"/></div>";
                    echo "</td>";
                    echo "<td>";
                    echo "<ul class=\"book-table\">";
                    echo "<li><a href=\"book_detail.php?bookID=$bookID\">{$row['bookName']}</a></li>";
                    echo "<li><b>{$row['bookAuthor']}</b></li>";
                    echo "</ul>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                    echo "</div>";
                    echo "<div class=\"cart-price\"><h2>RM {$row['bookPrice']}</h2></div>";
                    echo "</div>";
                    echo "</div>";
                    
                    //for total price
                    $total = $total + $row['bookPrice'];
                    }
                } 
                else {
                    echo "<h1>No book result returned!</h1>";
                }
            }
        }
        else{
            echo "<p>There is no book in the cart!</p>";
        }
        
        //close db connection
        mysqli_close($conn);
    ?>

    <!--Total-->
    <form method="POST" action="">
        <div class="payment-content">
            <table class="payment">
                <tr>
                    <td class="payment"><h2 class="payment-total">Total: </h2></td>
                    <td class="payment">
                        <h2 class="payment-price">
                            <?php 
                                echo"RM ".number_format((float)$total, 2, '.', ''); 
                                echo "<input type=\"hidden\" name=\"total\" value=\"$total\"/>";
                            ?>
                        </h2>
                    </td>
                </tr>
            </table>

            <table class="payment">
                <tr>
                    <td>
                        <div class="cart-button">
                            <button type="submit">
                                <input class="button" type="hidden" name="submitted" value="true" />Confirm Purchase
                            </button>
                        </div>
                        
                    </td>
                </tr>
            </table>
        </div>
    </form>
    

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