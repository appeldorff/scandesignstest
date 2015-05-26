<?php
namespace Appeldorff\Newsmanager;

abstract class DBObject implements DBObjectInterface
{
    public static $definition = array();

    public static function withId($id)
    {
        $class = get_called_class();
        $obj = $class::emptyObject();
        $obj->id = $id;
        $obj->get();
        return $obj;

    }

    protected static function getAddStmt()
    {
        $values = ' VALUES (';
        $stmt  = 'INSERT INTO ';
        $stmt .= static::$definition['table'];
        $stmt .= '(';
        foreach(static::$definition['columns'] as $property => $columnDef) {
            $stmt .= $columnDef['fieldName'] . ',';
            $values .= ':'.$columnDef['fieldName'] . ',';
        }
        $values = substr($values,0,-1);
        $stmt = substr($stmt,0,-1);
        $stmt .= ')';
        $values .= ')';
        return DB::getInstance()->prepare($stmt . $values);
    }

    protected static function getObjectStmt()
    {
        return DB::getInstance()->prepare('SELECT * FROM ' . static::$definition['table'] . ' WHERE ' . static::$definition['columns']['id']['fieldName'].' = :id');
    }

    public function getSearchStmt()
    {
        $stmt = 'SELECT * FROM ' . static::$definition['table'] . ' WHERE ';
        $where = '';
        foreach(static::$definition['columns'] as $property => $columnDef) {
            if($this->$property != null) {
                if($where != '') {
                    $where.= ' AND ';
                }
                $where .= ':'.$columnDef['fieldName'] . '=' . $columnDef['fieldName'];
            }
        }
        return DB::getInstance()->prepare($stmt.$where);
    }

    public function add()
    {
        $stmt = self::getAddStmt();
        foreach(static::$definition['columns'] as $property => $columnDef) {
            if(!array_key_exists('autoincrement', $columnDef)) {
                if($this->$property == null ) { //One could add !array_key_exists('nullable',$columnDef) if any of the tables had nullable columns
                    return;
                }
                $stmt->bindValue(':' . $columnDef['fieldName'], $this->$property);
            } else{
                $stmt->bindValue(':' . $columnDef['fieldName'], null);
            }
        }       
        $result = $stmt->execute();

        if($result !== false) {
            $this->id = DB::getInstance()->lastInsertRowID();
        }
        $stmt->close();
    }

    public function get()
    {
        if($this->id == null){
            return;
        }
        $stmt = self::getObjectStmt();
        $stmt->bindValue(':id', $this->id);
        $result = $stmt->execute();
        $result = $result->fetchArray();

        if($result !== false) {
            foreach(static::$definition['columns'] as $property => $columnDef) {
                if(!$property != 'id') {
                    $this->$property = $result[$columnDef['fieldName']];
                }
            }
        }
        $stmt->close();
    }
}