<?php 

namespace Stacey\Potato;

use Stacey\Potato\Config\DbConnect;

/**
* A class that performs the basic crud database operations
*
* The connection to the database is done on instantiation of an object,
* due to the presence of the constructor.
*
* @author Stacey Achungo
*/
class CarCrud
{
    /** @var object $dbConnect To hold the PDO object returned on successful 
    * connection to database
    */
    public static $dbConnect;

    /** @var string $name To hold the name of the car */
    public $name;

    /** @var string $price To hold the price of the car */
    public $price;
    
    /** @var string $color To hold the color of the car, its default value
    * is set to grey
    */
    public $color = "grey";

    /**
    * A constructor
    * 
    * Called on each instantiation of an object, to connect to the database
    * by using the class declared in DbConnect.php
    */
    public function __construct()
    {
        $host = "localhost";
        $dbname = "carstore";
        $user = "andela";
        $pass = "andela";

        CarCrud::$dbConnect = new DbConnect("mysql:host=$host;dbname=$dbname", $user, $pass);
    }

    /**
    * A method to retrieve all the record from the car table in the database
    *
    * Uses prepared statement to execute the sql statement that retrieves the 
    * data.
    *
    * @return array All the record in the table as an associative array
    */
    public static function getAll()
    {
        $result = self::$dbConnect->prepare("SELECT * FROM cars");
        $result -> execute();
        return $result-> fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
    * A method that searches for a particular record dependent on an id.
    *
    * Accepts the id of the row as parameter and uses it to select that
    * particular row. If the row is found then it returns the results mapped
    * onto an object, else it throws an exception.
    * For the setFetchMode() to work, you have to specify the qualified
    * name of the class as its second parameter.
    *
    * @param integer id The id of the record to be retrieved from the table
    * @return object The retrieved record mapped to an object
    * @throws Exception if the record does not exist in the table
    */
    public static function find($id)
    {
        $found = self::$dbConnect->prepare("SELECT * FROM cars WHERE id = $id");
        $found->execute();
        if ($found->rowCount() > 0) {
            $found->setFetchMode(\PDO::FETCH_CLASS, 'Stacey\Potato\CarCrud');
            return $found->fetch();
        } else {
            throw new \Exception("Record does not exist!!");
        }
    }

    /**
    * A method to save the new record - updated or inserted - to the table in
    * the database.
    *
    * Checks if the id is set, if it is then it checks if either the color, 
    * name or price of the car is set. Then the entire record of the given 
    * id in the database is updated.
    * If the id is not set, a check is done to verify that there is no
    * record in the table with the same car name. If the car name exists in the
    * table, it returns a string with the same message. Otherwise, the new 
    * record is inserted into the table.
    *
    * @return integer if an update or insert is done
    * @return string if data to be inserted is already in the table
    */
    public function save()
    {
        if (isset($this->id)) {
            if (isset($this->name) || isset($this->color) || isset($this->price)) {
                $sql = "UPDATE cars SET name = :name, color = :color, price= :price WHERE id = :id";
                $result = self::$dbConnect->prepare($sql);
                $result->execute(array(':name' => $this->name,
                                      ':color' => $this->color,
                                      ':price' => $this->price,
                                      ':id' => $this->id));
                return $result->rowCount();
            }
        } else {
            if (isset($this->name, $this->price, $this->color) && is_string($this->price)) {
                $query = "SELECT name FROM cars WHERE name = :name";
                $check = self::$dbConnect->prepare($query);
                $check->execute(array(":name" => $this->name));

                if ($check->rowCount() > 0) {
                    return "Record already exist in the table!!\n";
                } else {
                    $sql= "INSERT INTO cars (name, color, price) 
						   VALUES (:name, :color, :price)";
                    $result = self::$dbConnect->prepare($sql);
                    $result->execute(array(':name' => $this->name,
                                            ':color' => $this->color,
                                            ':price' => $this->price));
                    return $result->rowCount();
                }
            }
        }
    }

    /**
    * A method that deletes a record dependent on id given.
    *
    * Accepts the id of the row as parameter and uses it to select that
    * particular row. If the row is found then it deletes it from the table.
    * Otherwise it throws an exception that the record to be deleted
    * does not exist in the table .
    *
    * @param integer id The id of the record to be deleted from the table
    * @return integer The count of rows affected by the SQL query
    * @throws Exception if the record does not exist in the table
    */
    public static function destroy($id)
    {
        $query = "SELECT * FROM cars WHERE id = $id";
        $check = self::$dbConnect->prepare($query);
        $check->execute();
        if ($check->rowCount() > 0) {
            $found = self::$dbConnect->prepare("DELETE FROM cars WHERE id = $id");
            $found->execute();
            return $found->rowCount();
        } else {
            throw new \Exception("Record to be deleted does not exist!!");
        }
    }
}
