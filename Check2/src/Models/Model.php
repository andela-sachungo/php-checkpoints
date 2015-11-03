<?php 

namespace Stacey\Potato\Models;

use Stacey\Potato\DbConnect;
use Stacey\Potato\Interfaces\QueryDB;

/**
* A class that does basic ORM functions.
*
* @author Stacey Achungo
*/
class Model implements QueryDB
{
    /** @var string $table To hold the name of the table */
    public static $table = null;

    /** @var array $properties To hold the field name and their corresponding
    * values
    */
    private $properties = [];
    
    /** 
    * A customised getter to get the table name
    *
    * Uses a ternary operator to check if the table name is set if not it 
    * returns the class name in lowercase as the table name
    *
    * @return string The table name
    */
    public static function getTableName()
    {
        return isset(static::$table) ? static::$table : strtolower(get_called_class());
    }

    /** 
    * A customised setter to set the table name
    *
    * @param string table The name of the table
    */
    public static function setTableName($table)
    {
        static::$table = $table;
    }

    /** 
    * A magic method to create property names on the fly and store data 
    * associated with those properties
    *
    * @param string propName The property name
    * @param string propVal The property value  
    */
    public function __set($propName, $propVal)
    {
        $this->properties[$propName] = $propVal;
    }

    /** 
    * A magic method to get the values of private properties
    *
    * @param string propName The property whose value is being accessed
    * @return string The value of the private property being accessed
    */
    public function __get($propName)
    {
        if (array_key_exists($propName, $this->properties)) {
            return $this->properties[$propName];
        }
    }

    /** 
    * A method to get all the record from a table
    *
    * @return array An array of the model instances
    */
    public static function getAll()
    {
        try {
            $result = DbQuerries::selectAllRecord(static::$table);
            $modelInstance = [];

            // create an array of the model instances
            foreach ($result as $value) {
                $model = new static();
                $modelInstance[] = self::instanceOfModel($result, $model);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $modelInstance;
    }

    /** 
    * A method to find a record by its id
    *
    * @param integer id The id of the record
    * @return object An instance of the model
    * @throws Exception if the record does not exist in the table
    */
    public static function find($id)
    {
        try {
            $result = DbQuerries::selectSpecificRecord(static::$table, ['id'=>$id]);

            if (empty($result)) {
                throw new \Exception("Record does not exist!!");
            } else {
                $model = new static();
                $instance = self::instanceOfModel($result[0], $model);
                return $instance;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /** 
    * A method to find a record by other field name which is not ID
    *
    * @param array data The associative array of field name value pair
    * @return object An instance of the model
    * @throws Exception if an integer is passed as argument
    * @throws Exception if the record does not exist in the table
    */
    public static function findNotByID($data)
    {
        try {
            if (is_int($data)) {
                throw new \Exception("findNotByID only accepts non-integer argument!");
            } else {
                $result = DbQuerries::selectSpecificRecord(static::$table, $data);

                if (empty($result)) {
                    throw new \Exception("Record does not exist!!");
                } else {
                    $model = new static();
                    $instance = self::instanceOfModel($result[0], $model);
                    return $instance;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /** 
    * A method to save data to database
    *
    * Used when inserting or updating a record.
    * It checks if the id exists, if it does then it updates a record otherwise
    * it inserts.
    */
    public function save()
    {
        try {
            if ($this->id) {
                $result = DbQuerries::updateById(static::$table, $this->id, $this->properties);
            } else {
                $result = DbQuerries::insertInTable(static::$table, $this->properties);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /** 
    * A method to destroy a record by its id
    *
    * @param integer id The id of the record
    */
    public static function destroy($id)
    {
        try {
            $result = DbQuerries::deleteById(static::$table, $id);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /** 
    * A method to return instances of the model class when doing select queries
    *
    * Turns each returned field name into a property of the model instance and
    * assigns them their corresponding values.
    *
    * @param array queryResult The results of an SQL query
    * @param object model An instance of the Model class
    * @return object An instance of the model
    */
    private static function instanceOfModel($queryResult, $model)
    {
        foreach ($queryResult as $key => $value) {
            $model->$key = $value;
        }
        return $model;
    }
}
