<?php 

namespace Urban;

/**
* A class that adds, update, retrieve and delete meanings of urban words
*
* Inherits from the Slang class in the Slang.php file. When an object is being
* instantiated, it has to be given a string that will act as the key of the 
* array whose value is the associated array of urban words. This key will be 
* used to access the various methods in the class, i.e. addData, deleteData, 
* retrieveData and updateData.
*
* @author Stacey Achungo
*/
class CrudWord extends SlangWord
{
    /** @var string $string Hold the key of the array whose value
    * is the associated array of urban words.
    */
    public $string;

    /**
    * A constructor
    *
    * Called on each instantiation of an object, to 
    * initialize the key before the object can be manipulated
    * using CRUD
    *
    * @param string string The key declared when instantiating this class
    * @throws Exception if the data given is not a string
    */
    public function __construct($string)
    {
        if (!is_string($string)) {
            throw new Exception("This is not a string!");
        }
        $this->string = $string;
    }

    /**
    * A method to add an associated array to the static array declared in slang
    *
    * Checks if an argument has been given, if it's not it throws an exception.
    * After ascertaining that an argument has been given, it checks if the value 
    * is an array. If it is, another check is done to see if the array does not
    * exists in the data array (in Slang.php file). If it is a new array, then 
    * the given data is added to the static associative array in Slang class. 
    *
    * @param array item The associated array to be added
    * @throws Exception If an argument is not given
    */
    public function addData($item)
    {
        if (isset($item)) {
            if (is_array($item)) {
                if (!in_array($item, SlangWord::$data)) {
                    SlangWord::$data[$this->string] = $item;
                }
            } else {
                throw new Exception("The argument is not an array!");
            }
        } else {
            throw new Exception("item is not set!");
        }
    }

    /**
    * A method to delete an urban word, including its description and sample
    * sentence
    *
    * Checks if the key given exists in the associative array contained in 
    * the slang array. If it does, then both the key and value are deleted.
    * An exception is thrown if the key does not exist.
    *
    * @param string slang The key of the array to be deleted
    * @throws Exception If the key given doesn't exist
    */
    public function deleteData($slang)
    {
        if (array_key_exists($slang, SlangWord::$data)) {
            unset(SlangWord::$data[$slang]);
        } else {
            throw new Exception("The key doesn't exist!");
        }
    }

    /**
    * A method to retrieve an array of urban words, inclusive of all its values
    *
    * Checks if the key passed as argument exists. If it does, then it return
    * the array associated with that key. If the key does not exist, it throws
    * an exception.
    *
    * @param string slang The key of the array to be retrieved
    * @return array The retrieved array
    * @throws Exception If key does not exist
    */
    public function retrieveData($slang)
    {
        if (array_key_exists($slang, SlangWord::$data)) {
            return (SlangWord::$data[$slang]);
        } else {
            throw new Exception("The key doesn't exist!");
        }
    }

    /**
    * A method to update the description of the urban word
    *
    * Called by passing two arguments to it, the key and the new value of the
    * description. A check is then carried out to ascertain that the key given 
    * exists, if it does not an exception is thrown. Once the key has been 
    * confirmed to exist, the description value is updated.
    *
    * @param string slang The key of the array to be updated
    * @param string updateVal The new value of the description
    * @throws Exception if the given key does not exist
    */
    public function updateData($slang, $updateVal)
    {
        if (array_key_exists($slang, SlangWord::$data)) {
            SlangWord::$data[$slang]["description"] = $updateVal;
        } else {
            throw new Exception("The key doesn't exist!");
        }
    }
}
