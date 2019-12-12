<?php
class Module1 implements iModule {
   private $core;
   private $request;

   function __construct($core, $request) {
      $this->core = $core;
      $this->request = $request;
   }

   public function getId() {
      return 'module1';
   }

   public function getName() {
      return 'Module 1';
   }

   public function canHandle() {
      return $this->request->uri == '/?module1' || $this->request->uri == '/?module';
   }

   public function getStaticResources() {
      return array('styles.css');
   }

   public function handleRequest() {
      $this->core->writeResponse('<div class="module1_background">Module 1 handled</div>');
   }
}
?>