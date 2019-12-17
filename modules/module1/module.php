<?php
class Module1 implements iModule, iShortCodeHandler {
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
      $this->core->write('<div class="module1_background">Module 1 handled</div>');
   }

   public function canHandleShortCode($shortCode) {
      return $shortCode->getId() == 'module1:short';
   }

   public function handleShortCode($shortCode) {
      if ($this->canHandleShortCode($shortCode)) {
         $this->core->write('<div id="' . $shortCode->getId() . '">Params:<ul>');
         foreach($shortCode->getParams() as $key => $value) {
            $this->core->write('<li><b>' . $key . '</b> ' . $value . '</li>');
         }
         $this->core->write('</ul></div>');
      } else {
         throw new Exception('Short code not supported.');
      }
   }
}
