<?php
if (!isset($_SESSION))
{
    session_start();
    session_regenerate_id();
}
require_once "Classes.php";
$check=new CheckLogin($_SESSION['id']);