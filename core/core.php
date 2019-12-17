<?php
require_once ('iModule.php');
require_once ('iShortCodeHandler.php');
require_once ('request.php');
require_once ('shortCode.php');

interface iCore {
   public function write($str);
   public function get_username();
   public function get_userid();
   public function getService($type);
}


class Core implements iCore, iShortCodeHandler {
   private $request;
   private $modules;
   private $shortCodeHandlers;
   private $staticResources;
   private $layout;
   private $outputStream;

   public function handleRequest($layout) {
      $this->load();
      $this->layout = $layout;

      // Poll for handlers
      $this->requestHandlers = array();
      foreach($this->modules as $module) {
         if ($module->canHandle()) {
            $this->requestHandlers[] = $module;
         }
      }

      $this->staticResources = array();
      // Get static resources
      foreach($this->requestHandlers as $module) {
         $this->staticResources[] = array($module->getId(), $module->getStaticResources());
      }

      $this->outputStream = fopen("php://output", 'w');
      try {
         // Core handle
         $this->write($this->getIncludeContents($layout));
      } finally {
         fclose($this->outputStream);
      }
   }

   public function write($content) {
      $parts = preg_split(ShortCode::$SHORT_CODE_PATTERN, $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

      foreach($parts as $part){
         if (preg_match(ShortCode::$SHORT_CODE_PATTERN, $part)) {
            $shortCode = new ShortCode($part);

            foreach($this->shortCodeHandlers as $handler) {
               if ($handler->canHandleShortCode($shortCode)) {
                  $handler->handleShortCode($shortCode);
               }
            }
         } else {
            $this->writeToOutput($part);
         }
      }
   }

   public function get_username() {
      return 'ogi';
   }

   public function get_userid() {
      return 1;
   }

   public function getService($type) {
      return new $type;
   }

   public function canHandleShortCode($shortCode) {
      return $shortCode->getId() == 'core:staticResources' ||
         $shortCode->getId() == 'core:content';
   }

   public function handleShortCode($shortCode) {
      if ($this->canHandleShortCode($shortCode)) {
         if ($shortCode->getId() == 'core:staticResources') {
            $this->handleStaticResources();
         } else if ($shortCode->getId() == 'core:content') {
            $this->handleContent();
         }
      } else {
         throw new Exception('Short code not supported.');
      }
   }

   private function load() {
      // Initialize core
      $this->request = new Request($_SERVER['REQUEST_URI']);

      // Load modules
      $this->loadModules();
   }

   private function getIncludeContents($filename) {
      if (is_file($filename)) {
          ob_start();
          include $filename;
          return ob_get_clean();
      }
      return false;
   }

   private function writeToOutput($str) {
      fwrite($this->outputStream, $str);
   }

   private function handleContent() {
      $this->write('<p><b>Uri: </b>');
      $this->write($this->request->uri);
      $this->write('</p>');

      $this->write('<p><b>Available modules: </b></br><ul>');
      foreach($this->modules as $module) {
         $this->write('<li>' . $module->getName() . '</li>');
      }
      $this->write('</ul></p>');

      // module handlers request
      $this->write('<p><b>Request handlers: </b></br><ul>');
      foreach($this->requestHandlers as $module) {
         $this->write('<li>' . $module->getName() . '</li>');
      }
      $this->write('</ul></p>');
      foreach($this->requestHandlers as $module) {
         $module->handleRequest();
      }
   }

   private function handleStaticResources() {
      // Include static resources
      foreach($this->staticResources as $moduleResource) {
         foreach($moduleResource[1] as $resource) {
            $this->write('<link rel="stylesheet" type="text/css" href="static/' . $moduleResource[0] . '/' . $resource . '">');
         }
      }
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

      // these three lines should be in the constructor
      $this->modules = array();
      $this->shortCodeHandlers = array();
      $this->shortCodeHandlers[] = $this;

      foreach (get_declared_classes() as $className) {
         if (in_array('iModule', class_implements($className))) {
            $module = new $className($this, $this->request);
            $this->modules[] = $module;

            if ($module instanceof iShortCodeHandler) {
               $this->shortCodeHandlers[] = $module;
            }
         }
      }
   }
}
