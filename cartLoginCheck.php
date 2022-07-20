<script type="text/javascript">
    function cartLoginAlert(){
        alert("Please login first!");
        window.location = "login.php"; // Redirecting to other page.
    }
</script>

<?php
    function cartLogin(){
        error_reporting(E_ALL);  //give warning if session cannot start
        session_start(); //starting the session

        if(!isset($_SESSION['userID'])){
            echo '<script type="text/javascript">cartLoginAlert()</script>';
        }
        else{
            ob_start();
            header('Location:cart.php');
            ob_end_flush();
            die();
        }
    }

    cartLogin();
?>