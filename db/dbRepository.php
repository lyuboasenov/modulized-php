<?php

require_once(__DIR__ . '\..\domain\iRepository.php');
require_once('dbSet.php');

class DbRepository implements IRepository {
   private $set;
   private $db;

   public function __construct($db) {
      $this-> db = $db;
      $this->sets = array();
   }

   public function getUsers() {
      return $this->getSet('User');
   }

   public function getSet($type) {
      if (!array_key_exists($type, $this->sets)) {
         $this->sets[$type] = new DbSet($this->db, $this, $type);
      }

      return $this->sets[$type];
   }

   public function save() {
      foreach($this->sets as $type => $set) {
         $set->save();
      }
   }
}