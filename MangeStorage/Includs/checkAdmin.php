<?php
if (!isset($_SESSION))
{
    session_start();
}
require_once "Classes.php";
$checkAdmin=new CheckAdmin($_SESSION['role']);