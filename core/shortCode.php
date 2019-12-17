<?php
class ShortCode {
   private $id;
   private $params;

   public static $SHORT_CODE_PATTERN = '/(\$<[^>]+>)/i';

   private $idParamsPattern = '/\$<(\S+)(\s[^>]+)*>/i';
   private $paramsPattern = '/\s([^=]+)=\[([^\]]+)\]/i';

   function __construct($shortCode) {
      if (preg_match($this->idParamsPattern, $shortCode, $idParams)) {
         $this->id = $idParams[1];
         $this->params = array();
         if (count($idParams) == 3 &&
            preg_match_all($this->paramsPattern, $idParams[2], $params)) {
            for ($i = 0; $i < count($params[1]); $i++) {
               $this->params[$params[1][$i]] = $params[2][$i];
            }
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