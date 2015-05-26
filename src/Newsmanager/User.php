<?php
namespace Appeldorff\Newsmanager;

class User extends DBObject implements UserInterface
{	
	public $name;
	public $id;
	
	public static $definition = array(
		'table' => 'users',
		'primary_key' => 'id_user',
		'columns' => array(
			'id' => array('fieldName' => 'id_user','fieldType' => 'INTEGER', 'autoincrement' => true ),
			'name' => array('fieldName' => 'name','fieldType' => 'TEXT')
			//password could be added here
			)
		);
		
	public function __construct($name){
		$this->name = $name;
	}
	
	public static function emptyObject()
	{
		return new User(null);
	}
	
}