<?php

abstract class Field {
   private $name;
   private $default;
   private $null;

   public function __construct($name, $null, $default) {
      $this->name = $name;
      $this->null = $null;
      $this->default = $default;
   }

   public function getName() {
      return $this->name;
   }

   public function getDefault() {
      return $this->default;
   }

   public function getNull() {
      return $this->null;
   }

   public abstract function isValid($value);
}

class IntegerField extends Field {
   public function __construct($name, $null, $default) {
      parent::__construct($name, $null, $default);
   }

   public function isValid($value) {
      return is_int($value);
   }
}

class FloatField extends Field {
   public function isValid($value) {
      return is_float($value);
   }
}

class DecimalField extends Field {
   private $maxDigits;
   private $decimalPlaces;

   public function __construct($name, $null, $default, $maxDigits, $decimalPlaces) {
      parrent::__construct($name, $null, $default);
      $this->maxDigits = $decimalPlaces;
   }

   public function getMaxDigits() {
      return $this->maxDigits;
   }

   public function getDecimalPlaces() {
      return $this->decimalPlaces;
   }

   public function isValid($value) {
      return is_double($value);
   }
}

class DateTimeField extends Field {
   public function __construct($name, $null, $default) {
      parent::__construct($name, $null, $default);
   }

   public function isValid($value) {
      return $value instanceof DateTime;
   }
}

class BooleanField extends Field {
   public function __construct($name, $null, $default) {
      parent::__construct($name, $null, $default);
   }

   public function isValid($value) {
      return is_bool($value);
   }
}

class TextField extends Field {
   public function __construct($name, $null, $default) {
      parent::__construct($name, $null, $default);
   }

   public function isValid($value) {
      return is_string($value);
   }
}

class CharField extends Field {
   private $maxLength;

   public function __construct($name, $null, $default, $maxLength) {
      parent::__construct($name, $null, $default);
      $this->maxLength = $maxLength;
   }

   public function getMaxLength() {
      return $this->maxLength;
   }

   public function isValid($value) {
      return is_string($value) && strlen($value) <= $this->maxLength;
   }
}

class ForeignKeyField extends Field {
   public function isValid($value) {
      return true;
   }
}

class ManyToManyForeignKeyField extends Field {
   public function isValid($value) {
      return true;
   }
}