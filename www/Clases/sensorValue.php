<?php

    include ("../Recursos/variables.php");

    class Cl_SensorValue
    {
        public $date;
        public $nodeId;
        public $childNode;
        public $sensorType;
        public $value;

        public function __construct($date, $id, $child, $type, $value)
        {

/*
            $fecha = new DateTime($date);
            $fecha->setTimezone(new DateTimeZone('Europe/Madrid'));
*/
/*
            echo "fecha madrid: ".$fecha->format('Y-m-d H:i:s') . "\n";

            $fecha->setTimezone(new DateTimeZone('Pacific/Chatham'));
            echo "fecha chatman".$fecha->format('Y-m-d H:i:s') . "\n";


            $fecha= date_create('2000-01-01', timezone_open('Pacific/Nauru'));
echo date_format($fecha, 'Y-m-d H:i:sP') . "\n";

date_timezone_set($fecha, timezone_open('Pacific/Chatham'));
echo date_format($fecha, 'Y-m-d H:i:sP') . "\n";

*/

            //echo $date ."y.....";

            $fecha = new DateTime($date, new DateTimeZone('UTC'));
            //$fecha->setTimezone(new DateTimeZone($timezone));
            $fecha->setTimezone(new DateTimeZone("Europe/Madrid"));
            //echo $fecha->format('Y-m-d H:i:s')."\n";


            $this->date = $fecha->format('d-m-Y H:i:s');
            $this->nodeId = $id;
            $this->childNode = $child;
            $this->sensorType = $type;
            $this->value = $value;
        }
        
    }

?>