<?php
    namespace MusicPlayer\Services;

    require('../database/LoginConfig.php');
    require('../database/LoginDatabase.php');

    use MusicPlayer\Database\LoginDatabase;

    class LoginDao {
        public $database;

        function  __construct($pdo) {
            $this->database = new LoginDatabase($pdo);
        }

        function get_cookie($username, $password) {
            $statement = "SELECT * FROM user_details WHERE username = '". $username ."'";
            $rows = $this->database->getData($statement);

            if($rows > 0)
            {
                
                foreach($rows as $row)
                {
                    if(password_verify($password, $row["password"]))
                    {
                        setcookie("type", $row["type"], time()+3600);
                        header("location:index.php");
                    } else {
                        return $message = '<div  class="alert alert-danger">Wrong Password</div>';
                    }
                }   
            } else
            {
                return $message = "<div class='alert alert-danger'>Wrong Email Address</div>";
            }
        }
    }
?>