<?php 

namespace Stacey\Potato\Models;

use Stacey\Potato\DbConnect;

/**
* A class template for general database querries.
*
* @author Stacey Achungo
*/
class DbQuerries
{
    /** @var object $dbConnect To hold an instance of PDO class */
    public static $dbConnect;

    /**
    * A method to connect to the database
    *
    * Accepts an array as argument, checks if a connection to database has not
    * already been established. If it hasn't then it connects to database.
    *
    * @param array dbConnectionDetails An array of data used to connect to 
    * database using PDO
    * @return object An instance of PDO class
    */
    public static function connect(array $dbConnectionDetails)
    {
        try {
            // connect if not already connected
            if (is_null(self::$dbConnect)) {
                self::$dbConnect = new DbConnect(
                    "mysql:host=".$dbConnectionDetails['host'].
                    ";dbname=".$dbConnectionDetails['dbname'],
                    $dbConnectionDetails['user'],
                    $dbConnectionDetails['pass']
                );
            }
            return self::$dbConnect;
        } catch (Exception $e) {
            return  $e->getMessage();
        }
    }

    /**
    * A method to disconnect from the database
    */
    public static function disconnect()
    {
        self::$dbConnect = null;
    }

    /**
    * A method to select all records that exist in a table.
    *
    * @param string table The table name
    * @return array An associative array of all the records in the table
    */
    public static function selectAllRecord($table)
    {
        $result = self::$dbConnect->prepare("SELECT * FROM {$table}");
        $result -> execute();
        return $result-> fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
    * A method to select specific record(s) from a table.
    *
    * Assigns the values of the associated array passed as argument
    * to a variable that holds all the values. The field names 
    * are obtained by getting the keys of the associated array.
    *
    * @param string table The table name
    * @param array select_match An associative array of field name value pair
    * @param string(optional) order Field names by which the results should be 
    * ordered by
    * @param integer(optional) limit Limit of the SQL query
    * @param integer(optional) offset Offset of the SQL query
    * @return array An associative array of the specific record(s) in the table
    */
    public static function selectSpecificRecord($table, array $select_match, $order = '', $limit = null, $offset = '')
    {
        foreach ($select_match as $key => $value) {
            $parameter_value = $value;
        }
        $field = key($select_match);

        $sql = "SELECT * FROM {$table}
			WHERE {$field} = '{$parameter_value}'";

        if ($order !== '') {
            $sql .= " ORDER BY $order";
        }

        if ($limit !== null && $limit > 0) {
            $sql .= " LIMIT $limit";
        }

        if ($offset !== '' && $offset > 0) {
            $sql .= "OFFSET $offset";
        }

        $result = self::$dbConnect->prepare($sql);
        
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
    * A method to insert data to a table.
    *
    * The fields and their corresponding record are obtained from the array
    * using implode function and an SQL query built from the results.
    * 
    * @param string table The table name
    * @param array data An associated array of field name value pair
    * @return integer Number of rows affected by the query
    */
    public static function insertInTable($table, array $data)
    {
        $field_names = implode(',', array_keys($data));
        $values = "'". implode("','", array_values($data)). "'";

        $sql = "INSERT INTO `{$table}` ({$field_names}) VALUES ({$values})";
        $result = self::$dbConnect->prepare($sql);
        $result->execute();
        return $result->rowCount();
    }

    /**
    * A method to update record by its id in a table.
    *
    * Loop through the array to get the field names and their corresponding
    * named placeholders. 
    * Use rtrim to remove the last comma from the field_details variable then
    * build an sql query.
    *
    * @param string table The table name
    * @param integer id The record id
    * @param array new_record Associative array of field name and its new value
    * @return integer Number of affected rows by the query
    */
    public static function updateById($table, $id, array $new_record)
    {
        $field_details = null;
        $placeholder_values = [];

        foreach ($new_record as $key => $value) {
            $field_details .= "$key = :$key,";
            $placeholder_values[":$key"] = $value;
        }

        $field_details = rtrim($field_details, ",");

        $sql = "UPDATE $table SET $field_details WHERE id = $id";
        $result =self::$dbConnect->prepare($sql);
        $result->execute($placeholder_values);

        return $result->rowCount();
    }

    /**
    * A method to delete a record by id in a table.
    *
    * @param string table The table name
    * @param integer id The record id
    * @return integer The number of the affected rows by the query
    */
    public static function deleteById($table, $id)
    {
        $result = self::$dbConnect->prepare("DELETE FROM {$table} WHERE id = $id");
        $result->execute();
        return $result->rowCount();
    }
}
