<?php 

use Stacey\Potato\CarCrud;

/**
* A class to unit test the CarCrud class.
*
* Tests the methods in CarCrud class to check if they work correctly.
* An exception thrown by the find() and destroy() function makes the test to
* have an error.
*
* The setUp() method is a PHPUnit built-in method that creates a fixture
* to be called before a test is executed
*
* Assign the id to find()n and destroy() in accordance with the data in your
* table.
*/
class CarCrudTest extends PHPUnit_Framework_TestCase
{
    protected $object;
    
    protected function setUp()
    {
        $this->object = new CarCrud();
    }

    public function testRetrievingData()
    {
        $this->object = CarCrud::getAll();
        self::assertInternalType('array', $this->object);
    }

    public function testInsertDataToDatabase()
    {
        $this->object->name = 'Land Rover';
        $this->object->price ='54,850';
        $this->object->color ='red';
        $this->object->save();

        $this->assertNotEmpty($this->object->save());
    }
    public function testFindDataInDatabase()
    {
        $this->object = CarCrud::find(2);
        $this->assertObjectHasAttribute('name', new CarCrud);
    }

    public function testUpdateDataInDatabase()
    {
        $this->object = CarCrud::find(2);
        $this->object->name = 'Cadillac';
        $this->object->color = 'brown';
        $this->assertInternalType('integer', $this->object->save());
    }

    public function testDeleteDataFromDatabase()
    {
        $this->object = CarCrud::destroy(3);
        $this->assertInternalType('integer', $this->object);
    }
}
