<?php

require_once(__DIR__ . '\..\domain\iRepository.php');
require_once('dbSet.php');
require_once('userDbSet.php');

class Repository implements IRepository {
   private $userSet;
   private $db;

   public function __construct($db) {
      $this-> db = $db;
   }

   public function getUsers() {
      if (is_null($this->userSet)) {
         $this->userSet = new UserDbSet($this->db);
      }

      return $this->userSet;
   }

   public function save() {
      if (!is_null($this->userSet)) {
         $this->userSet->save();
      }
   }
}