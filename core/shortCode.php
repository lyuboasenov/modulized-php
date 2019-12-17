<?php
class ShortCode {
   private $id;
   private $params;

   public static $SHORT_CODE_PATTERN = '/(\$<[^>]+>)/i';

   private $partsPattern = '/\$<(\S+)(\s[^>]+)*>/i';
   //private $partsPattern = '/\$<(\S+)(\s([^=]+)=\[([^]]+)\])*>/i';

   function __construct($shortCode) {
      if (preg_match($this->partsPattern, $shortCode, $output_array)) {
         $this->id = $output_array[1];
         $this->params = array();
         if (count($output_array) == 3) {
            $parameters = $output_array[2];
         }
      } else {
         throw new Exception('Invalid short code.');
      }
   }

   public function getId() {
      return $this->id;
   }

   public function getParams() {
      return $this->params;
   }
}