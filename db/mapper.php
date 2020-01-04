<?php

require_once(__DIR__ . '/../domain/user.php');
require_once(__DIR__ . '/commands/selectBuilder.php');

class Mapper {
   private $table;
   private $model;

   private function __construct($table, $model) {
      $this->table = $table;
      $this->model = $model;
   }

   public static function fromDomainType($modelType) {
      return new Mapper($modelType, new $modelType(null));
   }

   public static function fromRawData($modelType, $data) {
      $result = array();

      foreach($data as $entry) {
         $result[] = new $modelType($entry);
      }

      return $result;
   }

   public static function fromDomainModel(Model $model) {
      return new Mapper(gettype($model), $model);
   }

   public function getSelectCommandBuilder() {
      return SelectBuilder::from($this->table)
         ->fields(array_keys($this->model->getMetadata()));
   }

   public function getInsertCommandBuilder() {

   }

   public function getUpdateCommandBuilder() {

   }

   public function getDeleteCommandBuilder() {

   }
}