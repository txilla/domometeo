<?php

    class Cl_User
    {
        public $username;
        public $password;
        public $role;
        public $enabled;

        public function __construct($xuserName = "", $xpass = "", $xrole = "", $xenabled = 0)
        {
            $this->username = $xuserName;
            $this->password = $xpass;
            $this->role = $xrole;
            $this->enabled = $xenabled;
        }

        // Function that is used to know if a user is enabled or not:
        // ----> 0 not enabled
        // ----> 1 enabled
        public function userEnabled()
        {
            $granted = false;

            if($this->enabled == 1)
                $granted = true;

            return($granted);
        }

        public function getUsername()
            { return $this->username; }

        public function getPassword()
            { return $this->password; }

        public function getRole()
            { return $this->role; }

        public function getenabled()
            { return $this->enabled; }

    }

?>
