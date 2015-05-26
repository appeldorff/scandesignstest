<?php
namespace Appeldorff\Newsmanager;

class Newsitem extends DBObject implements NewsitemInterface
{
    public $id;
    public $id_author;
    public $title;
    public $content;

    public static $definition = array(
        'table' => 'newsitems',
        'primary_key' => 'id_newsitem',
        'foreign_keys' => array(
            'id_author' => array(
                'table' => 'users',
                'column' => 'id_user'
                )
            ),
        'columns' => array(
            'id' => array('fieldName' => 'id_newsitem', 'fieldType' => 'INTEGER', 'autoincrement' => true ),
            'title' => array('fieldName' => 'name', 'fieldType' => 'TEXT'),
            'content' => array('fieldName' => 'content', 'fieldType' => 'TEXT' ),
            'id_author' => array('fieldName' => 'id_author', 'fieldType' => 'INTEGER' )
            )
        );    

    public function __construct($id_author, $title, $content)
    {
        $this->id_author = $id_author;
        $this->title = $title;
        $this->content = $content;
    }

    public static function emptyObject()
    {
        return new Newsitem(null, null, null);
    }
}