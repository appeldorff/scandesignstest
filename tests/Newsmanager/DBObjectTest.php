<?php
namespace Appeldorff\Test\Newsmanager;

use \PHPUnit_Framework_TestCase as TestCase;
use Appeldorff\Newsmanager\DB;
use Appeldorff\Newsmanager\DBObject;
use Appeldorff\Newsmanager\User;
use Appeldorff\Test\Newsmanager\MockDBObject;

class DBObjectTest extends TestCase
{
		
	public static function setUpBeforeClass()
	{
		DB::$test = true;
		if(file_exists(__DIR__.DB::DB_TEST_PATH))
		{
			unlink(__DIR__.DB::DB_TEST_PATH);
		}
		Db::getInstance()->exec('CREATE TABLE mock_table (id_mock INTEGER PRIMARY KEY)');	
	}
	
	public function setUp()
	{
		Db::getInstance()->exec('DROP TABLE mock_table');
		Db::getInstance()->exec('CREATE TABLE mock_table (id_mock INTEGER PRIMARY KEY)');
	}
	
	public static function tearDownAfterClass()
	{
		DB::getInstance()->close();
		DB::$test = false;
		if(file_exists(__DIR__.DB::DB_TEST_PATH))
		{
			unlink(__DIR__.DB::DB_TEST_PATH);
		}
	}	
	
	public function testWithID()
	{
		$mock = new MockDBObject();
		$mock->add();
		$this->assertEquals(1,$mock->id);
		$mock2 = MockDBObject::withId(1);
		$this->assertEquals(1,$mock2->id);
	}		
	
	public function testEmptyObject()
	{
		$mock = MockDBObject::emptyObject();
		$this->assertNull($mock->id);
	}
	
	public function testAdd()
	{
		$result = DB::getInstance()->query('SELECT COUNT(*) FROM mock_table')->fetchArray();
		$this->assertEquals(0,$result['COUNT(*)']);
		$mock = new MockDBObject();
		$mock->add();
		$result = DB::getInstance()->query('SELECT COUNT(*) FROM mock_table')->fetchArray();
		$this->assertEquals(1,$result['COUNT(*)']);
	}	
}