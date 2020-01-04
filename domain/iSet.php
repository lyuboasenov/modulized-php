<?php

require_once('model.php');

interface ISet {
   public function add(Model $model);
   public function remove(Model $model);
   public function findById($id);
   public function find($criteria);
   public function save();
}