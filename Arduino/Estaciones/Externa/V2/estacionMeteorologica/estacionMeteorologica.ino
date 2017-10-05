/*
 * ESTE ES UN CODIGO DE PUTA MADRE ECHO POR LA ARANTXA Y EL RUBEN
 */
 
// ---------------------- NRF24 RADIO ----------------------------------------------------------

#define MY_RADIO_NRF24
#define MY_NODE_ID 2
#include <MySensors.h>

// ---------------------------------------------------------------------------------------------

// ---------------------- TEMPERATURE SENSOR VARIABLES AND DEFINES ------------------------------
#include <SPI.h> 
#include <DHT.h>

/* PROGRAM CONSTANTS */
static const uint32_t UN_SEGUNDO = 1000;
static const uint32_t UN_MINUTO  = 60*UN_SEGUNDO;
static const uint32_t UNA_HORA   = 60*UN_MINUTO;
static const uint32_t UN_DIA     = 24*UNA_HORA;

/* PROGRAM PARAMETERS */

// Frecuencia cada cuanto se van a leer los sensores, en milisegundos
//
//static const uint32_t SENSORS_READ_FREQUENCY_MS = 30*UN_MINUTO; 
static const uint32_t SENSORS_READ_FREQUENCY_MS = 30 * UN_SEGUNDO; 
// static const uint32_t SENSORS_READ_FREQUENCY_MS = UNA_HORA; 


// Si la lectura de temperatura o humedad no varia, se ignoran FORCE_UPDATE_N_READS muestras. Entonces se fuerza un envio.
// Si FORCE_UPDATE_N_READS vale 0, no se ignora ninguna muestra
//
static const uint8_t FORCE_UPDATE_N_READS = 0;   // i.e. the sensor would force sending an update every UPDATE_INTERVAL*FORCE_UPDATE_N_READS [ms]


// Si la lectura de la presion no varia, se ignoran FORCE_UPDATE_PRESSURE_READS muestras. Entonces se fuerza un envio.
// Si FORCE_UPDATE_N_READS vale 0, se envian todas las muestras.
// 
static const uint8_t FORCE_UPDATE_PRESSURE_READS = 0;

// Si la temperatura esta desplazada de la realidad, se aplica este offset
//
#define SENSOR_TEMP_OFFSET 0


/* END OF PROGRAM PARAMETERS */

/* Configuracion de los pines */

// Pin del arduino donde se leen la temperatura y la humedad
//
#define DHT_DATA_PIN 3

// Sensor de lluvia
//
#define RAIN_SENSOR_ANALOG_PIN A0

// Sensor de la puerta
//
#define BUTTON_PIN  4  // Arduino Digital I/O pin for button/reed switch


/* Configuracion de los IDs de MySensors */


// Identificador del sensor de humedad, en MySensors
//
#define CHILD_ID_HUM 0

// Identificador del sensor de temperatura, en MySensors
//
#define CHILD_ID_TEMP 1

// Identificador del sensor de lluvia, en MySensors
//
#define CHILD_ID_RAIN 2

// Identificador del sensor de presion, en MySensors
//
#define CHILD_ID_BARO 3

// Identificador del sensor de puerta abierta/cerrada, en MySensors
//
#define CHILD_ID_DOOR 4




// Sleep time between sensor updates (in milliseconds)
// Must be >1000ms for DHT22 and >2000ms for DHT11
static const uint64_t UPDATE_INTERVAL = 60000;


float ultimaTemperatura;
float ultimaHumedad;
uint8_t nNoUpdatesTemp;
uint8_t nNoUpdatesHum = FORCE_UPDATE_N_READS;
bool metric = true;

// Variable for DHT sensor
DHT dht;

MyMessage msgHum(CHILD_ID_HUM, V_HUM);
MyMessage msgTemp(CHILD_ID_TEMP, V_TEMP);

// ---------------------------------------------------------------------------------------

// ---------------------- RAIN SENSOR VARIABLES AND DEFINES ------------------------------

// lowest and highest rain sensor readings:
const int sensorMin = 0;     // sensor minimum
const int sensorMax = 1024;  // sensor maximum

int ultimaLecturaLluvia;

MyMessage msgRain(CHILD_ID_RAIN, V_RAIN);

// --------------------------------------------------------------------------------------

// --------------------- PRESSURE SENSOR VARIABLES AND DEFINES --------------------------

#include <SFE_BMP180.h>
#include <Wire.h>

