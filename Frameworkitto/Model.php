<?php
/**
 * Very basic model.
 * 
 * Written by Andrei Mosman <andrei.mosman@gmail.com>
 * 
 * TODO: Exception
 * 
 */

namespace Frameworkitto;

Class Model {

    protected static $pdo;
    public static function setPDO(\PDO $pdo) {
        self::$pdo = $pdo;
    }
    public static function getPDOInstance() {
        if( !self::$pdo ) throw new ModelException("PDO not configurated");
        return(self::$pdo);
    }

    public $tableName;  // Default table to perform crud operations
    public $fields = [];
    public $idField = 'id';
    public $uniqueFields = [];

    public $createdAtField = 'created_at';
    public $modifiedAtField = 'modified_at';
    public $deletedAtField = 'deleted_at';

    public $orderBy = null;

    public function __construct() {
        self::getPDOInstance(); // It will throw an exception if PDO is not set.
        $this->init();
    }
    // FindFirstBy<Field>
    public function __call($function,$params) {

        if( !preg_match('/(findFirst|find)By(.*)/i', $function, $matches) ) {
            // Unsupported function
            throw new ModelException('Unsupported function');
        }

        $method = $matches[1];
        $field = $matches[2];

        if( strtolower($field) == "id" ) $field = $this->idField; // Id always point to idField

        $fieldList = array_combine(array_map('strtolower', $this->fields), $this->fields);
        if( !in_array(strtolower($field),$fieldList) ) throw new ModelException('Field does not exists');

        if( in_array($method,['find','findFirst']) ) return $this->$method([$field=>$params[0]]);

        throw new Exception('Method not found');

    }

    public function init() { } // Instantiate on child

    public function getFields() {
        return count($this->fields) ? implode(",", $this->fields) : '*';
    }

    public function beginTransaction() {
        return self::$pdo->beginTransaction();
    }

    public function commit() {
        return self::$pdo->beginTransaction();
    }

    public function rollback() {
        return self::$pdo->beginTransaction();
    }


    public function getConditionsAndBind($filter) : Array{
        $conditions = [];
        $bind = [];
        foreach($filter as $field => $value) {
            $conditions[] = $field . " = :" . $field;
            $bind[":".$field] = $value;
        }

        return [
            "conditions" => implode(" AND ", $conditions),
            "bind" => $bind,
        ];
    }

    public function find($filter=[],$forUpdate=false) { // Filter is to compose a very basic AND filter
        
        $fields = $this->getFields();
        
        $sql = "SELECT $fields FROM {$this->tableName}";

        $conditionsAndBind = $this->getConditionsAndBind($filter);


        //$conditionsAndBind["conditions"];
        $conditions = [];
        if( $this->deletedAtField ) $conditions[] = $this->deletedAtField . " IS NULL";
        if( $conditionsAndBind["conditions"] ) $conditions[] = $conditionsAndBind["conditions"];

        $conditionsWhereSQL = implode(" AND ", $conditions);    
        if($conditionsWhereSQL) $sql .= " WHERE " . $conditionsWhereSQL;

        if( $this->orderBy ) $sql .= " ORDER BY " . $this->orderBy;

        if( $forUpdate ) $sql .= " FOR UPDATE ";

        return($this->execute($sql,$conditionsAndBind["bind"]));

    }

    public function findFirst($filter=[],$forUpdate=false) {
        $data = $this->find($filter,$forUpdate);
        return count($data) ? $data[0] : null;
    }

    //public function findFirstById($id) {
    //    return($this->findFirst([$this->idField => $id]));
    //}

    public function execute($sql,$bind=[]) {
        $sth = self::$pdo->prepare($sql);
        $sth->execute($bind);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        return($sth->fetchAll());
    }

    public function isViolatingUniqueConstraint($data) {
        $fieldList = $this->uniqueFields;
        $fieldList[] = $this->idField;

        foreach($fieldList as $field) {
            $value = @$data[$field];
            $record = $this->findFirst([$field=>$value]);
            if( $record ) return true;
        }

    }

    // Override on specific model when needed
    public function beforeCreate($data) {
        return $data;
    }

    public function afterCreate($data) {
        return $data;
    }

    public function create($data) {
        if( $this->isViolatingUniqueConstraint($data) ) throw new ModelException('Unique constraint vaiolation');

        $data = $this->beforeCreate($data);

        $fields = [];
        $values = [];
        $bind = [];
        foreach($data as $field => $value) {
            if( !in_array($field,$this->fields) ) continue;
            $fields[] = $field;
            $values[] = ':'.$field;
            $bind[':'.$field] = $value;
        }
        $sql = "INSERT INTO {$this->tableName} ( " .implode(",",$fields) . ") VALUES (" . implode(",",$values) . ")";
        $this->execute($sql,$bind);

        $id = self::$pdo->lastInsertId();

        $savedData = $this->findFirstById($id);

        return($this->afterCreate($savedData));

    }

    public function beforeUpdate($data) {
        return($data);
    }

    public function afterUpdate($data) {
        return($data);
    }

    public function update($data) {
        $data = $this->beforeUpdate($data);

        if( !$data[$this->idField] ) throw new ModelException("Didn't find table id");

        $sql = "UPDATE " . $this->tableName . " SET ";

        $bind = [];
        $fieldSet = [];

        foreach( $data as $field=>$value ) {

            if( in_array($field,$this->fields) ) {

                if( $field == $this->modifiedAtField ) {
                    $fieldSet[] = $field . " = now()";
                    continue;
                }

                $bind[':'.$field] = $value;
                if( $field == $this->idField ) continue; // Don't update id

                $fieldSet[] = $field . " = :" . $field;            
            }
        }

        $sql .= implode(", ", $fieldSet);

        $sql .= " WHERE " . $this->idField . " = :" . $this->idField;

        $this->execute($sql,$bind);

        $savedData = $this->findFirstById($data[$this->idField]);
        return($this->afterUpdate($savedData));
    }

    public function delete($filter) {

        if( !$filter || !count($filter) ) throw new ModelException('Delete without filter not allowed');

        $conditionsAndBind = $this->getConditionsAndBind($filter);
        if( !$conditionsAndBind["conditions"] ) throw new ModelException('Delete without filter not allowed');

        if($this->deletedAtField) {
            $sql = "UPDATE " . $this->tableName . " SET " . $this->deletedAtField . " = now() WHERE " . $conditionsAndBind["conditions"];
        } else {
            $sql = "DELETE FROM " . $this->tableName . " WHERE " . $conditionsAndBind["conditions"];
        }

        return $this->execute($sql,$conditionsAndBind["bind"]);

    }



}
