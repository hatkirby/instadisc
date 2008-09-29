/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc;

import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.Item.Verification;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.apache.xmlrpc.XmlRpcException;
import org.apache.xmlrpc.client.XmlRpcClient;
import org.apache.xmlrpc.client.XmlRpcClientConfigImpl;

/**
 *
 * @author hatkirby
 */
public class XmlRpc {

    private String function;
    private Object[] params;
    private int step = 3;

    public XmlRpc(String function) {
        this.function = function;

        Verification ver = new Verification();
        params = new Object[3];
        params[0] = ver.getUsername();
        params[1] = ver.getHash();
        params[2] = ver.getID();
    }
    
    public XmlRpc(String function, String username, String password)
    {
        this.function = function;

        Verification ver = new Verification(username, password);
        params = new Object[3];
        params[0] = ver.getUsername();
        params[1] = ver.getHash();
        params[2] = ver.getID();
    }
    
    public void addParam(Object param)
    {
        Object oldParams[] = params;
        Object temp[] = new Object[step+1];
        int i=0;
        for (i=0;i<step;i++)
        {
            temp[i] = oldParams[i];
        }
        temp[step] = param;
        step++;
        params = temp;
    }

    public Object execute() {
        Object result = null;
        try {
            XmlRpcClientConfigImpl config = new XmlRpcClientConfigImpl();
            config.setServerURL(new URL("http://rpc.instadisc.org/"));
            XmlRpcClient client = new XmlRpcClient();
            client.setConfig(config);

            result = client.execute("InstaDisc." + function, params);
        } catch (XmlRpcException ex) {
            Logger.getLogger(InstaDiscApp.class.getName()).log(Level.SEVERE, null, ex);
        } catch (MalformedURLException ex) {
            Logger.getLogger(InstaDiscApp.class.getName()).log(Level.SEVERE, null, ex);
        }
        return result;
    }
    
    public void resetParams()
    {
        params = new Object[] {};
        step = 0;
    }
}
