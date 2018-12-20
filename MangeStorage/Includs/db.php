<?php
class  DataBase
{
    private        $dbType="mysql";
    private        $dbHost="localhost";
    private        $dbName="Sell&Buy";
    private        $dbUser="root";
    private        $dbPass="";
    private        $dbOptions=[PDO::MYSQL_ATTR_INIT_COMMAND=>"set Names utf8"];

    function PDO_Connect()
    {
        try
        {
             $dbDsn="{$this->dbType}:host={$this->dbHost};dbname={$this->dbName}";
            $connect= new PDO($dbDsn ,$this->dbUser,$this->dbPass,$this->dbOptions);
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connect;
        }
        catch(PDOException  $e )
        {
            echo "Error: "."<p style='color:red '>" . $e->getMessage()."</p>";
        }
    }



    function getData($db,$query,$parm = []) {
        $stmt = $db->prepare($query);
        $stmt->execute($parm);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    function setData($db,$query,$parm = []) {
        $stmt = $db->prepare($query);
        $stmt->execute($parm);
        $count = $stmt->rowCount();
        return $count;
    }
}

?>

