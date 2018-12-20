<?php
require_once "Classes.php";
if (!isset($_SESSION))
{
    session_start();
}
$check=new CheckLoginMain($_SESSION['id']);