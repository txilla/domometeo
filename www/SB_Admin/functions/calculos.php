<?php

    // Función que retorna el porcentaje de un valor respecto al total
    function calcularPorcentaje($valor, $total)
    {
        $porcentaje = 0.00;

        $porcentaje = ($valor*100/$total);

        return ($porcentaje);
    }

    function calcularTendencia($arraySensorvalues)
    {
        $valorAnterior = 222;                                                               // Al valor anterior le damos un valor descomunal para que siempre sea diferente al hacer la primera comparación
        $tendencia = -1;                                                                     // El valor de la tendencia es 0 si no es nada, 1 si crece, 2 si decrece

        // Nos aseguramos de que se hayan obtenido los datos, si no no la calculamos
        if(count($arraySensorvalues) > 0)
        {
            // Recorremos toda la Array para iniciar las comparaciones
            foreach ($arraySensorvalues as $index => $tempSensor) {

                // Si el valor anterior es diferente de 222 calculamos la tendencia
                if($valorAnterior != 222)
                {
                    // Si el valor anterior es mas pequeño que el valor del sensor
                    if($valorAnterior < $tempSensor->value)
                    {
                        if($tendencia == 0)
                        {
                            $tendencia = 1;
                        }
                        else if($tendencia == 1 || $tendencia == 4)
                        {
                            $tendencia = 1;
                        }
                        else if($tendencia != 1)
                        {
                            $tendencia = 3;
                        }
                    }
                    else if($valorAnterior > $tempSensor->value)
                    {
                        if ($tendencia == 0)
                        {
                            $tendencia = 2;
                        }
                        else if ($tendencia == 2 || $tendencia == 4)
                        {
                            $tendencia = 2;
                        }
                        else if ($tendencia != 2)
                        {
                            $tendencia = 3;
                        }
                    }
                    else if($valorAnterior == $tempSensor->value)
                    {
                        if ($tendencia == 0)
                        {
                            $tendencia = 4;
                        }
                        else if ($tendencia == 1)
                        {
                            $tendencia = 1;
                        }
                        else if ($tendencia == 2)
                        {
                            $tendencia = 2;
                        }
                        else
                        {
                            $tendencia = 3;
                        }
                    }
                    else {
                        $tendencia = 3;
                    }
                }
                else {
                    // El valor anterior valdra el valor actual de temperatura
                    $valorAnterior = $tempSensor->value;
                }
            }
        }

        return $tendencia;
    }

?>
