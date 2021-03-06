<?php

require_once('model.php');
require_once('field.php');

class Email extends MOdel {
   public static function fromRawData($data) {
      $email = new Email();
      $email->initialize();

      foreach($data as $name => $value) {
         $email->setField($name, $value, false, false);
      }

      return $email;
   }

   protected function getFields() {
      return [
         new IntegerField('id', false, null),
         new CharField('email', false, null, 15)
      ];
   }
}