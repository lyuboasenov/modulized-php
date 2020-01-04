<?php

abstract class Model {
   protected $metadata;
   protected $values;

   private $isInitialized = false;
   private $isDirty = false;

   public function __set($name, $value) {
      $this->setField($name, $value, true, true);
   }

   public function __get($name) {
      $this->initialize();

      if (array_key_exists($name, $this->metadata)) {
         return $this->values[$name];
      } else {
         throw new ErrorException('Unknown field "'. $name . '".');
      }
   }

   public function getIsDirty() {
      return $this->isDirty;
   }

   public function getMetadata() {
      $this->initialize();
      return $this->metadata;
   }

   protected function setField($name, $value, $riseIsDirty, $riseIdSetError) {
      $this->initialize();

      if (array_key_exists($name, $this->metadata)) {
         if ($this->metadata[$name]->isValid($value)) {

            if ($riseIdSetError && $name == 'id') {
               throw new ErrorException ('Id can not be set explicitly.');
            }

            $this->values[$name] = $value;
         } else {
            throw new ErrorException('Value "' . $value . '" not valid for field "' . $name . '".');
         }

         if ($riseIsDirty) {
            $this->isDirty = true;
         }
      } else {
         throw new ErrorException('Unknown field "'. $name . '".');
      }
   }

   protected abstract function getFields();

   protected function initialize() {
      if (!$this->isInitialized) {
         foreach($this->getFields() as $field) {
            $this->metadata[$field->getName()] = $field;
            $this->values[$field->getName()] = !is_null($field->getDefault()) ? $field->getDefault() : null;
         }
         $this->isInitialized = true;
      }
   }
}