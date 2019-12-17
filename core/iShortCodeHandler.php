<?php
interface iShortCodeHandler {
   public function canHandleShortCode($shortCode);
   public function handleShortCode($shortCode);
}