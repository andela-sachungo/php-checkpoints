<?php

namespace Urban;

/**
* A Slang class that possess a static associative array
*
* This class holds the static associative array, which contains
* urban words. The keys for the array are slang, description
* and sample-sentence.
*
* @author Stacey Achungo
*/
class SlangWord
{
    /** @var array $data Should hold the associated array */
    protected static $data = [];

    /**
    * A method to get the static associated array as a return
    *
    * @return array Return the associative array of slangs
    */
    public function getData()
    {
        return SlangWord::$data;
    }
}
