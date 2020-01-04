<?php

class Command {
   private $command;
   private $connection;

   public function __construct($connection, $command) {
      $this->connection = $connection;
      $this->command = $command;
   }

   public function executeNonQuery() {

   }

   public function executeScalar() {

   }

   public function executeQuery() {
      return array(array('id' => 12, 'username' => 'ogi', 'age' => 1, 'role' => 1000));
   }
}
