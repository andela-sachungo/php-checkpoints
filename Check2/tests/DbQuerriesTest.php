<?php 

namespace Stacey\Potatotests;

use Stacey\Potato\Models\DbQuerries;

class DbQuerriesTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $connDetails = array(
            'host' => 'localhost',
            'dbname' => 'potatorm',
            'user' => 'andela',
            'pass' => 'andela'
            );
        DbQuerries::connect($connDetails);
        DbSettingTest::createTable("cars");
    }

    public function tearDown()
    {
        DbQuerries::disconnect();
        DbSettingTest::dropTable("cars");
    }

    public function testAllRecordCanBeSelected()
    {
        $object = DbQuerries::selectAllRecord("cars");
        $this->assertEquals(1, count($object));
    }

    public function testASpecificRecordCanBeFetched()
    {
        $object = DbQuerries::selectSpecificRecord("cars", ['id'=>1]);
        $this->assertEquals(1, count($object));
    }

    public function testRecordCanBeInsertedInTable()
    {
        $data = [
        'name'=>'Jaguar',
        'color'=>'dark blue',
        'price'=>'$450,000'
        ];

        $object = DbQuerries::insertInTable("cars", $data);
        $this->assertEquals(1, $object);

        $object1 = DbQuerries::selectAllRecord("cars");
        $this->assertEquals(2, count($object1));
    }

    public function testUpdateRecordByIdCanBeDone()
    {
        $object = DbQuerries::updateById("cars", 1, ['color'=>'turqoise', 'price'=>'$1 million']);
        $this->assertEquals(1, $object);
    }

    public function testRecordCanBeDeletedById()
    {
        $object = DbQuerries::deleteById("cars", 1);
        $this->assertEquals(1, $object);

        $object1 = DbQuerries::selectAllRecord("cars");
        $this->assertEquals(0, count($object1));
    }
}
