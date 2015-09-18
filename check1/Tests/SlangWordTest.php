<?php

use Urban\SlangWord;

class SlangWordTest extends PHPUnit_Framework_TestCase
{
    protected $slang;

    protected function setUp()
    {
        $this->slang = new SlangWord();
    }

    public function testGetData()
    {
        $this->assertEmpty($this->slang->getData());
    }
}
