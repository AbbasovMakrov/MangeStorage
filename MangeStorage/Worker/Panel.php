<link rel="stylesheet" type="text/css" href="../semantic/dist/semantic.css">
<style>
    #tbl {
        border: 2px solid black;
    }
</style>
<?php
ob_start();
require_once "../Includs/Classes.php";
require_once "../Includs/checkLogin.php";
require_once "../Includs/CheckWroker.php";
if (!isset($_SESSION))
{
    session_start();
}

  echo "<table id='tbl' class='ui table'>";
    echo "<tr id='tbl' style='background: #fafffb'>
        <th>product</th>
        <th>Product name</th>
        <th>Date</th>
        <th>Action</th>
        </tr>";
          echo "<tr  id='tbl' style='background: #b6ebff'>";
          echo "<form  method='post' enctype='multipart/form-data'>";
          echo "<td><div  class='ui form'>
<input type='file'  name='up'></div>
</td>";
          echo "<td><div  class='ui form'><input type='text' placeholder='Products Name' name='prod'></div></td>";
          echo "<td>".date("Y/m/d")."</td>";
          echo "<td>"."<div  class='ui form'><input type='submit' value='Add' class='ui secondary button' name='add'></div>"."</td>";
          echo "</form>";
          echo "</tr>";
    echo "</table>";
      $prod=new MangeProductsByWorker();
      if (isset($_POST['add']))
      {
            if (isset($_FILES['up']))
            {
                $prod->AddProduct('up',$_POST['prod'],$_SESSION['dep'],$_SESSION['user']);
            }
}
$prod->ShowByDep($_SESSION['dep']);
ob_end_flush();