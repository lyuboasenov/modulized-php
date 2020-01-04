<?php

require_once(__DIR__ . '\..\domain\iRepository.php');
require_once('dbSet.php');

class DbRepository implements IRepository {
   private $userSet;
   private $db;

   public function __construct($db) {
      $this-> db = $db;
   }

   public function getUsers() {
      if (is_null($this->userSet)) {
         $this->userSet = new DbSet($this->db, 'User');
      }

      return $this->userSet;
   }

   public function save() {
      if (!is_null($this->userSet)) {
         $this->userSet->save();
      }
   }
}