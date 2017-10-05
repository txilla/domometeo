/*
 * ESTE ES UN CODIGO DE PUTA MADRE ECHO POR LA ARANTXA Y EL RUBEN
 */

// Enable debug prints to serial monitor
#define MY_DEBUG

// Enable and select radio type attached
#define MY_RADIO_NRF24
#define MY_NODE_ID 1
//#define MY_RADIO_RFM69

#define MY_SENSORS_ENABLED 1

#if MY_SENSORS_ENABLED
#include <MySensors.h>
#endif

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
//static const uint32_t SENSORS_READ_FREQUENCY_MS = 30 * UN_MINUTO; 
// static const uint32_t SENSORS_READ_FREQUENCY_MS = 30 * UN_MINUTO; 
static const uint32_t SENSORS_READ_FREQUENCY_MS = 30 * UN_SEGUNDO; 
// static const uint32_t SENSORS_READ_FREQUENCY_MS = UNA_HORA; 

// Si la lectura de temperatura o humedad no varia, se ignoran FORCE_UPDATE_N_READS muestras. Entonces se fuerza un envio.
// Si FORCE_UPDATE_N_READS vale 0, no se ignora ninguna muestra
//
static const uint8_t FORCE_UPDATE_N_READS = 0;   // i.e. the sensor would force sending an update every UPDATE_INTERVAL*FORCE_UPDATE_N_READS [ms]

// Si la lectura de calidad del aire no varia, no se mandan muestras al gateway. Se fuerza un envio cada FORCE_UPDATE_N_READS_AIR muestras.
// Si FORCE_UPDATE_N_READS_AIR vale 0, no se ignora ninguna muestra
//
static const uint8_t FORCE_UPDATE_N_READS_AIR = 0;

// Si la lectura de calidad del aire no varia, no se mandan muestras al gateway. Se fuerza un envio cada FORCE_UPDATE_N_READS_AIR muestras.
// Si FORCE_UPDATE_N_READS_AIR vale 0, no se ignora ninguna muestra
//
static const uint8_t FORCE_UPDATE_N_READS_AIR_ZEROS = 0;

// Si la temperatura esta desplazada de la realidad, se aplica este offset
//
#define SENSOR_TEMP_OFFSET 0        // Set this offset if the sensor has a permanent small offset to the real temperatures


/* END OF PROGRAM PARAMETERS */


/* Configuracion de los pines */

// Pin del arduino donde esta el sensor de luz
//
#define LIGHT_SENSOR_ANALOG_PIN 1

// Pin donde se encuentra el rele
//
#define DIGITAL_RELE 7


// Pin del arduino donde esta el sensor de movimiento
//
#define DIGITAL_INPUT_SENSOR 3   // The digital input you attached your motion sensor.  (Only 2 and 3 generates interrupt!)

// Pin del arduino donde se leen la temperatura y la humedad
//
#define DHT_DATA_PIN 5              // Set this to the pin you connected the DHT's data pin to:

// Pin donde se encuentra el MQ135
//
#define AIQ_SENSOR_ANALOG_PIN A2

/* Configuracion de los IDs de MySensors */

// Identificador del sensor de luz, en MySensors
//
#define CHILD_ID_LIGHT 0

// Identificador del sensor de movimiento, en MySensors
//
#define CHILD_ID_MOTION 1   // Id of the sensor child

// Identificador del sensor de humedad, en MySensors
//
#define CHILD_ID_HUM 2

// Identificador del sensor de temperatura, en MySensors
//
#define CHILD_ID_TEMP 3

// Identificador del sensor de Air Quality (leido del MQ135), en MySensors
//
#define CHILD_ID_AIQ 4

// Identificador del sensor de tabaco en el aire (leido del MQ135), en MySensors
//
//#define CHILD_ID_SMOKE 5

// Identificador del sensor CO en el aire (leido del MQ135), en MySensors
//
//#define CHILD_ID_CO 6

// Identificador del sensor LPG en el aire (leido del MQ135), en MySensors
//
//#define CHILD_ID_LPG 7



// Sleep time between sensor updates (in milliseconds)
// Must be >1000ms for DHT22 and >2000ms for DHT11
static const uint64_t UPDATE_INTERVAL = 60000;

// Variables for DHT sensor
float ultimaTemperatura;
float ultimaHumedad;
uint8_t nNoUpdatesTemp;
uint8_t nNoUpdatesHum = FORCE_UPDATE_N_READS;
bool metric = true;
DHT dht;

#if MY_SENSORS_ENABLED
MyMessage msg(CHILD_ID_LIGHT, V_LIGHT_LEVEL);
// Initialize motion message
MyMessage msg2(CHILD_ID_MOTION, V_TRIPPED);


