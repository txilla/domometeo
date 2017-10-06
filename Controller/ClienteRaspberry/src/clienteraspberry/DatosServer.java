/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package clienteraspberry;

/**
 *
 * @author tXillA
 */
public class DatosServer {
    
    private String date;
    private Integer node;
    private Integer childNode;
    private Integer sensor;
    private Double payload;

    public Integer getChildNode() {
        return childNode;
    }

    public void setChildNode(Integer childNode) {
        this.childNode = childNode;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public Integer getNode() {
        return node;
    }

    public void setNode(Integer node) {
        this.node = node;
    }

    public Integer getSensor() {
        return sensor;
    }

    public void setSensor(Integer sensor) {
        this.sensor = sensor;
    }

    public Double getPayload() {
        return payload;
    }

    public void setPayload(Double payload) {
        this.payload = payload;
    }
    
    
}
