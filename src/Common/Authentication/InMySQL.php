<?php
/**
 * Created by PhpStorm.
 * User: kathytr
 * Date: 3/21/15
 * Time: 9:57 PM
 */

namespace Common\Authentication;

use PDO;

use PDOException;


class InMySQL implements IAuthentication
{

    protected $username;
    protected $password;

    public function __construct($posting)
    {
        $this->username = $posting->username;
        $this->password = $posting->password;
    }


    /**
     * @return bool
     */
    public function authenticate()
    {
        $dbhost = "127.0.0.1";
        $dbname = "login";
        $dbuser = "root";
        $dbpass = "root";


        // connect to the database
        try
        {
            $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " .$e->getMessage();
            return false;
        }

        if ($this->username == '')
        {
            echo "Must enter user name!";
            return false;
        }

        if ($this->password == '')
        {
            echo "Must enter password!";
            return false;
        }

        // Set up the query to retrieve user information
        $statement = $conn->prepare("SELECT username, password FROM users WHERE username = ? AND password = ?;");

        // execute if statement is correct
        if ($statement != false && $statement->execute(array($this->username, $this->password)) != false)
        {
            // get record if statement is correct
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (count($results) > 0)
            {
                // If there was a record returned, login success
                echo "MySQL login success. Hello, " . htmlspecialchars($this->username);
                return true;
            }
        }

        
        echo "MySQL login invalid!";
        return false;
    }

}
