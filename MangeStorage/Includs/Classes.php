<?php
require_once "db.php";
abstract class functions
{
    function ShowError($Error)
    {
        return "<div><p style='color: red'>".$Error."</p></div>";
    }
    function ShowSeccful($Seccful)
    {
        return "<div><p style='color: green'>".$Seccful."</p></div>";
    }
    function Filter($input,$type='str')
    {
        if ($type == 'int')
        {
                $Filterd=filter_var($input,FILTER_SANITIZE_NUMBER_INT);
                return $Filterd;
        }
        elseif ($type == 'email')
        {
                $Filterd=filter_var($input,FILTER_SANITIZE_EMAIL);
                return $Filterd;
        }
    elseif ($type == 'float')
    {
            $Filterd=filter_var($input,FILTER_SANITIZE_NUMBER_FLOAT);
            return $Filterd;
    }
        else
        {
            $Filterd=filter_var($input,FILTER_SANITIZE_STRING);
            return $Filterd;
        }
    }
    private  function getSalt()
    {
        $salt='';
        $SaltChrest=array_merge(range('A','Z'),range('a','z'),range(0,9));
        $len=55;
        for ($i=12;$i<$len;$i++)
        {
            $salt.= $SaltChrest[array_rand($SaltChrest)];
        }
        return $salt;
    }
    function Hashing($Password,$cost=9)
    {
        $salt=$this->getSalt();
        $options=[
            'salt'=> $salt,
            'cost'=>$cost
        ];
        $Hashed=password_hash($Password,1,$options);
        return $Hashed;
    }
    function uploadImages ($name)
    {
        if (!is_dir("../images/"))
        {
            mkdir("../images");
        }

            $upDir="../"."images"."/";
            $upFile=$_FILES[$name];

            if (!($upFile['error']== 4))
            {
                $extn=@end(explode('.',strtolower($upFile['name'])));
                $AlowdedExtn=["jpg","png","jpeg"];
                $AlowdedMime=["image/jpg","image/png","image/jpeg"];
                $FinalName=$upDir."image-".rand(10,(14*7888754)^44).".".$extn;
                $upAccept=null;

                if (in_array($extn,$AlowdedExtn))
                {
                    if (in_array($upFile['type'],$AlowdedMime))
                    {
                        if (getimagesize($upFile['tmp_name']) == true)
                        {
                            $upAccept=true;
                        } else
                        {
                            $upAccept=false;
                            echo $this->ShowError("Sorry This is not image");
                        }
                    } else
                    {
                        $upAccept=false;
                        echo $this->ShowError("Sorry Alowded Extns is only jpg,jpeg,png");
                    }
                } else
                {
                    $upAccept=false;
                    echo  $this->ShowError("Sorry Alowded Extns is only jpg,jpeg,png");
                }
                if ($upAccept == true)
                {
                    if (move_uploaded_file($upFile['tmp_name'],$FinalName))
                    {
                        $FinalNameDb=@end(explode("../",$FinalName));
                        return $FinalNameDb;
                    }
                }
            }else
            {
                echo $this->ShowError("can not be empty");
            }
        }
}
class AddPerson extends functions
{
    function __construct($username,$password,$role,$dep)
    {
        $Fusername = $this->Filter($username, 'str');
        $Fpassword = $this->Filter($password, 'str');
        $Frole = $this->Filter($role, 'int');
        $Fdep = $this->Filter($dep, 'int');
        $Allparms = [$Fusername, $Fpassword, $Frole, $Fdep];

        if (!empty($Fusername) && !empty($Fpassword) && !empty($Frole)) {

            $db = new DataBase();
            $query = "SELECT * FROM `users` where `username` = ?";
            $res = $db->getData($db->PDO_Connect(), $query, [$Fusername]);
            if (!count($res) > 0) {
                if ($Frole == 2 || $Frole ==1)
                {
                    $query = "INSERT INTO `users`( `username`, `password`, `role`) VALUES (?,?,?)";
                    $res = $db->setData($db->PDO_Connect(), $query, [
                        $Fusername,
                        $this->Hashing($Fpassword, 11),
                        $Frole,
                    ]);
                    if (count($res) > 0) {
                        for ($i = 0; $i < count($Allparms); $i = $i + 1) {
                            unset($Allparms[$i]);
                        }
                        echo $this->ShowSeccful("Done");
                    } else {
                        echo $this->ShowError("Fail");
                    }
                }else
                {
                    $query = "INSERT INTO `users`( `username`, `password`, `role`, `dep`) VALUES (?,?,?,?)";
                    $res = $db->setData($db->PDO_Connect(), $query, [
                        $Fusername,
                        $this->Hashing($Fpassword, 11),
                        $Frole,
                        $Fdep
                    ]);
                    if (count($res) > 0) {
                        for ($i = 0; $i < count($Allparms); $i = $i + 1) {
                            unset($Allparms[$i]);
                        }
                        echo $this->ShowSeccful("Done");
                    } else {
                        echo $this->ShowError("Fail");
                    }
                }

            } else {
                echo $this->ShowError("username used");
            }
        }
        else
        {
            echo $this->ShowError("All Fields is Req");
        }
    }
}
class SignIn extends functions
{
    function __construct($username,$password)
    {
        $Fusername=$this->Filter($username);
        $Fpassword=$this->Filter($password);
        $AllParms=[$Fusername,$Fpassword];

        if (!empty($AllParms[0] && !empty($AllParms[1])))
        {
            $db=new DataBase();
            $q="SELECT * FROM `users` where `username` = ?";
            $res=$db->getData($db->PDO_Connect(),$q,[$AllParms[0]]);
            if (count($res)>0)
            {
                if (password_verify($AllParms[1],$res[0]['password']))
                {
                    if (!isset($_SESSION))
                    {
                        session_start();
                        session_regenerate_id();
                    }
                    $_SESSION['user']=$AllParms[0];
                    $_SESSION['role']=$res[0]['role'];
                    if ($res[0]['dep'] != null)
                    {
                        $_SESSION['dep']=$res[0]['dep'];
                    }
                    $_SESSION['id']=$res[0]['id'];
                    if ($res[0]['role'] == 1)
                    {
                        header("Location:Admin/Panel.php");
                        die();
                    }elseif ($res[0]['role'] == 0)
                    {
                        header("Location:Worker/Panel.php");
                        die();
                    }elseif ($res[0]['role'] == 2)
                    {
                        header("Location:index.php");
                        die();
                    }
                } else
                {
                    echo $this->ShowError("password Error");
                }
            } else
            {
                echo $this->ShowError("username not found");
            }
        }
    }
}
class CheckLogin extends functions
{
    function __construct($id)
    {
        $Fid=$this->Filter($id,'int');
        $db=new DataBase();
        $res=$db->getData($db->PDO_Connect(),"SELECT * FROM `users` where id =?",[$Fid]);
        if (count($res)<=0)
        {
            header("Location:../login.php");
            die();
        }
    }
}
class CheckAdmin extends functions
{
    function __construct($role)
    {
        $Frole=$this->Filter($role,'int');
        if ($Frole != 1)
        {
            header("Location:../login.php");
            die();
        }
    }
}
class MangeProductsByWorker extends functions
{
    function AddProduct ($img,$name,$dep,$added_by)
      {
            $Fname=$this->Filter($name);
            $imgDB=$this->uploadImages($img);
            if ($imgDB != null)
            {
                if (!empty($Fname))
                {
                    $db=new DataBase();
                    $sql="INSERT INTO `sell_products`( `product`, `image`,  `added_time`, `added_by`, `dep`,  `status`) VALUES (?,?,?,?,?,?)";
                    $res=$db->setData($db->PDO_Connect(),$sql,[
                        $Fname,
                        $imgDB,
                        date("Y/m/d"),
                        $added_by,
                        $dep,
                        0
                    ]);
                    if (count($res)>0)
                    {
                        echo $this->ShowSeccful("Done");
                    } else
                    {
                        echo $this->ShowError("Fail");
                    }
                } else
                {
                    echo $this->ShowError("All Fileds Is Req");
                }
            }
      }
      function ShowProductsByDate($date)
      {
            $Fdate=$this->Filter($date);
            if (!empty($Fdate))
            {
                $db=new DataBase();
                $query="SELECT * FROM `sell_products` WHERE `added_time` = ?";
                $res=$db->getData($db->PDO_Connect(),$query,[$Fdate]);
                if (count($res)>0)
                {
                   echo "<table class='ui table'><tr style='background: yellow'>
<th>Product</th>
<th>image</th>
<th>Rate</th>
<th>Price</th>
<th>Order</th>
<th>Added_time</th>
<th>Added By</th>
<th>Deprtment</th>
<th>Action</th>
</tr>";
                   foreach ($res as $re)
                   {
                       echo "<tr style='background: #0ea432'>";
                       echo "<td>".$re['product']."</td>";
                       $img="../".$re['image'];
                       $id=$re['id'];
                       echo "<td><img src='$img' style='max-width: 44px;max-height: 22px'></td>";
                       echo "<td>".$re['rate']."</td>";
                       echo "<td>".$re['order_product']."</td>";
                       echo "<td>".$re['added_time']."</td>";
                       echo "<td>".$re['added_by']."</td>";
                       echo "<td>".$re['dep']."</td>";
                     echo "<td>
<form method='post'>
<input type='hidden' name='id' value='$id'>
<input type='submit' name='del' value='Delete' class='ui red button'>
</form>
</td>";
                       echo "</tr>";
                   }
                   echo "</table>";
                } else
                {
                    echo $this->ShowError("No Data");
                }
            }
      }
      function Delete($id)
      {
          $Fid=$this->Filter($id,'int');
          if (!empty($Fid))
          {
              $db=new DataBase();
              $res=$db->getData($db->PDO_Connect(),"SELECT * FROM `sell_products` where id =?",[$Fid]);
              if (count($res)>0)
              {
                  $delId=$res[0]['image'];
                  if (unlink("../".$delId))
                  {
                      $qu="DELETE FROM `sell_products` WHERE `id` = ?";
                      $res=$db->setData($db->PDO_Connect(),$qu,[$Fid]);
                      $Fres=count($res)>0 ? print $this->ShowSeccful("Done"):print $this->ShowError("Fail");
                  }else
                  {
                      echo $this->ShowError("fail");
                  }
              }
          }
      }
      function DeleteU($id)
      {
          $Fid=$this->Filter($id,'int');
          if (!empty($Fid))
          {
              $db=new DataBase();
              $res=$db->getData($db->PDO_Connect(),"SELECT * FROM `sell_products` where id =?",[$Fid]);
              if (count($res)>0)
              {
                  $delId=$res[0]['image'];
                  if (unlink($delId))
                  {
                      $qu="DELETE FROM `sell_products` WHERE `id` = ?";
                      $res=$db->setData($db->PDO_Connect(),$qu,[$Fid]);
                      error_reporting(0);
                      $Fres= count($res)>0 ? print $this->ShowSeccful("Done"):print $this->ShowError("Fail");
                  }else
                  {
                      echo $this->ShowError("fail");
                  }
              }
          }
      }
    function ShowByDep($dep)
    {
        $Fdep=$this->Filter($dep);
        if (!empty($Fdep))
        {
            $db=new DataBase();
            $query="SELECT * FROM sell_products WHERE dep = ?";
            $res=$db->getData($db->PDO_Connect(),$query,[$Fdep]);
            if (count($res)>0)
            {
                echo "<table class='ui table'><tr style='background: #fafffb'>
        <th>d1</th>
        <th>product</th>
        <th>Product name</th>
        <th>Evaluation</th>
        <th>Date</th>
        <th>Order</th>
        <th>Notes</th>
        <th>Deprtment</th> 
        <th>Action</th>
        </tr>";
                foreach ($res as $re)
                {
                    echo "<tr style='background: #b6ebff'>";
                    $id=$re['id'];
                    echo "<td>$id</td>";
                    $img="../".$re['image'];
                    echo "<td><img src='$img' style='max-width: 44px;max-height: 22px'></td>";
                    echo "<td>".$re['product']."</td>";
                    echo "<td>".$re['rate']."</td>";
                    echo "<td>".$re['added_time']."</td>";
                    echo "<td>".$re['order_product']."</td>";
                    echo "<td>".$re['added_by']."</td>";
                    echo "<td>".$re['dep']."</td>";
                    echo "<td>
<form method='post'>
<input type='hidden' name='id' value='$id'>
<input type='submit' name='del' value='Delete' class='ui red button'>
</form>
</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else
            {
                echo $this->ShowError("No Data");
            }
        }
    }
    function ShowState1()
    {

            $db=new DataBase();
            $query="SELECT * FROM sell_products WHERE `status` = 0";
            $res=$db->getData($db->PDO_Connect(),$query);
            if (count($res)>0)
            {
                echo "<table class='ui table'><tr style='background: #fafffb'>
        <th>d1</th>
        <th>product</th>
        <th>Product name</th>
        <th>Evaluation</th>
        <th>Order</th>
        <th>Notes</th>
        <th>Added By</th>
        <th>Date</th>
        <th>Deprtment</th> 
        <th>Action</th>
        </tr>";
                foreach ($res as $re)
                {
                    echo "<tr style='background: #b6ebff'>";
                    echo "<form method='post'>";
                    $img="../".$re['image'];
                    $id=$re['id'];
                    echo "<td>".$id."</td>";
                    echo "<td><img src='$img' style='max-width: 44px;max-height: 22px'></td>";
                    echo "<td>".$re['product']."</td>";
                    echo "<td>"."<div class='ui form'><select name='rate' class='ui selectable'><option value='E.X'>Ex</option><option value='V.G'>V.G</option> </select> </div>"."</td>";
                    echo "<td>"."<div class='ui form'><input type='text' placeholder='Order' name='order'></div>"."</td>";
                    echo "<td>"."<div class='ui form'><input type='text' placeholder='Notes' name='notes'></div>"."</td>";
                    echo "<td>".$re['added_by']."</td>";
                    echo "<td>".$re['added_time']."</td>";
                    echo "<td>".$re['dep']."</td>";
                    echo "<td>
<input type='hidden' name='id' value='$id'>
<input type='submit' name='del' value='Delete' class='ui red button'>
<input type='submit' name='accept' value='Accept And Save' class='ui primary button'>
</td>";
                    echo "</form>";
                    echo "</tr>";
                }
                echo "</table>";
            } else
            {
                echo $this->ShowError("No Data");
            }
        }
        function FinalSave($id,$ev,$notes,$order)
        {
            $Fev=$this->Filter($ev);
            $Forder=$this->Filter($order,'int');
            $Fnotes=$this->Filter($notes);
            $db=new DataBase();
            $q="UPDATE `sell_products` SET `rate` = ? , `order_product` = ?, `notes` =?,`status` = ? WHERE `id` =? ";
            $res=$db->setData($db->PDO_Connect(),$q,[
                $Fev,
                $Forder,
              $Fnotes,
              1,
              $this->Filter($id,'int')
            ]);
            if (count($res)>0)
            {
                echo $this->ShowSeccful("Done");
            }else
            {
                echo $this->ShowError("Fail");
            }
        }
    function ShowByWorker($worker)
    {
        $Fworker=$this->Filter($worker);
        if (!empty($Fworker))
        {
            $db=new DataBase();
            $query="SELECT * FROM sell_products WHERE `added_by` = ?";
            $res=$db->getData($db->PDO_Connect(),$query,[$Fworker]);
            if (count($res)>0)
            {
                echo "<table class='ui table'><tr style='background: #fafffb'>
        <th>d1</th>
        <th>product</th>
        <th>Product name</th>
        <th>Evaluation</th>
        <th>Date</th>
        <th>Order</th>
        <th>Notes</th>
        <th>Deprtment</th> 
        <th>Action</th>
        </tr>";
                foreach ($res as $re)
                {
                    echo "<tr style='background: #b6ebff'>";
                    $id=$re['id'];
                    echo "<td>$id</td>";
                    $img="../".$re['image'];
                    echo "<td><img src='$img' style='max-width: 44px;max-height: 22px'></td>";
                    echo "<td>".$re['product']."</td>";
                    echo "<td>".$re['rate']."</td>";
                    echo "<td>".$re['added_time']."</td>";
                    echo "<td>".$re['order_product']."</td>";
                    echo "<td>".$re['added_by']."</td>";
                    echo "<td>".$re['dep']."</td>";
                    echo "<td>
<form method='post'>
<input type='hidden' name='id' value='$id'>
<input type='submit' name='del' value='Delete' class='ui red button'>
</form>
</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else
            {
                echo $this->ShowError("No Data");
            }
        }
    }function ShowByRate($rate)
    {
        $Frate=$this->Filter($rate);
        if (!empty($Frate))
        {
            $db=new DataBase();
            $query="SELECT * FROM sell_products WHERE `rate` = ?";
            $res=$db->getData($db->PDO_Connect(),$query,[$Frate]);
            if (count($res)>0)
            {
                echo "<table class='ui table'><tr style='background: #fafffb'>
        <th>d1</th>
        <th>product</th>
        <th>Product name</th>
        <th>Evaluation</th>
        <th>Date</th>
        <th>Order</th>
        <th>Notes</th>
        <th>Deprtment</th> 
        <th>Action</th>
        </tr>";
                foreach ($res as $re)
                {
                    echo "<tr style='background: #b6ebff'>";
                    $id=$re['id'];
                    echo "<td>$id</td>";
                    $img="../".$re['image'];
                    echo "<td><img src='$img' style='max-width: 44px;max-height: 22px'></td>";
                    echo "<td>".$re['product']."</td>";
                    echo "<td>".$re['rate']."</td>";
                    echo "<td>".$re['added_time']."</td>";
                    echo "<td>".$re['order_product']."</td>";
                    echo "<td>".$re['added_by']."</td>";
                    echo "<td>".$re['dep']."</td>";
                    echo "<td>
<form method='post'>
<input type='hidden' name='id' value='$id'>
<input type='submit' name='del' value='Delete' class='ui red button'>
</form>
</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else
            {
                echo $this->ShowError("No Data");
            }
        }
    }
    function ShowByDepU($dep)
    {
        $Fdep=$this->Filter($dep);
        if (!empty($Fdep))
        {
            $db=new DataBase();
            $query="SELECT * FROM sell_products WHERE dep = ?";
            $res=$db->getData($db->PDO_Connect(),$query,[$Fdep]);
            if (count($res)>0)
            {
                echo "<table class='ui table'><tr style='background: #fafffb'>
        <th>d1</th>
        <th>product</th>
        <th>Product name</th>
        <th>Evaluation</th>
        <th>Date</th>
        <th>Order</th>
        <th>Notes</th>
        <th>Deprtment</th> 
        </tr>";
                foreach ($res as $re)
                {
                    echo "<tr style='background: #b6ebff'>";
                    $id=$re['id'];
                    echo "<td>$id</td>";
                    $img=$re['image'];
                    echo "<td><img src='$img' style='max-width: 44px;max-height: 22px'></td>";
                    echo "<td>".$re['product']."</td>";
                    echo "<td>".$re['rate']."</td>";
                    echo "<td>".$re['added_time']."</td>";
                    echo "<td>".$re['order_product']."</td>";
                    echo "<td>".$re['added_by']."</td>";
                    echo "<td>".$re['dep']."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else
            {
                echo $this->ShowError("No Data");
            }
        }
    }
    function ShowByRateU($rate)
    {
        $Frate=$this->Filter($rate);
        if (!empty($Frate))
        {
            $db=new DataBase();
            $query="SELECT * FROM sell_products WHERE `rate` = ?";
            $res=$db->getData($db->PDO_Connect(),$query,[$Frate]);
            if (count($res)>0)
            {
                echo "<table class='ui table'><tr style='background: #fafffb'>
        <th>d1</th>
        <th>product</th>
        <th>Product name</th>
        <th>Evaluation</th>
        <th>Date</th>
        <th>Order</th>
        <th>Notes</th>
        <th>Addby</th>
        <th>Deprtment</th> 
        </tr>";
                foreach ($res as $re)
                {
                    echo "<tr style='background: #b6ebff'>";
                    $id=$re['id'];
                    echo "<td>$id</td>";
                    $img=$re['image'];
                    echo "<td><img src='$img' style='max-width: 44px;max-height: 22px'></td>";
                    echo "<td>".$re['product']."</td>";
                    echo "<td>".$re['rate']."</td>";
                    echo "<td>".$re['added_time']."</td>";
                    echo "<td>".$re['order_product']."</td>";
                    echo "<td>".$re['notes']."</td>";
                    echo "<td>".$re['added_by']."</td>";
                    echo "<td>".$re['dep']."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else
            {
                echo $this->ShowError("No Data");
            }
        }
    }
    function ShowStatusToAccept ()
    {
                $db=new DataBase();
                $query="SELECT * FROM sell_products WHERE `status` = 1";
                $res=$db->getData($db->PDO_Connect(),$query);
                if (count($res)>0)
                {
                    echo "<table class='ui table'><tr style='background: #fafffb'>
        <th>d1</th>
        <th>product</th>
        <th>Product name</th>
        <th>Evaluation</th>
        <th>Order</th>
        <th>Notes</th>
        <th>Added By</th>
        <th>Date</th>
        <th>Deprtment</th> 
        <th>Action</th>
        </tr>";
                    foreach ($res as $re)
                    {
                        echo "<tr style='background: #b6ebff'>";
                        echo "<form method='post'>";
                        $img=$re['image'];
                        $id=$re['id'];
                        echo "<td>".$id."</td>";
                        echo "<td><img src='$img' style='max-width: 44px;max-height: 22px'></td>";
                        echo "<td>".$re['product']."</td>";
                        echo "<td>".$re['rate']."</td>";
                        echo "<td>".$re['order_product']."</td>";
                        echo "<td>".$re['notes']."</td>";
                        echo "<td>".$re['added_by']."</td>";
                        echo "<td>".$re['added_time']."</td>";
                        echo "<td>".$re['dep']."</td>";
                        echo "<td>
<input type='hidden' name='id' value='$id'>
<input type='submit' name='del' value='Delete' class='ui red button'>
<input type='submit' name='accept' value='Accept And Save' class='ui primary button'>
</td>";
                        echo "</form>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else
                {
                    echo $this->ShowError("No Data");
                }
            }
            function AcceptAdminAccept ($id)
            {
                $Fid=$this->Filter($id,'int');
                if (!empty($Fid))
                {
                    $db=new DataBase();
                    $res=$db->setData($db->PDO_Connect(),"UPDATE `sell_products` SET `status` = 2 WHERE `id` = ?",[$Fid]);
                    if (count($res)>0)
                    {
                        echo $this->ShowSeccful("Accepted");
                    }else
                    {
                        echo $this->ShowError("Fail");
                    }
                }
            }
}
class CheckLoginMain extends functions
{
    function __construct($id)
    {
        $Fid=$this->Filter($id,'int');
        $db=new DataBase();
        $res=$db->getData($db->PDO_Connect(),"SELECT * FROM `users` where id =?",[$Fid]);
        if (count($res)<=0)
        {
            header("Location:login.php");
            die();
        }
    }
}
class CheckWorker extends functions
{
    function __construct($role)
    {
        $Frole=$this->Filter($role,'int');
        if (!empty($Frole))
        {
            if ($Frole != 0)
            {
                header("Location:../login.php");
                die();
            }
        }
    }
}

