<?php
namespace Appeldorff\Test\Newsmanager;

use \PHPUnit_Framework_TestCase as TestCase;
use Appeldorff\Newsmanager\Acl;
use Appeldorff\Newsmanager\Newsitem;
use Appeldorff\Newsmanager\User;
use Appeldorff\Newsmanager\DB;

class AclTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		DB::$test = true;
		if(file_exists(__DIR__.DB::DB_TEST_PATH))
		{
			unlink(__DIR__.DB::DB_TEST_PATH);
		}
		
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
	
	public function testHasPermission()
	{
		$user = new User('Poul');
		$user->add();
		$user2 = new User('Jens');
		$user2->add();
		$user3 = new User('Ib');
		$user3->add();
		$newsitem = new Newsitem($user->id,'Test Article','This is a news item.');
		$newsitem->add();
		$acl = Acl::emptyObject();
		
		$this->assertTrue($acl->hasPermission($user,Acl::READ,$newsitem));
		$this->assertTrue($acl->hasPermission($user,Acl::WRITE,$newsitem));
		$this->assertTrue($acl->hasPermission($user,Acl::DELETE,$newsitem));
		
		$this->assertTrue($acl->hasPermission($user2,Acl::READ,$newsitem));
		$this->assertFalse($acl->hasPermission($user2,Acl::WRITE,$newsitem));
		$this->assertFalse($acl->hasPermission($user2,Acl::DELETE,$newsitem));
		
		$this->assertTrue($acl->hasPermission($user3,Acl::READ,$newsitem));
		$this->assertFalse($acl->hasPermission($user3,Acl::WRITE,$newsitem));
		$this->assertFalse($acl->hasPermission($user3,Acl::DELETE,$newsitem));
		
		$acl = new Acl($user2->id, Acl::WRITE,$newsitem->id);
		$acl->add();
		
		$this->assertTrue($acl->hasPermission($user,Acl::READ,$newsitem));
		$this->assertTrue($acl->hasPermission($user,Acl::WRITE,$newsitem));
		$this->assertTrue($acl->hasPermission($user,Acl::DELETE,$newsitem));
		
		$this->assertTrue($acl->hasPermission($user2,Acl::READ,$newsitem));
		$this->assertTrue($acl->hasPermission($user2,Acl::WRITE,$newsitem));
		$this->assertFalse($acl->hasPermission($user2,Acl::DELETE,$newsitem));
		
		$this->assertTrue($acl->hasPermission($user3,Acl::READ,$newsitem));
		$this->assertFalse($acl->hasPermission($user3,Acl::WRITE,$newsitem));
		$this->assertFalse($acl->hasPermission($user3,Acl::DELETE,$newsitem));
		
		$acl = new Acl($user3->id, Acl::DELETE,$newsitem->id);
		$acl->add();
		
		$this->assertTrue($acl->hasPermission($user,Acl::READ,$newsitem));
		$this->assertTrue($acl->hasPermission($user,Acl::WRITE,$newsitem));
		$this->assertTrue($acl->hasPermission($user,Acl::DELETE,$newsitem));
		
		$this->assertTrue($acl->hasPermission($user2,Acl::READ,$newsitem));
		$this->assertTrue($acl->hasPermission($user2,Acl::WRITE,$newsitem));
		$this->assertFalse($acl->hasPermission($user2,Acl::DELETE,$newsitem));
		
		$this->assertTrue($acl->hasPermission($user3,Acl::READ,$newsitem));
		$this->assertFalse($acl->hasPermission($user3,Acl::WRITE,$newsitem));
		$this->assertTrue($acl->hasPermission($user3,Acl::DELETE,$newsitem));
	}
}