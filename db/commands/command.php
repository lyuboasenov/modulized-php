<?php

class SqlCommand {
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

   }
}
