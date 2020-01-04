<?php

require_once(__DIR__ . '\..\domain\user.php');
require_once('commands\dbModel.php');

class DbUser extends User implements IDbModel {

   private $dbModel;

   private function __construct() {
   }

   public static function getPrototype(){
      $dbUser = new DbUser();
      $dbUser->initialize();

      $dbUser->dbModel = new DbModel('users', $dbUser->metadata, $dbUser->values);

      return $dbUser;
   }

   public static function fromRawData($data) {
      $result = array();
      foreach($data as $entry) {
         $prototype = DbUser::getPrototype();

         foreach($entry as $name => $value) {
            $prototype->setField($name, $value, false, false);
         }
         $result[] = $prototype;
      }

      return $result;
   }

   public static function fromDomainModel(User $user) {
      $dbUser = new DbUser();
      $dbUser->initialize();

      $dbUser->metadata = $user->metadata;
      $dbUser->values = $user->values;

      $dbUser->dbModel = new DbModel('users', $dbUser->metadata, $dbUser->values);

      return $dbUser;
   }

   public function getSelectCommandBuilder() {
      return $this->dbModel->getSelectCommandBuilder();
   }

   public function getInsertCommandBuilder(){
      return $this->dbModel->getInsertCommandBuilder();
   }

   public function getUpdateCommandBuilder(){
      return $this->dbModel->getUpdateCommandBuilder();
   }

   public function getDeleteCommandBuilder(){
      return $this->dbModel->getDeleteCommandBuilder();
   }
}