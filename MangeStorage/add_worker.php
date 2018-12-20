<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.css">
    <script src="semantic/dist/semantic.js"></script>
</head>
<body>
<form method="post" class="ui form bottom aligned">
    <input class="ui input" name="user" type="text" placeholder="Username"><br>
    <input class="ui input" name="pass" type="password" placeholder="Password"><br>
   <select name="role" class="ui selectable"  title="Role">
       <option value="0">Worker</option>
       <option value="1">Admin</option>
       <option value="2">Vistor</option>
   </select><br>
    <input class="ui input" name="dep" type="number" placeholder="departemnt"><br>
    <input class="ui primary button" type="submit" name="sub" placeholder="Add">
</form>




<?php
require_once "Includs/Classes.php";
require_once "Includs/checkLoginMain.php";
require_once "Includs/checkAdmin.php";
if (isset($_POST['sub']))
{
    $set=new AddPerson($_POST['user'],$_POST['pass'],$_POST['role'],$_POST['dep']);
}
?>
</body>
</html>