<?php
namespace Appeldorff\Newsmanager;

class DB extends \SQLite3
{
    const DB_PATH = '/../../newsmanager.sqlite';
    const DB_TEST_PATH = '/../../newsmanagertest.sqlite';
    public static $test = false;
    private static $tables_created = false;
    private static $instance;

    public function __construct()
    {

            if(self::$test) {
                parent::__construct(__DIR__.self::DB_TEST_PATH);
            } else {
                parent::__construct(__DIR__.self::DB_PATH);
            }
            $this->enableExceptions(true);
            if(!self::$tables_created) {
                $this->createTables();
            }
    }

    public function __destruct()
    {
        $this->close();
    }

    private function createTables()
    {
        $this->createTable(User::$definition);
        $this->createTable(NewsItem::$definition);
        $this->createTable(Acl::$definition);
        self::$tables_created = true;
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    private function createTable($definition = null)
    {
        if($definition == null) {
            return;
        }        
        $sql = 'CREATE TABLE IF NOT EXISTS '.$definition['table'].' (';
        foreach($definition['columns'] as $property => $columnDef) {

            $sql .= $columnDef['fieldName'].' '.$columnDef['fieldType'];

            if(array_key_exists('check',$columnDef)) {
                $sql .= ' '.$columnDef['check'];
            }
            $sql .= ', ';
        }
        $sql .= 'PRIMARY KEY('.$definition['primary_key'].')';

        if(array_key_exists('foreign_keys',$definition)) {
            foreach($definition['foreign_keys'] as $column => $ref) {
                $sql .= ', FOREIGN KEY('.$column.
                ') REFERENCES '.$ref['table'].'('.$ref['column'].')';
            }
        }
        $sql .=  ')';
        $this->exec($sql);
    }
}
