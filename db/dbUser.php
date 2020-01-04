<?php

require_once(__DIR__ . '\..\domain\user.php');
require_once('commands\dbModel.php');

class DbUser extends User implements IDbModel {

   private $dbModel;

   public function __construct($data) {
      $this->initialize();

      if (!is_null($data)) {
         foreach($data as $name => $value) {
            $this->setField($name, $value, false, false);
         }
      }

      $this->dbModel = new DbModel('users', $this->metadata, $this->values);
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