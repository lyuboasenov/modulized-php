<?php

interface iView {
   public function append($content);
   public function getContent();
}

class View implements iView {
   private $content;

   function __construct($content) {
      $this->content = $content;
   }

   public function getContent() {
      return $this->content;
   }

   public function append($content) {
      $this->content .= $content;
   }
}