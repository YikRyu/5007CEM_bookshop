<script type="text/javascript">
    function cartAlerts(cart){
        switch(cart){
                case 1: //user did not login
                    alert("Please login first!");
                    window.location = "login.php"; // Redirecting to other page.
                    break;
                
                case 2://book no stock
                    alert("This book is out of stock....");
                    break;

                case 3://book successfully added to cart
                    alert("Book added to cart!");
                    break;
            }
    }
</script>

<?php
    error_reporting(E_ALL);  //give warning if session cannot start
    session_start(); //starting the session

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
    $conn = mysqli_connect($dbServername ,$dbUsername,$dbPassword,$dbName);

    //cart functions part
    if(isset($_POST['submitted'])){
        $bookAmount = $_POST['bookAmount'];
        $bookID = $_POST['bookID'];

        //redirect user to login if found no user logged in
        if(!isset($_SESSION['userID'])){
            echo '<script type="text/javascript">cartAlerts(1)</script>';
        }
        else{
            if($bookAmount === '0'){
                echo '<script type="text/javascript">cartAlerts(2)</script>';
            }
            else{
                echo '<script type="text/javascript">alert("Book added into shopping cart!")</script>';
                array_push($cart, $bookID); //add new book into cart
                $_SESSION['cartArray'] = $cart;
            }
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookshop  - Book Details</title>
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
        <?php
                if (isset($_GET['bookID'])) {
                    $bookID = $_GET['bookID']; //getting book id from url param

                    $query = "SELECT
                                    *
                            FROM
                                    book 
                            WHERE
                                    bookID = $bookID";
                    $retval = mysqli_query($conn,$query);

                    if(!$retval){
                        echo "<h1>No book info found!</h1>";
                    }
                    
                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                    if (mysqli_num_rows($result)==1) {
                        while($row = mysqli_fetch_assoc($result)) {
                        $bookID = $row['bookID'];
                        $bookAmount = $row['bookAmount'];
                        $bookCover = $row["bookCover"];

                        echo "<h1>Book-Details</h1>";
                        echo "<div>";
                        echo "<table>";
                        echo "<tr>";
                        echo "<td class=\"img-book-detail\">";
                        echo "<div class=\"img-book-detail\">";
                        echo "<img class=\"book-detail\" src=\"data:bookCover/jpeg;base64,".base64_encode($bookCover)."\" alt=\"{$row['bookName']}\"/>";
                        echo "</div>";
                        echo "</td>";    
                        echo "<td>"; 
                        echo "<p class=\"book-detail\"><b>{$row['bookName']}</b></p>"; 
                        echo "<p class=\"book-detail-author\"><b>{$row['bookAuthor']}</b></p>";
                        echo "<p>".nl2br($row['bookDesc'])."</p>";  
                        echo "<p><b>RM {$row['bookPrice']}</b></p>";  
                        echo "<p>Book in stock: $bookAmount</p>";   
                        echo "<div class=\"add-cart\">";
                        echo "<form method=\"POST\" action=\"book_detail.php?bookID=$bookID\">";
                        //for passing book amount and bookID into the cart function
                        echo "<input type=\"hidden\" name=\"bookAmount\" value=\"$bookAmount\" />";
                        echo "<input type=\"hidden\" name=\"bookID\" value=\"$bookID\" />";
                        echo "<input type=\"submit\" name=\"submitted\" class=\"add-cart\" id=\"addCart\" value=\"Add to Cart\"/>";
                        echo "</form>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                        } 
                    } 
                    else {
                        echo "<h1>No book info found!</h1>";
                    }
                }
                else{
                    echo "<h1>No book info found!</h1>";
                }
                
                //close connection
                mysqli_close($conn);
            ?>

        <br/> <!--spacing-->
        <!--line for separating book with review-->
        <hr class="book-detail" noshade />
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