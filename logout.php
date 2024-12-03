<?php
if (!isset($_SESSION['loggedin'])) {
    session_start();
}
session_destroy();
header("refresh:0;url=index.php");