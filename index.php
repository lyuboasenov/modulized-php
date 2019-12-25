<?php
   require_once ('./host/host.php');
   $core = new Host();
   $core->handleRequest('./layout.php');