SFE_BMP180 bmp;


double ultimaLecturaPresion;
uint8_t nNoUpdatesPressure;


MyMessage msgBaro(CHILD_ID_BARO, V_PRESSURE);

// --------------------------------------------------------------------------------------

// ------------------------ DOOR SENSOR VARIABLES AND DEFINES ---------------------------
#include <Bounce2.h>

Bounce debouncer = Bounce(); 
int oldValue=-1;


// Change to V_LIGHT if you use S_LIGHT in presentation below
MyMessage msg(CHILD_ID_DOOR,V_TRIPPED);

// --------------------------------------------------------------------------------------


void presentation()  
{ 
  // Send the sketch version information to the gateway
  sendSketchInfo("TemperatureAndHumidity", "1.1");
  sendSketchInfo("RainSensor", "1.1");
  sendSketchInfo("PressureSensor", "1.1");
  sendSketchInfo("Door Sensor", "1.0");
  

  // Register all sensors to gw (they will be created as child devices)
  present(CHILD_ID_HUM, S_HUM);
  present(CHILD_ID_TEMP, S_TEMP);
  present(CHILD_ID_RAIN, S_RAIN);
  present(CHILD_ID_BARO, S_BARO);
  present(CHILD_ID_DOOR, S_DOOR); 

}

void setup() {

  // Open Serial Port at 9600 bits per second
  Serial.begin(9600);

  Serial.println("Arduino - Iniciando Arduino Nano...");

  // ---------------------- LOAD THE TMP AND HUM SENSOR ------------------------------
    
  dht.setup(DHT_DATA_PIN);  // set data pin of DHT sensor

  if(UPDATE_INTERVAL <= dht.getMinimumSamplingPeriod())
  {
    Serial.println("DHT - Atencion: UPDATE_INTERVAL es mas pequeño que el soportado por el sensor");
  }
  else
  {
    Serial.println("DHT - Sensor iniciado correctamente");
  }
  
  // Sleep for the time of the minimum sampling period to give the sensor time to power up
  // (otherwise, timeout errors might occure for the first reading)
  sleep(dht.getMinimumSamplingPeriod());

  // --------------------------------------------------------------------------------------

  // ------------------------------ LOAD THE BMP SENSOR -----------------------------------

  if(bmp.begin())
  {
    Serial.println("BMP - Sensor de presión iniciado correctamente");
  }
  else
  {
    Serial.println("BMP - Error al iniciar el sensor BMP180");
    //while(1); // Bucle infinito
  }
  
  // --------------------------------------------------------------------------------------

  
  // -------------------------------- LOAD DOOR SENSOR ------------------------------------
  
  // Setup the button
  pinMode(BUTTON_PIN,INPUT);
  // Activate internal pull-up
  digitalWrite(BUTTON_PIN,HIGH);

  // After setting up the button, setup debouncer
  debouncer.attach(BUTTON_PIN);
  debouncer.interval(5);
  
  // --------------------------------------------------------------------------------------
  
}

  

