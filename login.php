<!--script file for login alerts and page redirecting-->
<script type="text/javascript">
        function loginAlerts(login_status) {
            switch(login_status){
                case 1: //credentials correct
                    alert("Login successfully");
                    window.location = "index.html"; // Redirecting to other page.
                    break;
                
                case 2://one of the credentials wrong
                    alert("Wrong email or password, please try again!");
                    break;

                case 3://one of field left empty
                    alert("Please enter both username and password!");
                    break;
                case 4://if no username was successfully searched in the table
                    alert("Username does not exist!");
                    break;
            }
        }
</script>

<?php
    error_reporting(E_ALL);  //give warning if session cannot start

    //if user is already logged in, redirect them to home page
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        header("location: index.php");
        exit;
    }

    //run this code when the login button is clicked
    if(isset($_POST['submitted'])){
        //assign inserted data into variables
        $username = $_POST['username'];
        $password = $_POST['password'];

        //connect to db api for data fetching, pass the assigned data into link for api function
        $api_url = 'http://localhost/bookshop/db_api/user/search.php?username='. urlencode($username) ;

        //fetched the data, make it into php array from json array
        $login_details = file_get_contents($api_url);
        $login_array = json_decode($login_details, true);

        if(!isset($login_array)){
            //calling the javascript function for alert box printing and page redirecting
            echo '<script type="text/javascript">loginAlerts(4)</script>';
        }
        else{
            //store the data in variables
            $login_userID = $login_array['records'][0]['userID'];
            $login_username = $login_array['records'][0]['username'];
            $login_password = $login_array['records'][0]['password'];
        
            //if there's any one of them empty
            if(!isset($username) || !isset($password)){
                //calling the javascript function for alert box printing and page redirecting
                echo '<script type="text/javascript">loginAlerts(3)</script>';
            }
            //validating credentials and login or prompt error messages if no empty data
            else if($username==$login_username && $password==$login_password){
                //telling the system that the user is entitled to be logged in
                session_start(); //start the session
                $_SESSION['userID'] = $login_userID;
                $_SESSION['loggedin'] = true;

                //calling the javascript function for alert box printing and page redirecting
                echo '<script type="text/javascript">loginAlerts(1)</script>';
            }
            else{
                //display error message if email and password do not match data in the database
                echo '<script type="text/javascript">loginAlerts(2)</script>';
            }  
        }

        
         
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

</head>

<body class="custom">
    <!--Header-->
    <div class="header-wrapper">
        <div class="header-logo">
            <a href="index.html"><img class="header-logo" src="img/logo.png" alt="bookshop_logo" /></a>
        </div>

        <div class="header">
            <ul class="header">
                <li class="header"><img class="search" src="img/search.png" alt="search" /></li>
                <li class="header">
                    <!--form for the input to be able to function and pass data-->
                    <form method="GET" action="listing.html">
                        <input class="search" type="text" name="search" id="search" placeholder="Enter keywords here to search" />
                    </form>
                </li>
                <li class="header"><a href="user.html"><img src="img/user.png" class="header" alt="user_profile" /></a></li>
                <li class="header"><a href="cart.html"><img src="img/cart.png" class="header" alt="shopping_cart" /></a> </li>
            </ul>
        </div>
    </div>

    <!--Navigation-->
    <div class="navbar">
        <ul id="nav">
            <li><a href="index.html">Home</a></li>
            <li><a href="popular.html">Recent Populars</a></li>
            <li><a href="new.html">Newly Arrived</a></li>
            <li>
                <a href="#">Books According to Genre</a>
                <ul>
                    <li><a href="listing.html">Fiction</a></li>
                    <li><a href="listing.html">Non-Fiction</a></li>
                    <li><a href="listing.html">Textbook</a></li>
                    <li><a href="listing.html">Kids</a></li>
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
            <p>New here? <a href="register.html">Sign up now!</a></p>
        </form>
    </div>

    &nbsp; <!--spacing-->
    <!--Footer-->
    <footer>
        <div class="header-logo">
            <a href="index.html"><img class="footer-logo" src="img/logo.png" alt="bookshop_logo" /></a>
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