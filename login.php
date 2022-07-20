<!--script file for login alerts and page redirecting-->
<script type="text/javascript">
    function loginAlerts(login_status) {
        switch(login_status){
            case 1: //credentials correct
                alert("Login successfully");
                window.location = "index.php"; // Redirecting to other page.
                break;
            
            case 2://one of the credentials wrong
                alert("Wrong email or password, please try again!");
                break;

            case 3://one of field left empty
                alert("Please enter both username and password!");
                break;
        }
    }

    function loginAlert(){
        window.location = "index.php"; // Redirecting to other page.
    }
</script>

<?php
    error_reporting(E_ALL);  //give warning if session cannot start
    session_start();

    if(isset($_SESSION['userID'])){ //
        echo '<script type="text/javascript">loginAlert()</script>';
    }

    //run this code when the login button is clicked
    if(isset($_POST['submitted'])){
        //assign inserted data into variables
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        //if there's any one of them empty
        if($username == null || $password == null){
            //calling the javascript function for alert box printing and page redirecting
            echo '<script type="text/javascript">loginAlerts(3)</script>';
        }
        //validating credentials and login or prompt error messages if no empty data
        else{
            //connect to db
            $dbServername = "localhost";
            $dbUsername = "bookshop";
            $dbPassword = "webDevBookshop";
            $dbName = "bookshop";
            $conn = mysqli_connect($dbServername ,$dbUsername, $dbPassword, $dbName);

            $query = "SELECT
                        userID, username, password
                    FROM
                        user 
                    WHERE
                        username = '$username'";
            $result_login = mysqli_query($conn, $query) or die(mysqli_error($conn));
            if(mysqli_num_rows($result_login)>0){
                while($row = mysqli_fetch_assoc($result_login)){
                    $userID = $row['userID'];
                    $check_username = $row['username'];
                    $hashed_password = $row['password'];   
                }
            }

            if(strcmp($username,$check_username)==0 && password_verify($password,$hashed_password)){
                $userID = $userID;
                //telling the system that the user is entitled to be logged in
                $_SESSION['userID'] = $userID; //store user id in session
                $_SESSION['loggedin'] = true;

                //display error message if username and password do not match data in the database
                echo '<script type="text/javascript">loginAlerts(1)</script>';
            }
            else{
                //display error message if username and password do not match data in the database
                echo '<script type="text/javascript">loginAlerts(2)</script>';
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
    <title>Bookshop  - Login</title>
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script type="text/javascript" src="jquery-1.8.1.min.js"></script>
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
    <div class="login-regis">
        <form name="login" method="POST" action="login.php">
            <h1 class="login-regis">Account Login</h1>
            <div>
                <p class="login-regis">Username:</p>
                <input class="login-regis" type="text" name="username" id="username" placeholder="Username" required />
            </div>

            <div>
                <p class="login-regis">Password:</p>
                <input class="login-regis" type="password" name="password" id="password" placeholder="Password" required />
            </div>

            <div class="login-regis-button">
                <button type="submit">
                    <input type="hidden" name="submitted" value="true"/>
                    Login
                </button>
            </div>
            <br /> <!--spacing-->
            <p>New here? <a href="register.php">Sign up now!</a></p>
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