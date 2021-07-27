<?php

namespace Application\Models;

use Frameworkitto\Model;

/**
 * This sample is a very simple implementation of model.
 * 
 * Manipulation functions:
 *    create()
 *    update($data) --> the id field must to be on array. ex: update(['id'=> 1, name=>'Jose']);
 *    delete($fitler) --> ex: delete([id=>5, active=>1]);
 * 
 * Magic Functions:
 * 
 * findBy<fieldname> ex: findByEmail
 * findFirstBy<fieldname> ex: findFirstById, findFirstByEmail
 * 
 * Helper functions:
 * 
 * beforeCreate() and beforeUpdate()
 * afterCreate() and afterUpdate()
 * 
 */

/**
 * 
-- SAMPLE TABLE STRUCTURE
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `recovery_token` varchar(255) DEFAULT NULL,
  `email_token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` text,
  `uuid` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1

*/

class UsersModel extends Model {

    //Use init function of base Model to set up basic parameters for a User model:
    public function init() {

        $this->tableName = 'users'; //This means this model will interface with the 'users' table on the database.
        //These are the fields which you will have created on the database, alongisde the default 'created', 'modified', 'deleted' and UUID fields.
        $this->fields = [
            'id', 'email', 'password','active', 'recovery_token', 'name', 'address',
            'email_token', $this->createdAtField, $this->modifiedAtField, $this->deletedAtField,
            $this->uniqueIdField,
        ]; 
        $this->idField = 'id'; //The field that is supposed to be the main indentifier
        $this->uniqueFields = ['email']; //Fields that are supposed to be unique

        $this->orderBy = 'nome'; //Which field should Frameworkitto use to order results returned from a find() call.

    }
  
  
    //Here we are setting a beforeCreate to encrypt the password before it's sent to a database.
    public function beforeCreate($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); //Encrypt password before storing.
        $data['email_token'] = md5(uniqid()); // Email confirmation token
        return($data);
    }

    //The same is being set up for when a password is being updated.
    public function beforeUpdate($data) {
        if( isset($data['password']) && trim($data['password']) ) $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); //Encrypt password before storing.
        return($data);
    }

    //Please look at the parent Model class to learn more functions you can use on these child models.
}




