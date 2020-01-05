<?php

require_once('model.php');
require_once('field.php');

class Email extends MOdel {
   public static function fromRawData($data) {
      $group = new Group();
      $group->initialize();

      foreach($data as $name => $value) {
         $group->setField($name, $value, false, false);
      }

      return $group;
   }

   protected function getFields() {
      return [
         new IntegerField('id', false, null),
         new CharField('email', false, null, 15),
         new IntegerField('user_id', false, 0),
      ];
   }
}