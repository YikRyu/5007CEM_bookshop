<!--script file for register alerts and page redirecting-->
<script type="text/javascript">
    function registerAlerts(register_status) {
        switch(register_status){
            case 1: //successfully registered
                alert("Successfully registered! Redirecting to login page...");
                window.location = "login.php"; //redirect to login page to login
                break;
            
            case 2://username used
                alert("Username has been used, please try another one!");
                return false;
                break;

            case 3://one of field left empty
                alert("Please enter all the required fields!");
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

    //database connection
    $dbServername = "localhost";
    $dbUsername = "bookshop";
    $dbPassword = "webDevBookshop";
    $dbName = "bookshop";
    $conn = mysqli_connect($dbServername ,$dbUsername,$dbPassword,$dbName);

    if(isset($_POST['submitted'])){
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $fullname = trim($_POST['fullname']);
    
        //checking if user already exists
        $checking_user_existence = "SELECT * FROM user WHERE username='$username'";
        $result_register = mysqli_query($conn, $checking_user_existence) or die(mysqli_error($conn));
        if(mysqli_num_rows($result_register)>0){
            while($row = mysqli_fetch_assoc($result_register)){
                $checking_username_existence = $row['username'];
            }
        }
        
        //string checking with case sensitive, if user already exists
        if(strcmp($username,$checking_username_existence)==0){
            echo '<script type="text/javascript">registerAlerts(2)</script>';
        }
        else{
            //if user does not exist in database insert the data into the db
            $query = "INSERT INTO user (username, password, fullname)
                      VALUES " . "('" .$username. "','" .$hashed_password. "','" .$fullname. "')";
    
            $conn->query($query);

            //call js function telling the user successfully registered and redirect to login
            echo '<script type="text/javascript">registerAlerts(1)</script>'; 
        }
    
        $conn->close();
    
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookshop  - Register Account</title>
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
    <div class="login-regis">
        <form name="register" method="POST">
            <h1 class="login-regis">Register Account</h1>
            <div>
                <p class="login-regis">Username:</p>
                <input class="login-regis" type="text" name="username" id="username" placeholder="Username" required />
            </div>

            <div>
                <p class="login-regis">Password:</p>
                <input class="login-regis" type="password" name="password" id="password" placeholder="Password" required />
            </div>

            <div>
                <p class="login-regis">Name:</p>
                <input class="login-regis" type="text" name="fullname" id="fullname" placeholder="Your Full Name" required />
            </div>

            <div class="login-regis-button">
                <button type="submit">
                    <input type="hidden" name="submitted" value="true"/>
                    Register
                </button>
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
</body>

</html>