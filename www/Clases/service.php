<?php

    include ("../SB_Admin/functions/system.php");

    class Cl_Service
    {
        public $name;
        public $fullname;
        public $status;
        public $date;
        public $uptime;

        public function __construct($name = "", $fullname = "", $status = "", $fecha = "", $uptime = "")
        {
            $this->name = $name;
            $this->fullname = $fullname;
            $this->status = $status;
            $this->uptime = $uptime;
        }

        public function checkServiceStatus()
        {
            $command = 'systemctl status ' . $this->name . ' | grep Active';
            
            // Ejecutamos el comando
            $output = execCommand($command);
            
            // De las cadenas obtenidas, obtenemos la posición 4 que tiene siempre los datos referentes al estado del servicio y 
            // hacemos un split quitando los espacios y los tabuladores para obtener solo los datos
            $datos = preg_split('@\s+@', trim($output[0]), 7 );

            // Asignamos las nuevas propiedades al objeto para que se queden almacenadas
            //print_r($datos);
            //echo $datos[1] . " " . $datos[2];
            $this->status = $datos[1] . " " . $datos[2];        // Asignamos el nuevo estado

            // Creamos la fecha y le damos formato
            //$divideFecha = preg_split('@\s+@', trim($datos[6]), 7 );

            //$fechaTemp = new DateTime($datos[5] + $divideFecha[6], new DateTimeZone('UTC'));
            //$fechaTemp = setTimezone(new DateTimeZone("Europe/Madrid"));

            //$this->date = $fechaTemp->format('d-m-Y H:i:s');    // Asignamos la nueva fecha

            //print_r($divideFecha);

        }

        public function setStatus($status) { $this->status = $status; }

        public function getName() { return $this->name; }
        public function getFullName() { return $this->fullname; }
        public function getStatus() { return $this->status; }

    }

?>