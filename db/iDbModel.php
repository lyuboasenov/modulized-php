<?php

interface IDbModel {
   public function getSelectCommandBuilder();
   public function getInsertCommandBuilder();
   public function getUpdateCommandBuilder();
   public function getDeleteCommandBuilder();
}