// TEMP AND HUM MESSAGES
MyMessage msgHum(CHILD_ID_HUM, V_HUM);
MyMessage msgTemp(CHILD_ID_TEMP, V_TEMP);
#endif

// --------------------------------------------------------------------------------------

// --------------------- GAS SENSOR VARIABLES AND DEFINES --------------------------


/************************Hardware Related Macros************************************/
#define         RL_VALUE                     (1)     //define the load resistance on the board, in kilo ohms
#define         RO_CLEAN_AIR_FACTOR          (9.83)  //RO_CLEAR_AIR_FACTOR=(Sensor resistance in clean air)/RO,
/***********************Software Related Macros************************************/
#define         CALIBARAION_SAMPLE_TIMES     (5)    //define how many samples you are going to take in the calibration phase
#define         CALIBRATION_SAMPLE_INTERVAL  (500)   //define the time interal(in milisecond) between each samples in the
                                                     //cablibration phase
#define         READ_SAMPLE_INTERVAL         (50)    //define how many samples you are going to take in normal operation
#define         READ_SAMPLE_TIMES            (1)     //define the time interal(in milisecond) between each samples in 
/*****************************************************************************/

#define MQ135_DEFAULTPPM 399 //default ppm of CO2 for calibration
//static const float MQ135_DEFAULTRO = 4.1550;  //default Ro for MQ135_DEFAULTPPM ppm of CO2
static const float MQ135_DEFAULTRO = 6.8550;  //default Ro for MQ135_DEFAULTPPM ppm of CO2
//static const float MQ135_DEFAULTRO = 9.6550;  //default Ro for MQ135_DEFAULTPPM ppm of CO2
//static const float MQ135_DEFAULTRO = 15.83;  //default Ro for MQ135_DEFAULTPPM ppm of CO2
//#define MQ135_DEFAULTRO 68550  //default Ro for MQ135_DEFAULTPPM ppm of CO2
#define MQ135_SCALINGFACTOR 116.6020682 //CO2 gas value
#define MQ135_EXPONENT -2.769034857 //CO2 gas value
#define MQ135_MAXRSRO 2.428 //for CO2
#define MQ135_MINRSRO 0.358 //for CO2

#if MY_SENSORS_ENABLED
MyMessage msgCO2(CHILD_ID_AIQ, V_LEVEL);
//MyMessage msgSMOKE(CHILD_ID_SMOKE, V_LEVEL);
//MyMessage msgCO(CHILD_ID_CO, V_LEVEL);
//MyMessage msgLPG(CHILD_ID_LPG, V_LEVEL);
#endif

// --------------------------------------------------------------------------------------



void setup()
{
    Serial.begin(9600);
    Serial.println("Iniciando Arduino Nano...");

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

    // CONFIGURAR PIN RELE
    pinMode(DIGITAL_RELE,OUTPUT);

    // Sleep for the time of the minimum sampling period to give the sensor time to power up
    // (otherwise, timeout errors might occure for the first reading)
    delay(dht.getMinimumSamplingPeriod());

    //calibrateAirQualitySensor();
}

/*
 * get the ppm concentration
 */
double mq135_getppm(float resvalue, double ro) {
  double ret = 0;
  double validinterval = 0;
  validinterval = resvalue/ro;
  if(validinterval<MQ135_MAXRSRO && validinterval>MQ135_MINRSRO) {
    ret = (double)MQ135_SCALINGFACTOR * pow( ((double)resvalue/ro), MQ135_EXPONENT);
  }
  return ret;
}

#if MY_SENSORS_ENABLED
void presentation()
{
    // Send the sketch version information to the gateway and Controller
    sendSketchInfo("Light Sensor", "1.0");

    // Register all sensors to gateway (they will be created as child devices)
    present(CHILD_ID_LIGHT, S_LIGHT_LEVEL);

    // Send the sketch version information to the gateway and Controller
    sendSketchInfo("Internal-TemperatureAndHumidity", "1.1");
    sendSketchInfo("Motion Sensor", "1.0");
    sendSketchInfo("AIQ Sensor MQ135", "1.0");
    //sendSketchInfo("AIR Smoke MQ135", "1.0");
    //sendSketchInfo("AIR CO MQ135", "1.0");
    //sendSketchInfo("AIR LPG MQ135", "1.0");

    // Register all sensors to gw (they will be created as child devices)
    present(CHILD_ID_MOTION, S_MOTION); 
    present(CHILD_ID_HUM, S_HUM);
    present(CHILD_ID_TEMP, S_TEMP);
    present(CHILD_ID_AIQ, S_AIR_QUALITY);
    //present(CHILD_ID_SMOKE, S_AIR_QUALITY);
    //present(CHILD_ID_LPG, S_AIR_QUALITY);
    //present(CHILD_ID_CO, S_AIR_QUALITY);
}
#endif 

