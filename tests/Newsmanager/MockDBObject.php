<?php
namespace Appeldorff\Test\Newsmanager;
use Appeldorff\Newsmanager\DBObject;
class MockDBObject extends DBObject
{
	public $id;
	public static $definition = 
	array(

		'table' => 'mock_table',

		'primary_key' => 'id_mock',

		'columns' => array(

		'id' => array('fieldName' => 'id_mock','fieldType' => 'INTEGER', 'autoincrement' => true )

		)

	);
	
	public static function emptyObject()
	{
		return new MockDBObject();
	}
}