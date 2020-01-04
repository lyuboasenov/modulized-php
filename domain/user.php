<?php

require_once('model.php');
require_once('field.php');

class User extends Model {

   public function __construct($data) {
      if (!is_null($data)) {
         foreach($data as $name => $value) {
            $this->setField($name, $value, false, false);
         }
      }
   }

   protected function getFields() {
      return [
         new IntegerField('id', false, null),
         new CharField('username', false, null, 15),
         new CharField('password', false, null, 35),
         new IntegerField('age', true, 0),
         new IntegerField('role', false, null),
      ];
   }
}