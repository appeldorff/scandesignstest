<?php
namespace Appeldorff\Newsmanager;

class Acl extends DBObject implements AclInterface
{
	public $id;
	public $id_newsitem;
	public $id_user;
	public $access_type;
	
	public static $definition = array(
		'table' => 'newsitem_access',
		'primary_key' => 'id_newsitem_access',
		'foreign_keys' => array(
			'id_user' => array(
				'table' => 'users',
				'column' => 'id_user'
				),
			'id_newsitem' => array(
				'table' => 'newsitems',
				'column' => 'id_newsitem'
				)
			),
		'columns' => array(
			'id' => array('fieldName' => 'id_newsitem_access','fieldType' => 'INTEGER', 'autoincrement' => true ),
			'id_newsitem' => array('fieldName' => 'id_newsitem','fieldType' => 'INTEGER' ),
			'id_user' => array('fieldName' => 'id_user','fieldType' => 'INTEGER' ),
			'access_type' => array('fieldName' => 'access_type','fieldType' => 'TEXT',
				'check' => "CHECK(access_type = 'read' OR access_type = 'write' OR access_type = 'delete')"
				)
			)
		);	
	
	public function __construct($id_user, $access_type, $id_newsitem)
	{
		$this->id_newsitem = $id_newsitem;
		$this->id_user = $id_user;
		$this->access_type = $access_type;
	}

	public static function emptyObject()
	{
		return new Acl(null,null,null);
	}

	public function hasPermission(UserInterface $user, $type = self::READ, NewsItemInterface $entity)
	{
		
		if($user->id == $entity->id_author) {
			return true;
		}
		$this->id = null;
		$this->id_user = null;
		$this->access_type = $type;
		$this->id_newsitem = $entity->id;
		if($type != self::READ)
		{
			$this->id_user = $user->id;
		}
		if($this->id_user == null)
		{
			$stmt = $this->getSearchStmt();
			$stmt->bindValue(':id_newsitem',$this->id_newsitem);
			$stmt->bindValue(':access_type',$this->access_type);
			$result = $stmt->execute();
			$result = $result->fetchArray();
			$stmt->close();
			if($result===false) //Noone has readpermission explicitly set, hence everyone has read permission
			{
				return true;
			}
			else
			{
				$this->id_user = $user->id; //Some people have read permissions set, hence we must ensure the specific user has read permission
			}
			
		}
		$stmt = $this->getSearchStmt();
		$stmt->bindValue(':id_newsitem',$this->id_newsitem);
		$stmt->bindValue(':access_type',$this->access_type);
		$stmt->bindValue(':id_user',$this->id_user);
		$result = $stmt->execute();
		$result = $result->fetchArray();
		$stmt->close();
		if($result === false) //Noone has permission explicitly set, hence everyone has read permission
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}