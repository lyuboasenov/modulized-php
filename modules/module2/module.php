<?php
class Module2 implements iModule {
   private $core;
   private $request;

   function __construct($core, $request) {
      $this->core = $core;
      $this->request = $request;
   }

   public function getId() {
      return 'module2';
   }

   public function getName() {
      return 'Module 2';
   }

   public function canHandle() {
      return $this->request->uri == '/?module2' || $this->request->uri == '/?module';
   }

   public function getStaticResources() {
      return array('styles.css');
   }

   public function handleRequest() {
      $this->core->writeResponse('<div class="module2_background">Module 2 handled</div>');
   }
}
?>