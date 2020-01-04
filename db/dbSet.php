<?php

require_once(__DIR__ . '\..\domain\set.php');

class DbSet extends Set {
   private $db;
   private $dbType;

   public function __construct($db, $domainType, $dbType) {

      if (!($dbType instanceof $domainType)) {
         throw new ErrorException('DbType "' . $dbType . '" does not inherit from "' . $domainType . '".');
      }

      parent::__construct($domainType);

      $this->db = $db;
      $this->dbType = $dbType;
   }

   protected function saveObjectInternal(Model $obj) {
      $dbObj = $obj instanceof $this->dbType ? $obj : $this->dbType::fromDomainModel($obj);

      $command = null;
      if (is_null($dbObj->id)){
         $command = $dbObj->getInsertCommandBuilder()->build($this->db);
      } else {
         $command = $dbObj->getUpdateCommandBuilder()->build($this->db);
      }

      $result = command.executeScalar();
      if ($result != 1) {
         throw new ErrorException('Invalid row count on insert/update "' . $result . '".');
      }
   }

   protected function findByIdInternal($id) {
      return $this->findInternal('id=' . $id);
   }

   protected function findInternal($criteria) {
      $prototype = $this->dbType::getPrototype();
      $command = $prototype->getSelectCommandBuilder()
         ->where($criteria)
         ->build($db);

      return $this->dbType::fromRawData($command->executeQuery());
   }
}