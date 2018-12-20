<link rel="stylesheet" type="text/css" href="../semantic/dist/semantic.css">
<style>
    table, th, td {
        border: 1px solid black;
    }
    h1 {
        text-align:center;
        color:red;
    }
</style>
<h1>Evaluation report</h1>
<form method="post">
    <select class="ui selectable" title="Select  Department" name="depa">
        <option value="1"> Dep 1</option>
        <option value="2"> Dep 2</option>
        <option value="3"> Dep 3</option>
        <option value="4"> Dep 4</option>
    </select>
    <input type="submit" name="sub" class="ui pink button">
</form>
<a href='../add_worker.php'>Add Worker</a>
<?php
require_once "../Includs/db.php";
require_once "../Includs/checkLogin.php";
require_once "../Includs/checkAdmin.php";
$prod=new MangeProductsByWorker();
$prod->ShowState1();
if (isset($_POST['accept']))
{
    $prod->FinalSave($_POST['id'],$_POST['rate'],$_POST['notes'],$_POST['order']);
}
if (isset($_POST['del']))
{
    $prod->Delete($_POST['id']);
}
if (isset($_POST['sub']))
{
    $prod->ShowByDep($_POST['depa']);
}
$db=new DataBase();
$res=$db->getData($db->PDO_Connect(),"SELECT `username` FROM `users` where `role` =0");
echo "<form method='post' class='ui form'><select name='user' class='ui selectable'>";
foreach ($res as $value)
{
    $username=$value['username'];
    echo "<option value='$username'>$username</option>";
}
echo "</select><select name='rateget' class='ui selectable'><option value='E.X'>E.X</option><option value='V.G'>V.G</option> </select><br><input type='submit' value='Show By Worker' class='ui olive button' name='bywork'><input type='submit' value='ShowByRate' class='ui olive button' name='showrate'></form>";
if (isset($_POST['bywork']))
{
        $prod->ShowByWorker($_POST['user']);
}
if (isset($_POST['showrate']))
{
    $prod->ShowByRate($_POST['rateget']);
}