<?php
namespace Appeldorff\Test\Newsmanager;

use \PHPUnit_Framework_TestCase as TestCase;
use Appeldorff\Newsmanager\DB;

class DBTest extends TestCase
{
	
	public function testGetInstance()
	{
		$db = \PHPUnit_Framework_Assert::getStaticAttribute('Appeldorff\Newsmanager\DB','instance');
		$this->assertNull($db);
		$db = DB::getInstance();
		$this->assertTrue($db != null);
		$db = \PHPUnit_Framework_Assert::getStaticAttribute('Appeldorff\Newsmanager\DB','instance');
		$this->assertTrue($db != null);
		
	}
    
}
