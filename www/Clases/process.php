<?php

  class Cl_Process
  {
      private $pid;
      private $cpu;
      private $pMem;
      private $user;
      private $command;

      /** CONSTRUCTOR **/
      public function __construct($pid = 0, $cpu = 0.00, $pMem = 0.00, $user = "", $command = "")
      {
          $this->pid = $pid;
          $this->cpu = $cpu;
          $this->pMem = $pMem;
          $this->user = $user;
          $this->command = $command;
      }

      /** GETTERS **/
      public function getPid() { return $this->pid; }
      public function getCpu() { return $this->cpu; }
      public function getpMem() { return $this->pMem; }
      public function getUser() { return $this->user; }
      public function getCommand() { return $this->command; }

      /** FUNCTIONS AND METHODS **/

      public function strArray_to_ProcessArray($strArray)
      {
          $list = array();
          $item = null;

          foreach ($strArray as $key => $value) {
              $item = $this->to_Cl_Process($value);
              $list[] = $item;
          }

          return $list;
      }

      private function to_Cl_Process($cadena)
      {
          $process = null;

          // We split de string doing a regular expression to eliminate the spaces and tabs
          $split = preg_split('@\s+@', trim($cadena), 7 );
          $process = new Cl_Process($split[0], $split[1], $split[2], $split[3], $split[4]);

          return $process;
      }

  }

?>
