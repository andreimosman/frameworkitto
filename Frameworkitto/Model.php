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

    //Below are some values that you can set when instantiating a child of this model using the init() function.
    
    public $tableName;  //On your child model, set this to the respective database table name that the child is related to. 
    public $fields = [];
    public $idField = 'id';
    public $uniqueFields = [];

    // Use uuids to make ids unpredictable. 
    // If you don't want you can set $this->uniqueIdField = null on init() of subclass 
    // and remove it from $this->fields array;
    public $uniqueIdField = 'uuid'; 

    // These fields are used to store the date and time by which model objects have been created, modified or deleted.
    // It is recommended that you get these fields set up for your model on the respective database table.
    public $createdAtField = 'created_at';
    public $modifiedAtField = 'modified_at';
    public $deletedAtField = 'deleted_at'; //This field is used to mark objects as deleted without actually losing the data.

    // Use this on your child model to specify a field by which the results of a find() call should be ordered by.
    public $orderBy = null; //TODO: Allow order by ASC or DESC

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

    /**
     * Initialize function
     * Set parameters and configurations that are specific of the model.
     */
    public function init() { } //This function should be instantiated from the child.

    public function getFields() {
        return count($this->fields) ? implode(",", $this->fields) : '*';
    }

    public function beginTransaction() {
        return self::$pdo->beginTransaction();
    }

    public function commit() {
        return self::$pdo->commit();
    }

    public function rollback() {
        return self::$pdo->rollback();
    }

    /**
     * Get Conditions And Bind
     * Construct PDO supported attributes for MySQL queries to select one or many objects. 
     * @param array $filter Array of key/pair values with the name of the parameter, and the value it needs to contain. Only supports singular values at the moment.
     */
    public function getConditionsAndBind($filter) : Array{
        $conditions = [];
        $bind = [];
        foreach($filter as $field => $value) {
            $op = " = ";
            // TODO: Add support to IN () queries
            // if( is_array($value) ) $op = " IN ";

            $conditions[] = $field . " ".$op." :" . $field;
            $bind[":".$field] = $value;
        }

        return [
            "conditions" => implode(" AND ", $conditions),
            "bind" => $bind,
        ];
    }

    /**
     * Find Object(s) of model
     * Function used to interface with a database to find one or many objects of a model, based on filters if any are given. Supports only MySQL at the moment.
     * @param array $filter Array of key/pair values with the name of the parameter, and the value it needs to contain. Only supports singular values at the moment.
     * @param bool $forUpdate Set this to TRUE if you want to lock the found objects from being updated by other functions. Should only be used inside transactions. Default is FALSE.
     */
    public function find($filter=[],$forUpdate=false) { // Filter is to compose a very basic AND filter
        
        $fields = $this->getFields();
        
        $sql = "SELECT $fields FROM {$this->tableName}";

        //Construct parameters for query.
        $conditionsAndBind = $this->getConditionsAndBind($filter);


        //$conditionsAndBind["conditions"];
        $conditions = [];
        //This is used to not return any objects that were marked as deleted.
        if( $this->deletedAtField ) $conditions[] = $this->deletedAtField . " IS NULL";
        if( $conditionsAndBind["conditions"] ) $conditions[] = $conditionsAndBind["conditions"];

        $conditionsWhereSQL = implode(" AND ", $conditions);    
        if($conditionsWhereSQL) $sql .= " WHERE " . $conditionsWhereSQL;

        if( $this->orderBy ) $sql .= " ORDER BY " . $this->orderBy;

        if( $forUpdate ) $sql .= " FOR UPDATE ";

        return($this->execute($sql,$conditionsAndBind["bind"]));

    }

    /**
     * Find Object of model
     * Function used to interface with a database to find only one object of a model, based on filters if any are given. Supports only MySQL at the moment. If a selection results in many objects being fetched, only the first one will be returned.
     * @param array $filter Array of key/pair values with the name of the parameter, and the value it needs to contain. Only supports singular values at the moment.
     * @param bool $forUpdate Set this to TRUE if you want to lock the found objects from being updated by other functions. Should only be used inside transactions. Default is FALSE.
     */
    public function findFirst($filter=[],$forUpdate=false) {
        //Calls base find function above.
        $data = $this->find($filter,$forUpdate);
        //If a selection results in many objects being fetched, only the first one will be returned.
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

    /**
     * Before Creation function
     * Use this function if you have code that needs to be ran before a new object of the model is created on the database. Can be used for values that need to be processed in a certain way before that.
     * @param $data Data of object that is going to be created
     */
    public function beforeCreate($data) {
        //Instantiate this function on the child model to run code specifically for it.
        return $data;
    }

    /**
     * After Creation function
     * Use this function if you have code that needs to be ran after a new object of the model is created on the database. Can be used to validate an object's creation after it's done, for example.
     * @param $data Data of object that is going to be created
     */
    public function afterCreate($data) {
        //Instantiate this function on the child model to run code specifically for it.
        return $data;
    }

    /**
     * Generate UUID
     * Generates a 128-bit universally unique identifier.
     * @param int id Base numeric ID from which the UUID should be made from.
     */
    public function uniqueId($id = 0) {
        $id = (int)$id;
        return uniqid(str_pad(dechex($id),8,"0",STR_PAD_BOTH));
    }

    protected $isSavingUniqueId = false;

    /**
     * Save Unique ID If Not Set
     * Generates a UUID for a model object if there is none, or the database doesn't support it.
     * @param $data Data of object to check if UUID exists
     */
    public function saveUniqueIdIfNotSet($data) {

        if( !$this->isSavingUniqueId && $this->uniqueIdField && !isset($data[ $this->uniqueIdField ]) ){
            $this->isSavingUniqueId = true;
            $data[ $this->uniqueIdField ] = $this->uniqueId($data["id"]);
            $data = $this->update($data);
            $this->isSavingUniqueId = false;
        }

        return($data);
    }

    /**
     * Create model object
     * Function used to interface with a database to create a new object of a model. Also generates a UUID for the object if there is none set. Supports only MySQL at the moment.
     * @param $data Data of object
     */
    public function create($data) {
        //Check the data first to see if there are no constraint violations.
        if( $this->isViolatingUniqueConstraint($data) ) throw new ModelException('Unique constraint violation');

        $data = $this->beforeCreate($data);

        $fields = [];
        $values = [];
        $bind = [];
        foreach($data as $field => $value) {
            if( !in_array($field,$this->fields) ) continue;
            $fields[] = $field;
            $values[] = ':'.$field;
            $bind[':'.$field] = $value;
        } //Deconstruct object data in order to create insert statement
        $sql = "INSERT INTO {$this->tableName} ( " .implode(",",$fields) . ") VALUES (" . implode(",",$values) . ")"; //Prepare MySQL statement for object insertion
        $this->execute($sql,$bind);

        //Fetch insertion in order to enable post-creation validation and other processing.
        $id = self::$pdo->lastInsertId();
        $savedData = $this->findFirstById($id);

        //If UUIDs are enabled on the model, Frameworkitto generates one and updates the object with it.
        $savedData = $this->saveUniqueIdIfNotSet($savedData); 

        return($this->afterCreate($savedData));

    }

    /**
     * Before Creation function
     * Use this function if you have code that needs to be ran before an existing object of the model is updated on the database. Can be used for values that need to be processed in a certain way before that.
     * @param $data Data of object that is going to be updated
     */
    public function beforeUpdate($data) {
        //Instantiate this function on the child model to run code specifically for it.
        return($data);
    }

    /**
     * After Creation function
     * Use this function if you have code that needs to be ran after an existing object of the model is updated on the database. Can be used to validate an object's creation after it's done, for example.
     * @param $data Data of object that is going to be updated
     */
    public function afterUpdate($data) {
        //Instantiate this function on the child model to run code specifically for it.
        return($data);
    }

    /**
     * Update function
     * Function used to interface with a database to update an existing object of a model. Supports only MySQL at the moment.
     * @param $data Data of object that is going to be updated
     */
    public function update($data) {
        $data = $this->beforeUpdate($data);

        // Only update uniqueId when is appropriated
        if( $this->uniqueIdField && !$this->isSavingUniqueId ) {
            unset($data[ $this->uniqueIdField ] );
        }

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
        $savedData = $this->saveUniqueIdIfNotSet($savedData);
        
        return($this->afterUpdate($savedData));
    }

    /**
     * Delete function
     * Function used to interface with a database to mark an object of a model as deleted. Supports only MySQL at the moment.
     * @param $data Data of object that is going to be deleted
     */
    public function delete($filter) {

        if( !$filter || !count($filter) ) throw new ModelException('Delete without filter not allowed');

        $conditionsAndBind = $this->getConditionsAndBind($filter);
        if( !$conditionsAndBind["conditions"] ) throw new ModelException('Delete without filter not allowed');

        //the deletedAtField is used so the object does not need to actually be deleted, but just "marked" as such.
        if($this->deletedAtField) {
            $sql = "UPDATE " . $this->tableName . " SET " . $this->deletedAtField . " = now() WHERE " . $conditionsAndBind["conditions"];
        } else {
            $sql = "DELETE FROM " . $this->tableName . " WHERE " . $conditionsAndBind["conditions"];
        }

        return $this->execute($sql,$conditionsAndBind["bind"]);

    }



}
