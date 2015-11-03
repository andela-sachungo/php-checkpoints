<?php 

namespace Stacey\Potato;

/**
* A class that sets up PDO to throw an exception by default should an error 
* occur.
*
* The class extends the PDO extension class as explained in:
* http://stackoverflow.com/questions/8992795/set-pdo-to-throw-exceptions-by-default
*/
class DbConnect extends \PDO
{
    /**
    * A constructor 
    *
    * The constructor accepts parameters such as dsn, username etc.
    * All the other parameters are given default values except the $dsn.
    * The parent::__construct() is called to ensure the PDO class constructor
    * is run when instantiating an object of this class.
    * The setAttribute() method is used to set up PDO to throw exceptions 
    * incase an error occurs as "setAttribute(ATTRIBUTE, OPTION)";
    *
    * @param string dsn Holds the information required to connect to database
    * @param string username The username for the database
    * @param string password The password to sign in to database
    * @param array driver_options Associative array of driver-specific 
    * connection options
    */
    public function __construct($dsn, $username = null, $password = null, array $driver_options = null)
    {
        parent :: __construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