void loop() {

  // Force reading sensor, so it works also after sleep()
  dht.readSensor(true);

  // ---------------------- GET THE TEMPERATURE ------------------------------
  float temperature = dht.getTemperature();

  if(isnan(temperature))
  {
    Serial.println("DHT - Error: no se ha podido leer la temperatura del sensor DHT!");
  }
  else if(temperature != ultimaTemperatura || nNoUpdatesTemp >= FORCE_UPDATE_N_READS)
  {
    // Only send temperature if it changed since the last measurement or if we didn't send an update for n times
    ultimaTemperatura = temperature;

    // Reset no updates counter
    nNoUpdatesTemp = 0;
    temperature += SENSOR_TEMP_OFFSET;
    
    // Here we will send the temperature to the Gateway!
    send(msgTemp.set(temperature, 1));

    // Show the temperature in the Serial Port:
    Serial.print("DHT - Temperatura : ");
    Serial.print(temperature);
    Serial.println(" C");
  }
  else
  {
    // Increase no update counter if the temperature stayed the same
    nNoUpdatesTemp++;
  }

  // ---------------------- GET THE HUMIDITY ------------------------------
  float humidity = dht.getHumidity();

  if(isnan(humidity))
  {
    Serial.println("DHT - Error: no se ha podido leer la humedad del sensor DHT!");
  }
  else if(nNoUpdatesHum >= FORCE_UPDATE_N_READS)
  {
    // Only send humidity if it changed since the last measurement or if we didn't send an update for n times
    ultimaHumedad = humidity;

    // Reset no updates counter
    nNoUpdatesHum = 0;

    // Here we will send the humidity to the Gateway!
    send(msgHum.set(humidity, 1));

    // Show the temperature in the Serial Port:
    Serial.print("DHT - Humedad : ");
    Serial.print(humidity);
    Serial.println(" %"); 
  }
  else
  {
    nNoUpdatesHum++;
  }

    // --------------------------- GET THE VALUES FOR THE PRESSURE SENSOR (BMP) ------------------------
  char status;
  double T,pressure;

  status = bmp.startTemperature(); // Start to get the temperature
  if(status != 0)
  {
    delay(status);  // Stop to finish reading
    status = bmp.getTemperature(T); // Get the temperature into the variable
    
    if(status != 0)
    {
      status = bmp.startPressure(3); // Start to get the Pressure
      
      if(status != 0)
      {
        delay(status);  // Stop to finish reading
        status = bmp.getPressure(pressure,T);  // Get the pressure

        if(status != 0)
        {
          if(isnan(pressure))
          {
            Serial.println("BMP - Error: no se ha podido leer del sensor de presion");
          }
          else if(nNoUpdatesPressure >= FORCE_UPDATE_PRESSURE_READS)
          {
            // Only send if we didn't send an update for n times
            ultimaLecturaPresion = pressure;
            
            // Reset no updates counter
             nNoUpdatesPressure = 0;

            // Here we will send the pressure payload to the gateway
            send(msgBaro.set(pressure, 1));

            // Print in the serial monitor all the pressure information:
            Serial.print("BMP - Presion : ");
            Serial.print(pressure, 2);
            Serial.println(" mb");
             
          }
          else
          {
            nNoUpdatesPressure++;
          }
        }
      }
    }
  }
  
  // Entramos en un bucle que durara 30 minutos en los que se analizara si llueve o si detecta presencia cuando pasen 30 minutos los demas sensores volveran a enviar

  //for( int vueltas = 0 ; vueltas < 1800 ; vueltas++ )
  for( int vueltas = 0 ; vueltas < SENSORS_READ_FREQUENCY_MS/1000 ; vueltas++ )
  {
    
    // --------------------------- GET THE VALUES FOR THE RAIN SENSOR ---------------------------
    int rainSensorReading = analogRead(RAIN_SENSOR_ANALOG_PIN); // We get the analogic value
  
    int range = map(rainSensorReading, sensorMin, sensorMax, 0, 3);   // We map the sensor range for obtain only 3 values : 0 - 1 - 2
  
    if(isnan(rainSensorReading))
    {
      Serial.println("Rain Sensor - Error: no se ha podido leer del sensor de lluvia");
    }
    else if(range != ultimaLecturaLluvia)
    {
      // Se manda siempre el dato de lluvia, haya cambiado o no.
      ultimaLecturaLluvia = range;
  
      // We check if it's raining or not
      switch(range)
      {
        case 0:
          Serial.println("Rain Sensor - Esta lloviendo");
          // Here we will send the state rain to the Gateway!
          send(msgRain.set(range, 1));
          break;
        case 1:
          Serial.println("Rain Sensor - Aviso de lluvia");
          // Here we will send the state rain to the Gateway!
          send(msgRain.set(range, 1));
          break;
        case 2:
          Serial.println("Rain Sensor - No llueve");
          send(msgRain.set(range, 1));
          break;
      }
    
      // We show the value of the rain sensor in the Serial Port
      Serial.print("Sensor Rain - Valor mapeado del sensor de lluvia : ");
      Serial.println(range);
          
    }
  
  
  
    // ----------------------- DETECT VALUE OF DOOR SENSOR ------------------------------------
    
    debouncer.update();
    // Get the update value
    int value = debouncer.read();
  
    // Only send the value door sensor if it changes respect the last valuee
    if (value != oldValue) 
    {
       // Send in the new value
       if(value == 1)
        Serial.println("DOOR SENSOR - Puerta abierta");
       else
        Serial.println("DOOR SENSOR - Puerta cerrada");
       send(msg.set(value==HIGH ? 1 : 0));
       oldValue = value;
    }

    // Hacemos una pausa de 1 segundo
    delay(1000);
    
  }
    
  //delay(SENSORS_READ_FREQUENCY_MS);
}



