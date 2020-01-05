<?php

require_once('command.php');

class SelectBuilder {
   private $tables;
   private $fields;
   private $where;

   private function __construct($table) {
      $this->tables = array();
      $this->tables[$table] = array('MAIN', null);

      $this->fields = array();
   }

   public static function from($table) {
      return new SelectBuilder($table);
   }

   public function field($field) {
      $mainTable = array_keys($this->tables)[0];
      $this->fields[$mainTable][] = $field;
      return $this;
   }

   public function fields($fields) {
      $mainTable = array_keys($this->tables)[0];
      $this->fields[$mainTable] = array_merge($this->fields, $fields);
      return $this;
   }

   public function tableField($table, $field) {
      $this->fields[$table][] = $field;
      return $this;
   }

   public function tableFields($table, $fields) {
      $this->fields[$table] = array_merge($this->fields[$table], $fields);
      return $this;
   }

   public function where($where) {
      $this->where = $where;
      return $this;
   }

   public function join($table, $condition) {
      $this->tables[$table] = array('INNER JOIN', $condition);
      return $this;
   }

   public function leftJoin($table, $condition) {
      $this->tables[$table] = array('LEFT JOIN', $condition);
      return $this;
   }

   public function rightJoin($table, $condition) {
      $this->tables[$table] = array('RIGHT JOIN', $condition);
      return $this;
   }


   public function build($connection){
      $tables = array_keys($this->tables);

      $tableSpecificFields = array();
      foreach($this->fields as $table => $fields) {
         $tableIndex = array_search($table, $tables);

         foreach($fields as $field) {
            $tableSpecificFields[] = $table . '.' . $field;
         }
      }

      $fromClause = $tables[0];
      for($i = 1; $i < count($tables); $i++) {
         $currentTable = $tables[$i];
         $joinType = $this->tables[$currentTable][0];
         $condition = $this->tables[$currentTable][1];

         $fromClause .= ' ' . $joinType . ' ' . $currentTable . ' ON ' . $condition;
      }

      $commandText = 'SELECT ' . implode(', ', $tableSpecificFields) . ' FROM ' . $fromClause;
      if (!is_null($this->where)) {
         $commandText .= ' WHERE ' . $this->where;
      }

      return new Command($connection, $commandText);
   }
}