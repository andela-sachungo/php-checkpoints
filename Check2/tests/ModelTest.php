<?php 
namespace Stacey\Potatotests;
use Stacey\Potato\Models\DbQuerries;
use Stacey\Potato\Models\Model;

class Modeltest extends \PHPUnit_Framework_TestCase
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
        Model::setTableName("cars");
        DbSettingTest::createTable(Model::getTableName());
	}

	public function tearDown()
	{
		DbQuerries::disconnect();
		DbSettingTest::dropTable(Model::getTableName());
	}

	public function testTableNameIsSet()
	{
		$tableName = Model::setTableName("stationery");
		$tableName1 = Model::getTableName();
		$this->assertSame('stationery', $tableName1);
	}

	public function testAllDataCanBeRetrieved()
	{
		$resultQuery = Model::getAll();
		self::assertInternalType('array', $resultQuery);
	}

	public function testRecordCanBeFoundInDatabase()
	{
		$resultQuery= Model::find(1);
        self::assertInternalType('object', $resultQuery);
	}

	/**
      * @expectedExceptionMessage Record does not exist!!
      */
	public function testExceptionIsThrownIfRecordIsNotFound()
	{
		$resultQuery= Model::find(2);
	}

	public function testRecordInsertedInTable()
	{
		$insertion = new Model();
		$insertion->name = 'Land Rover';
		$insertion->color ='red';
        $insertion->price ='$54,850';
        $insertion->save();

       $resultQuery = Model::findNotByID(['name'=>'Land Rover']);
       self::assertInternalType('object', $resultQuery);
	}

	/**
     * @expectedExceptionMessage findNotByID only accepts non-integer argument!
     */
	public function testFindNotByIdThrowsExceptionWhenIntegerIsGiven()
	{
		$resultQuery = Model::findNotByID(['id'=>2]);
	}

	/**
     * @expectedExceptionMessage Record does not exist!!
     */
	public function testFindNotByIdThrowsExceptionIfRecordNotFound()
	{
		$resultQuery = Model::findNotByID(['name'=>'Toyota']);
	}

	public function testRecordIsUpdated()
	{
		$resultQuery = new Model();
		$resultQuery= Model::find(1);
        $resultQuery->color = 'brown';
        $resultQuery->save();

        $resultQuery= Model::find(1);
        self::assertInternalType('object', $resultQuery);
	}

	public function testRecordIsDeleted()
	{
		$resultQuery = new Model();
		$resultQuery = Model::destroy(1);
		
		$resultQuery = Model::getAll();
		$this->assertEquals(0, count($resultQuery));
	}
}