<?php
class Request {
   public $uri;

   function __construct($uri) {
      $this->uri = $uri;
   }

   function get_uri() {
      return $this->uri;
   }
}