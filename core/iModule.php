<?php
interface iModule {
   public function getId();
   public function getName();
   public function canHandle();
   public function getStaticResources();
   public function handleRequest();
}