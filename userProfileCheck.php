<script type="text/javascript">
    function userProfileLoginAlert(){
        alert("Please login first!");
        window.location = "login.php"; // Redirecting to other page.
    }
</script>

<?php
    function userProfileLogin(){
        error_reporting(E_ALL);  //give warning if session cannot start
        session_start(); //starting the session

        if(!isset($_SESSION['userID'])){
            echo '<script type="text/javascript">userProfileLoginAlert()</script>';
        }
        else{
            ob_start();
            header('Location:user.php');
            ob_end_flush();
            die();
        }
    }

    userProfileLogin();
?>