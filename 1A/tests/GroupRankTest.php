<?php

use Urban\GroupRank;

class GroupRankTest extends PHPUnit_Framework_TestCase
{
    private $words;

    protected function setUp()
    {
        $this->words = new GroupRank([
        	"Tight"=>[
                    "slang" => "Tight",
                    "description" => "When someone performs an 
								    awesome task",
                    "sample-sentence" => "Prosper is Tight, Tight!!!"
                    ]
                ]);
    }

    public function testRank()
    {
    	$sorted = $this->words->rank("Tight");
        
        $this->assertEquals($sorted, ["Tight" => 2, "is" => 1, "Prosper" => 1]);
    }
}
