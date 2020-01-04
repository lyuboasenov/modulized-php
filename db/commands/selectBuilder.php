<?php

require_once('command.php');

class SelectBuilder {
   private $table;
   private $fields;
   private $where;

   private function __construct($table) {
      $this->table = $table;
      $this->fields = array();
   }

   public static function from($table) {
      return new SelectBuilder($table);
   }

   public function field($field) {
      $this->fields[] = $field;
      return $this;
   }

   public function fields($fields) {
      $this->fields = array_merge($this->fields, $fields);
      return $this;
   }

   public function where($where) {
      $this->where = $where;
      return $this;
   }

   // TODO: add join


   public function build($connection){
      $commandText = 'SELECT ' . implode(', ', $this->fields) . ' FROM ' . $this->table;
      if (!is_null($this->where)) {
         $commandText .= ' WHERE ' . $this->where;
      }

      return new SqlCommand($connection, $commandText);
   }
}