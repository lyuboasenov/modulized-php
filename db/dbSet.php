<?php

require_once(__DIR__ . '\..\domain\set.php');
require_once('mapper.php');

class DbSet extends Set {
   private $db;

   public function __construct($db, $domainType) {
      parent::__construct($domainType);

      $this->db = $db;
   }

   protected function saveObjectInternal(Model $model) {
      $mapper = Mapper::fromDomainModel($model);

      $command = null;
      if (is_null($model->id)){
         $command = $mapper->getInsertCommandBuilder()->build($this->db);
      } else {
         $command = $mapper->getUpdateCommandBuilder()->build($this->db);
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
      $mapper = Mapper::fromDomainType($this->domainModelType);
      $command = $mapper->getSelectCommandBuilder()
         ->where($criteria)
         ->build($db);

      $data = $command->executeQuery();
      $result = array();
      foreach($data as $entry) {
         $result[] = new $this->domainModelType($entry);
      }

      return $result;
   }
}