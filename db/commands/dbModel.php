<?php

require_once(__DIR__ . '\..\iDbModel.php');
require_once('selectBuilder.php');

class DbModel implements IDbModel {
   private $table;
   private $metadata;
   private $values;

   public function __construct($table, $metadata, $values) {
      $this->table = $table;
      $this->metadata = $metadata;
      $this->values;
   }

   public function getSelectCommandBuilder() {
      return SelectBuilder::from($this->table)
         ->fields(array_keys($this->metadata));
   }

   public function getInsertCommandBuilder() {

   }

   public function getUpdateCommandBuilder() {

   }

   public function getDeleteCommandBuilder() {

   }
}