bool trippedAnterior = false;
void loop()
{    
    // --------------------- GET THE LIGHT SENSOR ---------------------------------
    int16_t lightLevel = (1023-analogRead(LIGHT_SENSOR_ANALOG_PIN))/10.23;
    Serial.println(lightLevel);
    #if MY_SENSORS_ENABLED
    send(msg.set(lightLevel));
    #endif

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
      #if MY_SENSORS_ENABLED
      send(msgTemp.set(temperature, 1));
      #endif 
  
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
      #if MY_SENSORS_ENABLED
      send(msgHum.set(humidity, 1));
      #endif
  
      // Show the temperature in the Serial Port:
      Serial.print("DHT - Humedad : ");
      Serial.print(humidity);
      Serial.println(" %"); 
    }
    else
    {
      nNoUpdatesHum++;
    }

    // --------------------------- GET THE VALUES FOR THE AIR QUALITY SENSOR (MQ) ------------------------

    airQualitySensor();
  
  
  // Recorremos este for 30 minutos para recolectar información sobre el sensor de movimiento y saber si hay que encender o no la bombilla con el relé
  //for( int vueltas = 0 ; vueltas < 1800 ; vueltas++ )
  for( int vueltas = 0 ; vueltas < SENSORS_READ_FREQUENCY_MS/1000 ; vueltas++ )
  {

    // --------------------- GET THE LIGHT SENSOR ---------------------------------
    
    lightLevel = (1023-analogRead(LIGHT_SENSOR_ANALOG_PIN))/10.23;
    Serial.println(lightLevel);

    // Encender
    //
    if (lightLevel < 4)
    {
      digitalWrite(DIGITAL_RELE,1);
    }

    // Apagar
    //
    if (lightLevel > 7)
    {
      digitalWrite(DIGITAL_RELE,0);
    }

    // --------------------- GET THE VALUE FOR MOTION SENSOR -----------------------
    
    

    // Read digital motion value
    bool tripped = digitalRead(DIGITAL_INPUT_SENSOR) == HIGH;
  
    if (tripped != trippedAnterior)
    {
  
    Serial.println(tripped);
    //send(msg2.set(tripped?"1":"0"));  // Send tripped value to gw

    if (tripped)
    {
      #if MY_SENSORS_ENABLED
      send(msg2.set("1"));  // Send tripped value to gw
      #endif
    }
    
    }

  
    trippedAnterior = tripped;

    // Ponemos una espera de un segundo
    delay(1000);
    
  }
    
    //delay(SENSORS_READ_FREQUENCY_MS);
}

#define MQ135_SENSOR AIQ_SENSOR_ANALOG_PIN


/* AIQ constants*/
static const int GAS_CO2     = 2;
static const int GAS_NH4     = 4;
static const int GAS_CO2H50H = 5;
static const int GAS_CH3     = 6;
static const int GAS_CH3_2CO = 7;
static const int GAS_CO_sec  = 13;

/* AIQ variables */
static double airQualitySensorRo = 6.8;

void calibrateAirQualitySensor()
{
  airQualitySensorRo = MQCalibration(MQ135_SENSOR);
  Serial.print("    MQ135:"); 
  Serial.println(airQualitySensorRo);
}

void airQualitySensor()
{
  float lecturaSensor = MQRead(MQ135_SENSOR);
  
  //int ppmCo2 = MQ135_DEFAULTPPM + MQGetGasPercentage(MQRead(MQ135_SENSOR),airQualitySensorRo,GAS_CO2);
  int ppmCo2 = MQ135_DEFAULTPPM + MQGetGasPercentage(lecturaSensor,MQ135_DEFAULTRO ,GAS_CO2);
  
  //convert to ppm (using default ro)
  //int valAIQ = MQ135_DEFAULTPPM + mq135_getppm(lecturaSensor, MQ135_DEFAULTRO);
  int valAIQ = MQ135_DEFAULTPPM + mq135_getppm(lecturaSensor, MQ135_DEFAULTRO);

  Serial.print("CO2 2  :"); 
  Serial.print( ppmCo2 );
  Serial.print( "ppm" );      
  Serial.print("   val :" ); 
  Serial.print(lecturaSensor ); 
  Serial.println("    ");   

  Serial.print("CO2 viejo   :"); 
  Serial.print( valAIQ );
  Serial.println( "ppm" );      
  Serial.print("    "); 
  #if MY_SENSORS_ENABLED
  send(msgCO2.set(ppmCo2));
  #endif
}



/* MQ HELP FUNCTIONS */
 
