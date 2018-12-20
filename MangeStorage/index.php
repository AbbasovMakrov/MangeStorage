<link rel="stylesheet" type="text/css" href="semantic/dist/semantic.css">
<?php
require_once "Includs/Classes.php";
require_once "Includs/db.php";
require_once "Includs/checkLoginMain.php";
$prod=new MangeProductsByWorker();
$db=new DataBase();
$res=$db->getData($db->PDO_Connect(),"SELECT `username` FROM `users` where `role` =0");
echo "<form method='post' class='ui form'>";
echo "<select  multiple='multiple' class='ui selectable' name='depa'>
        <option value='1'> Dep 1</option>
        <option value='2'> Dep 2</option>
        <option value='3'> Dep 3</option>
        <option value='4'> Dep 4</option>
    </select>
    <input type='submit' name='sub' class='ui pink button'><select name='rateget' class='ui selectable'><option value='E.X'>E.X</option><option value='V.G'>V.G</option> </select><br><input type='submit' value='ShowByRate' class='ui olive button' name='showrate'></form>";
if (isset($_POST['showrate']))
{
    $prod->ShowByRateU($_POST['rateget']);
}
if (isset($_POST['sub']))
{
    $prod->ShowByDepU($_POST['depa']);
}
$prod->ShowStatusToAccept();
if (isset($_POST['accept']))
{
    $prod->AcceptAdminAccept($_POST['id']);
}
if (isset($_POST['del']))
{
    $prod->DeleteU($_POST['id']);
}