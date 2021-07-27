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

    /**
     * Configure model object. 
     */
    public function init() {

        $this->tableName = 'users';
        $this->fields = [
            'id', 'email', 'password','active', 'recovery_token', 'name', 'address',
            'email_token', $this->createdAtField, $this->modifiedAtField, $this->deletedAtField,
            $this->uniqueIdField,
        ];
        $this->idField = 'id';
        $this->uniqueFields = ['email'];

        $this->orderBy = 'nome';

    }
  
  
    /**
     * Before Creation function
     * This function is always called before instantiating a new user. Can be used to set default values for an user object.
     * @param $data Data of object that is going to be created
     */
    public function beforeCreate($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); //Encrypt password before storing.
        $data['email_token'] = md5(uniqid()); // Email confirmation token
        return($data);
    }

    /**
     * Before Update function
     * This function is always called before updating an existing user. Can be used to validate/process updated data before sending to a database, for instance.
     * @param $data Data of object that is going to be created
     */
    public function beforeUpdate($data) {
        if( isset($data['password']) && trim($data['password']) ) $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); //Encrypt password before storing.
        return($data);
    }

}




