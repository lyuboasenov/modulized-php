<?php

require_once(__DIR__ . '/../domain/user.php');
require_once(__DIR__ . '/../domain/email.php');
require_once(__DIR__ . '/../domain/field.php');
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
      $builder =  SelectBuilder::from($this->table);
      foreach($this->model->getMetadata() as $name => $field) {
         if (!($field instanceof ForeignKeyField)) {
            $builder->field($name);
         }
      }

      return $builder;
   }

   public function getInsertCommandBuilder() {
      $builder = InsertBuilder::into($this->table);
      foreach($this->model->getMetadata() as $name => $field) {
         if ($name != 'id' && !($field instanceof ForeignKeyField)) {
            $builder->value($name, $this->model->$name);
         }
      }

      return $builder;
   }

   public function getUpdateCommandBuilder() {
      $builder = UpdateBuilder::table($this->table);
      foreach($this->model->getMetadata() as $name => $field) {
         if ($name != 'id' && !($field instanceof ForeignKeyField)) {
            $builder->set($name, $this->model->$name);
         }
      }

      return $builder->where('id=' . $this->model->id);
   }

   public function getDeleteCommandBuilder() {
      return DeleteBuilder::from($this->table)->where('id=' . $this->model->id);
   }
}