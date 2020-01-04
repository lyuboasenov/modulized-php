<?php

require_once(__DIR__ . '/../domain/user.php');
require_once(__DIR__ . '/commands/selectBuilder.php');
require_once(__DIR__ . '/commands/insertBuilder.php');
require_once(__DIR__ . '/commands/updateBuilder.php');
require_once(__DIR__ . '/commands/deleteBuilder.php');

class Mapper {
   private $table;
   private $model;

   private function __construct($table, $model) {
      $this->table = $table;
      $this->model = $model;
   }

   public static function fromDomainType($modelType) {
      return new Mapper($modelType, new $modelType);
   }

   public static function fromDomainModel(Model $model) {
      return new Mapper(gettype($model), $model);
   }

   public function getSelectCommandBuilder() {
      return SelectBuilder::from($this->table)
         ->fields(array_keys($this->model->getMetadata()));
   }

   public function getInsertCommandBuilder() {
      $builder = InsertBuilder::into($this->table);
      foreach(array_keys($this->model->getMetadata()) as $field) {
         if ($field != 'id') {
            $builder->value($field, $this->model->$field);
         }
      }

      return $builder;
   }

   public function getUpdateCommandBuilder() {
      $builder = UpdateBuilder::table($this->table);
      foreach(array_keys($this->model->getMetadata()) as $field) {
         $builder->set($field, $this->model->$field);
      }

      return $builder->where('id=' . $this->model->id);
   }

   public function getDeleteCommandBuilder() {
      return DeleteBuilder::from($this->table)->where('id=' . $this->model->id);
   }
}