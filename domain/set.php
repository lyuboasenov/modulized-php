<?php

require_once('model.php');

abstract class Set {
   protected $domainModelType;
   private $trackedObjects;

   public function __construct($domainModelType) {
      $this->domainModelType = $domainModelType;
      $this->trackedObjects = array();
   }

   public function add(Model $obj) {
      if (is_null($obj)) {
         throw new ErrorException('The set does not track nulls.');
      }

      if ($obj instanceof $this->domainModelType) {
         $this->trackedObjects[] = $obj;
      } else {
         throw new ErrorException('Passed object "' . $obj . '" is of type "' . gettype($obj) . '". Expected type "' . $this->domainModelType . '".');
      }
   }

   public function remove(Model $obj) {
      if (!array_search($obj, $this->trackedObjects)) {
         unset($this->trackedObjects[$obj]);
      } else {
         throw new ErrorException('Object "' . $obj . '" not tracked.');
      }
   }

   public function findById($id) {
      $found = $this->findByIdInternal($id);

      foreach($found as $obj) {
         $this->add($obj);
      }

      return $found;
   }

   public function find($criteria) {
      $found = $this->findInternal($criteria);

      foreach($found as $obj) {
         $this->add($obj);
      }

      return $found;
   }

   public function save() {
      foreach($this->trackedObjects as $obj) {
         if ($obj->getIsDirty()) {
            $this->saveObjectInternal($obj);
         }
      }
   }

   protected abstract function saveObjectInternal(Model $obj);
   protected abstract function findByIdInternal($id);
   protected abstract function findInternal($criteria);
}