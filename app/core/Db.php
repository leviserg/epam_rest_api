<?php
	namespace app\core;
    use mysqli;
    use mysqli_sql_exception;

	class Db {

        public static $tblname = "todos";

        public static function dbconn(){
            return [
                'host' => 'localhost',
                'dbname' => 'dbtodos',
                'user' => "root",
                'pwd'  => "1111"
            ];
        }

        public static function getAssocData($res){
            $ret = [];
            while($data = $res->fetch_assoc()){
                foreach($data as $key => $keyval){
                    $temp[$key] = $keyval;
                }
                array_push($ret, $temp);
            }
            return $ret;
        }

        public static function dbSeed(){
            $db = self::dbconn();
            try{
                $mysqli = new mysqli($db['host'],$db['user'],$db['pwd']);
                if ($mysqli->connect_errno) {
                    return null;
                }
                else{
                    $sql = "SHOW DATABASES LIKE '".$db['dbname']."'";
                    $res = $mysqli->query($sql);
                    $row = $res->fetch_array();
                    $res->free();
                    if($row[0]===$db['dbname']){
                        echo "Database {$db['dbname']} already exists...\n";
                        $sql = "drop table if exists `".$db['dbname']."`.`".self::$tblname."`";
                        $res = $mysqli->query($sql);
                        if($res){
                            echo "Table `".self::$tblname."` has been deleted\n";
                            $sql = "CREATE TABLE IF NOT EXISTS `".$db['dbname']."`.`".self::$tblname."`  ( `id` INT(3) NOT NULL AUTO_INCREMENT , `todo` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `recdate` DATETIME NOT NULL , PRIMARY KEY (`id`))";
                            $mysqli->query($sql);
                            echo "Table ".self::$tblname." has been created...\n";
                            $sql = "INSERT INTO `".$db['dbname']."`.`".self::$tblname."` (`todo`,`recdate`) VALUES ('first task', date_sub(NOW(), interval 2 hour)); ";
                            $mysqli->query($sql);
                            $sql = "INSERT INTO `".$db['dbname']."`.`".self::$tblname."` (`todo`,`recdate`) VALUES ('second task', date_sub(NOW(), interval 1 hour)); ";
                            $mysqli->query($sql);
                            $sql = "INSERT INTO `".$db['dbname']."`.`".self::$tblname."` (`todo`,`recdate`) VALUES ('third task', NOW()); ";
                            $mysqli->query($sql);
                            echo "Records ".self::$tblname." has been inserted...\n";                            
                        }
                    }
                    else{
                        $sql = "CREATE DATABASE IF NOT EXISTS ".$db['dbname'];
                        if ($mysqli->query($sql) === TRUE){
                            echo "Database ".$db['dbname']." has been created...\n";
                            $sql = "CREATE TABLE IF NOT EXISTS `".$db['dbname']."`.`".self::$tblname."`  ( `id` INT(3) NOT NULL AUTO_INCREMENT , `todo` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `recdate` DATETIME NOT NULL , PRIMARY KEY (`id`))";
                            $res = $mysqli->query($sql);
                            if($res){
                                echo "Table ".self::$tblname." has been created...\n";
                                $sql = "INSERT INTO `".$db['dbname']."`.`".self::$tblname."` (`todo`,`recdate`) VALUES ('first task', date_sub(NOW(), interval 2 hour));";
                                $mysqli->query($sql);                                
                                $sql = "INSERT INTO `".$db['dbname']."`.`".self::$tblname."` (`todo`,`recdate`) VALUES ('second task', date_sub(NOW(), interval 1 hour)); ";
                                $mysqli->query($sql);
                                $sql = "INSERT INTO `".$db['dbname']."`.`".self::$tblname."` (`todo`,`recdate`) VALUES ('third task', NOW()); ";
                                $mysqli->query($sql);
                                echo "Records ".self::$tblname." has been inserted...\n";                            
                            }
                        } else {
                            echo "Error creating database: " . $mysqli->error;
                        }			
                    }
                    $mysqli->close();
                    echo "--- Script finished ---";
                }
            } catch(mysqli_sql_exception $e){
                echo "Error connection to Database";
                return null;
            } 

        }

    }
?>