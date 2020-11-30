<?php
    namespace app\core\models;

    use app\core\Db;
    use mysqli_sql_exception;
    use mysqli;

    class Main extends Db {

        public function getAll(){
            $db = self::dbconn();
            try{
                $mysqli = new mysqli($db['host'],$db['user'],$db['pwd'],$db['dbname']);
                if ($mysqli->connect_errno) {
                    return null;
                }
                else{
                    $sql = "select * from `".self::$tblname."`";
                    $res = $mysqli->query($sql);
                    $ret = self::getAssocData($res);
                    $res->close();
                    $mysqli->close();
                    return $ret;
                }
            } catch(mysqli_sql_exception $e){
                return null;
            }               
        }

        public function getOne($id){
            $db = self::dbconn();
            try{
                $mysqli = new mysqli($db['host'],$db['user'],$db['pwd'],$db['dbname']);
                if ($mysqli->connect_errno) {
                    return null;
                }
                else{
                    $sql = "select * from `".self::$tblname."` where `id`='{$id}'";
                    $res = $mysqli->query($sql);
                    $ret = self::getAssocData($res);
                    $res->close();
                    $mysqli->close();
                    return $ret;
                }
            } catch(mysqli_sql_exception $e){
                return null;
            }               
        }

        public function insNew($postdata){
            $db = self::dbconn();
            try{
                $mysqli = new mysqli($db['host'],$db['user'],$db['pwd'],$db['dbname']);
                if ($mysqli->connect_errno) {
                    return null;
                }
                else{
                    $sql = "insert into `".self::$tblname."` (`todo`,`recdate`)  values ('{$postdata}', now())";
                    $res = $mysqli->query($sql);
                    ($res) ? $ret = true : $ret = false;
                    $mysqli->close();
                    return $ret;
                }
            } catch(mysqli_sql_exception $e){
                return null;
            }               
        }

        public function updOne($id, $putdata){
            $db = self::dbconn();
            try{
                $mysqli = new mysqli($db['host'],$db['user'],$db['pwd'],$db['dbname']);
                if ($mysqli->connect_errno) {
                    return null;
                }
                else{
                    $sql = "update `".self::$tblname."` set `todo` = '{$putdata}', `recdate` = now() where `id` = '{$id}'";
                    $res = $mysqli->query($sql);
                    ($res) ? $ret = true : $ret = false;
                    return $ret;
                }
            } catch(mysqli_sql_exception $e){
                return null;
            }               
        }

        public function delOne($id){
            $db = self::dbconn();
            try{
                $mysqli = new mysqli($db['host'],$db['user'],$db['pwd'],$db['dbname']);
                if ($mysqli->connect_errno) {
                    return null;
                }
                else{
                    $sql = "delete from `".self::$tblname."` where `id` = '{$id}'";
                    $res = $mysqli->query($sql);
                    ($res) ? $ret = true : $ret = false;
                    return $ret;
                }
            } catch(mysqli_sql_exception $e){
                return null;
            }               
        }

    }
?>