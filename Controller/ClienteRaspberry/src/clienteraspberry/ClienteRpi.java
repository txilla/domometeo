/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package clienteraspberry;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.Socket;
import java.net.UnknownHostException;

/**
 *
 * @author tXillA
 */
public class ClienteRpi {

    
    
    
    Socket cliente = null;
    DataInputStream entrada = null;
    DataOutputStream salida = null;

    boolean dentro=true;
    
    String ipServidor ="";
    SQLite con;
    DatosServer datos;
    
    // Constructor
    //
    public ClienteRpi(SQLite sqlite) {
        this.con = sqlite;
    }
    


    //nos conectamos al localhost a traves de esta dirección IP

    //if (cliente != null && salida != null && entrada!= null) {

    public boolean conectar(String ip)
    {
        this.ipServidor = ip;
        boolean conexioSqlCorrecte=false;
        try {	
                System.out.println("Conectando con el servidor... Espere...");
                cliente = new Socket(ipServidor, 5003);
                System.out.println("Conectado");
                conexioSqlCorrecte = true;
                //asignamos este numero de puerto
                entrada = new DataInputStream(cliente.getInputStream());
                // será lo que enviaremos al servidor	
                salida = new DataOutputStream(cliente.getOutputStream());
                // será lo que nos devuelva el servidor	

        }
        catch (UnknownHostException excepcion) {
                System.err.println("El servidor no está levantado");
        }
        catch (Exception e) {
                System.err.println("Error: " + e );
        }

        return conexioSqlCorrecte;
    }

    //SQLite con = new SQLite();
    //con.connect();

    public void tratarDatos()
    {
        while (dentro)
        {
            try 
            {
                String linea_recibida;
                linea_recibida = entrada.readLine();
                System.out.println("SERVIDOR DICE: " + linea_recibida);

                String[] array = linea_recibida.split(";");

                if ( array[2].equals("1") && !array[5].equals("") )
                {
                    datos = new DatosServer();
                    
                    datos.setDate("DateTime('now')");
                    datos.setNode(Integer.parseInt(array[0]));
                    datos.setChildNode(Integer.parseInt(array[1]));
                    datos.setSensor(Integer.parseInt(array[4]));
                    datos.setPayload(Double.parseDouble(array[5]));
                    
                    //String sql = "INSERT INTO payload " +
                   //"VALUES (DateTime('now'),"+ Integer.parseInt(array[0])+ ", "+ Integer.parseInt(array[1])+ ", "+ Double.parseDouble(array[5])+ " );";

                    con.insert(datos);


                }

            } 
            catch (UnknownHostException excepcion) {
                    System.err.println("No encuentro el servidor en la dirección" + ipServidor);
            }
            catch (Exception e) {
                    System.err.println("Error: " + e );

            }
        }

        try {
            salida.close();
            entrada.close();
            cliente.close();
        } catch (IOException ex) {
            System.err.println("Error de entrada/salida");
        }

    }
    
    
}
