<?php
require_once ('module.php');
require_once ('request.php');

interface iCore {
   public function writeResponse($str);
   public function get_username();
   public function get_userid();
}


class Core implements iCore {
   private $request;
   private $modules;

   function load() {
      // Initialize core
      $this->request = new Request($_SERVER['REQUEST_URI']);

      // Load modules
      $this->loadModules();
   }

   function handleRequest() {
      // Poll for handlers
      $requestHandlers = array();
      foreach($this->modules as $module) {
         if ($module->canHandle()) {
            $requestHandlers[] = $module;
         }
      }

      $staticResources = array();
      // Get static resources
      foreach($requestHandlers as $module) {
         $staticResources[] = array($module->getId(), $module->getStaticResources());
      }

      // Core handle
      $this->writeResponse('<html><title>Modulized php project</title>');

      // Include static resources
      foreach($staticResources as $moduleResource) {
         foreach($moduleResource[1] as $resource) {
            $this->writeResponse('<link rel="stylesheet" type="text/css" href="static/' . $moduleResource[0] . '/' . $resource . '">');
         }
      }

      $this->writeResponse('<body>');
      $this->writeResponse('<p><b>Uri: </b>');
      $this->writeResponse($this->request->uri);
      $this->writeResponse('</p>');

      $this->writeResponse('<p><b>Available modules: </b></br><ul>');
      foreach($this->modules as $module) {
         $this->writeResponse('<li>' . $module->getName() . '</li>');
      }
      $this->writeResponse('</ul></p>');

      // module handlers request
      $this->writeResponse('<p><b>Request handlers: </b></br><ul>');
      foreach($requestHandlers as $module) {
         $this->writeResponse('<li>' . $module->getName() . '</li>');
      }
      $this->writeResponse('</ul></p>');
      foreach($requestHandlers as $module) {
         $module->handleRequest();
      }

      // End core handle
      $this->writeResponse('<p><b>Bye!</b></p>');
      $this->writeResponse('</body></html>');
   }

   public function writeResponse($str) {
      echo $str, PHP_EOL;
   }

   public function get_username() {
      return 'ogi';
   }

   public function get_userid() {
      return 1;
   }

   private function loadModules() {
      $modulesParentDir = 'modules';
      $modulesDirs = scandir($modulesParentDir);
      foreach ($modulesDirs as $key => $value) {
         if (!in_array($value,array(".",".."))) {
            if (is_dir($modulesParentDir . DIRECTORY_SEPARATOR . $value)) {
               require_once($modulesParentDir . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . 'module.php');
            }
         }
      }

      $this->modules = array();

      foreach (get_declared_classes() as $className) {
         if (in_array('iModule', class_implements($className))) {
            $this->modules[] = new $className($this, $this->request);
         }
      }
   }
}

?>