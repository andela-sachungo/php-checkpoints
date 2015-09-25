<?php

use Urban\CrudWord;

class CrudWordTest extends PHPUnit_Framework_TestCase
{
    private $slang;

    protected function setUp()
    {
        $this->slang = new CrudWord("init");
        $this->slang->addData([
                        "slang" => "init",
                        "description" => "Lazy way of saying isn't it",
                        "sample-sentence" => "That movie was awesome, init?!"
                        ]);
    }

    public function testAddData()
    {
        $counter = count($this->slang->getdata());
        $this->assertEquals(1, $counter);
    }

    public function testDeleteData()
    {
        $this->slang->deleteData("init");
        $this->assertEquals(0, count($this->slang->getData()));
    }

    public function testUpdateData()
    {
        $Sentence = $this->slang->getData();
        $actual = $Sentence["init"]["description"];
        $this->slang->updateData("init", "saying isn't it?");
        $Sentence1 = $this->slang->getData();
        $result = $Sentence1["init"]["description"];
        $this->assertFalse($actual == $result);
    }
    
    public function testRetrieveData()
    {
        $this->assertEquals($this->slang->retrieveData("init"),
                            [
                            "slang" => "init",
                            "description" => "Lazy way of saying isn't it",
                            "sample-sentence" => "That movie was awesome, init?!"
                            ]);
    }
}