/***************************** MQCalibration ****************************************
Input:   mq_pin - analog channel
Output:  Ro of the sensor
Remarks: This function assumes that the sensor is in clean air. It use  
         MQResistanceCalculation to calculates the sensor resistance in clean air 
         and then divides it with RO_CLEAN_AIR_FACTOR. RO_CLEAN_AIR_FACTOR is about 
         10, which differs slightly between different sensors.
************************************************************************************/ 
float MQCalibration(int mq_pin)
{
  int i;
  float val=0;
 
  for (i=0;i<CALIBARAION_SAMPLE_TIMES;i++) {            //take multiple samples
    val += MQResistanceCalculation(analogRead(mq_pin));
    delay(CALIBRATION_SAMPLE_INTERVAL);
  }
  val = val/CALIBARAION_SAMPLE_TIMES;                   //calculate the average value
 
  val = val/RO_CLEAN_AIR_FACTOR;                        //divided by RO_CLEAN_AIR_FACTOR yields the Ro 
                                                        //according to the chart in the datasheet 
 
  return val; 
}

/*****************************  MQRead *********************************************
Input:   mq_pin - analog channel
Output:  Rs of the sensor
Remarks: This function use MQResistanceCalculation to caculate the sensor resistenc (Rs).
         The Rs changes as the sensor is in the different consentration of the target
         gas. The sample times and the time interval between samples could be configured
         by changing the definition of the macros.
************************************************************************************/ 
float MQRead(int mq_pin)
{
  int i;
  float rs=0;
 
  for (i=0;i<READ_SAMPLE_TIMES;i++) {
    rs += MQResistanceCalculation(analogRead(mq_pin));
    delay(READ_SAMPLE_INTERVAL);
  }
 
  rs = rs/READ_SAMPLE_TIMES;
 
  return rs;  
}

/*****************************  MQGetGasPercentage **********************************
Input:   rs_ro_ratio - Rs divided by Ro
         gas_id      - target gas type
Output:  ppm of the target gas
Remarks: This function passes different curves to the MQGetPercentage function which 
         calculates the ppm (parts per million) of the target gas.
************************************************************************************/ 
int MQGetGasPercentage(float rs_ro_ratio, float ro, int gas_id)
{
    static const float CO2Curve[2]     =  {113.7105289, -3.019713765}; //MQ135
    static const float CO_secCurve[2]  =  {726.7809737, -4.040111669}; //MQ135
    static const float NH4Curve[2]     =  {84.07117895, -4.41107687};  //MQ135
    static const float CO2H50HCurve[2] =  {74.77989144, 3.010328075};  //MQ135
    static const float CH3Curve[2]     =  {47.01770503, -3.281901967}; //MQ135
    static const float CH3_2COCurve[2] =  {7.010800878, -2.122018939}; //MQ135

    int valueToReturn = 0;
  
    if ( gas_id == GAS_CO2 ) {
     valueToReturn = MQGetPercentage(rs_ro_ratio,ro,CO2Curve);     //MQ135
    } else if ( gas_id == GAS_NH4 ) {
     valueToReturn = MQGetPercentage(rs_ro_ratio,ro,NH4Curve);     //MQ135
    } else if ( gas_id == GAS_CO2H50H ) {
     valueToReturn = MQGetPercentage(rs_ro_ratio,ro,CO2H50HCurve); //MQ135
    } else if ( gas_id == GAS_CH3 ) {
     valueToReturn = MQGetPercentage(rs_ro_ratio,ro,CH3Curve);     //MQ135
    } else if ( gas_id == GAS_CH3_2CO ) {
     valueToReturn = MQGetPercentage(rs_ro_ratio,ro,CH3_2COCurve); //MQ135
    } else if ( gas_id == GAS_CO_sec ) {
     valueToReturn = MQGetPercentage(rs_ro_ratio,ro,CO_secCurve);  //MQ135
    }
 
  return valueToReturn;
}

/*****************************  MQGetPercentage **********************************
Input:   rs_ro_ratio - Rs divided by Ro
         pcurve      - pointer to the curve of the target gas
Output:  ppm of the target gas
Remarks: By using the slope and a point of the line. The x(logarithmic value of ppm) 
         of the line could be derived if y(rs_ro_ratio) is provided. As it is a 
         logarithmic coordinate, power of 10 is used to convert the result to non-logarithmic 
         value.
************************************************************************************/ 
int  MQGetPercentage(const float rs_ro_ratio, const float ro, const float *pcurve)
{
  return (double)(pcurve[0] * pow(((double)rs_ro_ratio/ro), pcurve[1]));
}

/****************** MQResistanceCalculation ****************************************
Input:   raw_adc - raw value read from adc, which represents the voltage
Output:  the calculated sensor resistance
Remarks: The sensor and the load resistor forms a voltage divider. Given the voltage
         across the load resistor and its resistance, the resistance of the sensor
         could be derived.
************************************************************************************/ 
float MQResistanceCalculation(int raw_adc)
{
  return ( ((float)RL_VALUE*(1023-raw_adc)/raw_adc));
}

