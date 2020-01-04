<?php

require_once(__DIR__ . '\..\domain\set.php');

abstract class DbSet extends Set {
   private $db;

   public function __construct($db, $type) {
      parent::__construct($type);

      $this->db = $db;
   }
}