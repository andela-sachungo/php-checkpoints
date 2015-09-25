<?php 

namespace Urban;

/**
* A class that group the same words according to the number of occurrences
* 
* When an object is being instantiated, it has to be given an associative 
* array whose sample sentence will be grouped and ranked according to the
* number of occurrences.
*
* @author Stacey Achungo
*/
class GroupRank
{
    /**
    * A constructor
    *
    * Called on each instantiation of an object, to 
    * initialize the associative array before the array sample sentence
    * can be grouped and ranked
    *
    * @param array stringArray The array whose sample sentence it to be ranked
    * @throws Exception if the argument passed is not an array
    */
    public function __construct($stringArray)
    {
        if (!is_array($stringArray)) {
            throw new Exception("This is not an associative array!");
        }
        $this->data = $stringArray;
    }

    /**
    * A method to group and rank the words in the sample sentence
    *
    * Checks if the key passed as argument exists, if it does not it 
    * throws an exception. Once it has been confirmed that the key exists,
    * the sample sentence is retrieved and assigned to a variable as a string.
    * 
    * The string is then split using a regular expression and the returned
    * array is assigned to a variable, in this case $words.
    * Loop through the associative array in $words and count the frequency of
    * occurrence of each word, ignoring any empty strings.
    * 
    * Sort the resulting associative array of grouped words according to their 
    * frequency, in descending order.
    *
    * @param string item The key whose value contains an array with the sample
    * sentence
    * @return array The grouped words ranked in descending order, in terms
    * of frequency
    * @throws Exception if the key passed as argument does not exist
    */
    public function rank($item)
    {
        $arrayCount = [];

        if (!array_key_exists($item, $this->data)) {
            throw new Exception("The key doesn't exist!");
            ;
        }
    
        $sentence = $this->data[$item]['sample-sentence'];
        
        $words = preg_split("/[^A-Za-z]/", $sentence);
        
        foreach ($words as $index => $word) {
            if ($word == "") {
                array_slice($words, $index);
                continue;
            }

            if (isset($arrayCount[$word])) {
                $arrayCount[$word]++;
            } else {
                $arrayCount[$word] = 1;
            }
        }

        array_multisort($arrayCount, SORT_DESC);
        
        return $arrayCount;
    }
}
