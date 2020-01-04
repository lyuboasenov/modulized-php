<?php

require_once(__DIR__ . '\..\domain\set.php');
require_once('dbUser.php');

class UserDbSet extends DbSet {
   private $db;

   public function __construct($db) {
      parent::__construct($db, 'User');
   }

   protected function saveObjectInternal(Model $obj) {
      if (is_null($obj->id)){
         // insert
      } else {
         // update
      }
   }

   protected function findByIdInternal($id) {
      $prototype = new DbUser(null);
      $command = $prototype->getSelectCommandBuilder()
         ->where('id=' . $id)
         ->build($db);

      var_dump($command);

      return array(new DbUser(array('id' => $id, 'username' => 'ogi', 'age' => 1, 'role' => 1000)));
   }

   protected function findInternal($criteria) {
      return array();
   }
}