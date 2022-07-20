<?php
    error_reporting(E_ALL);  //give warning if session cannot start
    session_start(); //starting the session

    //connect to db
    $dbServername = "localhost";
    $dbUsername = "bookshop";
    $dbPassword = "webDevBookshop";
    $dbName = "bookshop";
    $conn = mysqli_connect($dbServername ,$dbUsername,$dbPassword,$dbName);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookshop  - Home Page</title>
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
            <li><a class="active" href="index.php">Home</a></li>
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
    <h1>Recent Populars</h1>
    <p>See what are popular recently</p>
    <?php
        $query = "SELECT
                        *
                    FROM
                        book 
                    WHERE
                        bookGenre = 1 
                    ORDER BY
                        bookID ASC";
        $retval = mysqli_query($conn,$query);

        if(!$retval){
            echo "<p>No book to display!</p>";
        }
        
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        
        echo "<table class=\"book-table\">";
        echo "<tr>";
        //print book list
        if (mysqli_num_rows($result) > 0) {
            for($int=1; $int<=4; $int++){
            $row = mysqli_fetch_assoc($result);
            $bookID = $row['bookID'];
            $bookAmount = $row['bookAmount'];
            $bookCover = $row["bookCover"];

            
            echo "<td class=\"book-table\">";
            echo "<ul class=\"book-table\">";
            echo "<li class=\"book-table\">";
            echo "<div class=\"img-book-table\">";
            echo "<img class=\"book-table\" src=\"data:bookCover/jpeg;base64,".base64_encode($bookCover)."\"/>";
            echo "</div>";
            echo "</li>";
            echo "<li><a href=\"book_detail.php?bookID=$bookID\">{$row['bookName']}</a></li>";
            echo "<li>{$row['bookAuthor']}</li>";
            echo "<li><b>RM {$row['bookPrice']}</b></li>";
            echo "</ul>";
            echo "</td>";    
            }
            echo "</tr>";
            echo "</table>";
        } 
        else {
            echo "<p>No book to display!</p>";
        }
    ?>

    <h1>New Arrivals</h1>
    <p>New books available!</p>
    <?php
        $query = "SELECT
                        *
                    FROM
                        book 
                    WHERE
                        bookGenre = 2 
                    ORDER BY
                        bookID ASC";
        $retval = mysqli_query($conn,$query);

        if(!$retval){
            echo "<p>No book to display!</p>";
        }
        
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        
        echo "<table class=\"book-table\">";
        echo "<tr>";
        //print book list
        if (mysqli_num_rows($result) > 0) {
            for($int=1; $int<=4; $int++){
            $row = mysqli_fetch_assoc($result);
            $bookID = $row['bookID'];
            $bookAmount = $row['bookAmount'];
            $bookCover = $row["bookCover"];

            
            echo "<td class=\"book-table\">";
            echo "<ul class=\"book-table\">";
            echo "<li class=\"book-table\">";
            echo "<div class=\"img-book-table\">";
            echo "<img class=\"book-table\" src=\"data:bookCover/jpeg;base64,".base64_encode($bookCover)."\"/>";
            echo "</div>";
            echo "</li>";
            echo "<li><a href=\"book_detail.php?bookID=$bookID\">{$row['bookName']}</a></li>";
            echo "<li>{$row['bookAuthor']}</li>";
            echo "<li><b>RM {$row['bookPrice']}</b></li>";
            echo "</ul>";
            echo "</td>";    
            }
            echo "</tr>";
            echo "</table>";
        } 
        else {
            echo "<p>No book to display!</p>";
        }
    ?>

    <h1>Textbook</h1>
    <p>Textbooks available for hardworkers!</p>
    <?php
        $query = "SELECT
                        *
                    FROM
                        book 
                    WHERE
                        bookGenre = 5 
                    ORDER BY
                        bookID ASC";
        $retval = mysqli_query($conn,$query);

        if(!$retval){
            echo "<p>No book to display!</p>";
        }
        
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        
        echo "<table class=\"book-table\">";
        echo "<tr>";
        //print book list
        if(mysqli_num_rows($result) > 0) {
            for($int=1; $int<=4; $int++){
            $row = mysqli_fetch_assoc($result);
            $bookID = $row['bookID'];
            $bookAmount = $row['bookAmount'];
            $bookCover = $row["bookCover"];

            
            echo "<td class=\"book-table\">";
            echo "<ul class=\"book-table\">";
            echo "<li class=\"book-table\">";
            echo "<div class=\"img-book-table\">";
            echo "<img class=\"book-table\" src=\"data:bookCover/jpeg;base64,".base64_encode($bookCover)."\"/>";
            echo "</div>";
            echo "</li>";
            echo "<li><a href=\"book_detail.php?bookID=$bookID\">{$row['bookName']}</a></li>";
            echo "<li>{$row['bookAuthor']}</li>";
            echo "<li><b>RM {$row['bookPrice']}</b></li>";
            echo "</ul>";
            echo "</td>";    
            }
            echo "</tr>";
            echo "</table>";
        } 
        else {
            echo "<p>No book to display!</p>";
        }
        

        //close db connection
        mysqli_close($conn);
    ?>

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