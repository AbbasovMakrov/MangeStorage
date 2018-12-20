<?php
if (!isset($_SESSION))
{
    session_start();
}
require_once "Classes.php";
$check=new CheckWorker($_SESSION['role']);