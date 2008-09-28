 /*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Item;

import com.fourisland.instadisc.MD5;
import com.fourisland.instadisc.AskForPasswordForm;
import com.fourisland.instadisc.Database.Filter;
import com.fourisland.instadisc.Database.Subscription;
import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.Functions;
import com.fourisland.instadisc.XmlRpc;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.HashMap;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JFrame;
import javax.swing.JLabel;

/**
 *
 * @author hatkirby
 */
public class SubscriptionFile {

    public HashMap<String, String> headerMap;

    public SubscriptionFile(URL url, JLabel status) {
        status.setText("Checking....");
        try
        {
            HttpURLConnection urc = (HttpURLConnection) url.openConnection();
            Thread th = new Thread(new SubscriptionFileThread(urc, status));
            th.start();
        } catch (FileNotFoundException ex)
        {
            status.setText("Error: Subscription File doesn't exist");
        } catch (IOException ex)
        {
            Logger.getLogger(SubscriptionFile.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public static void deleteSubscription(Subscription s, boolean deleteFromData) {
        int i = 0;
        com.fourisland.instadisc.Database.Item it[] = Wrapper.getAllItem();
        for (i = 0; i < it.length; i++)
        {
            if (it[i].getSubscription().equals(s.getURL()))
            { 
                Wrapper.deleteItem(it[i].getID());
            }
        }

        if (deleteFromData)
        {
            Wrapper.deleteSubscription(s.getURL());
        }

        XmlRpc xmlrpc = new XmlRpc("deleteSubscription");
        xmlrpc.addParam(s.getURL());
        xmlrpc.execute();

        Filter f[] = Wrapper.getAllFilter();
        for (i = 0; i < f.length; i++)
        {
            if (f[i].getSubscription().equals(s.getURL()))
            {
                Wrapper.deleteFilter(f[i].getID());
            }
        }
    }
}

class SubscriptionFileThread implements Runnable {

    HttpURLConnection urc;
    JLabel status;

    public SubscriptionFileThread(HttpURLConnection urc, JLabel status) {
        this.urc = urc;
        this.status = status;
    }

    public void run() {
        InputStream is = null;
        try
        {
            is = urc.getInputStream();
            int[] buffer = new int[1000];
            int rs = 0;
            int i = 0;

            while (rs != -1)
            {
                try
                {
                    rs = is.read();

                    if (rs != -1)
                    {
                        buffer[i] = rs;
                    }
                    i++;
                } catch (IOException ex)
                {
                    Logger.getLogger(SubscriptionFileThread.class.getName()).log(Level.SEVERE, null, ex);
                }
            }

            StringBuilder result = new StringBuilder();
            int j = 0;
            for (j = 0; j < i; j++)
            {
                result.append(Character.toString((char) buffer[j]));
            }

            String[] headers = result.toString().split("\n");
            HashMap<String, String> headerMap = new HashMap<String, String>();
            i = 0;
            while (1 == 1)
            {
                try
                {
                    String[] nameVal = headers[i].split(": ");
                    String name = nameVal[0];
                    String value = nameVal[1].trim();
                    headerMap.put(name, value);
                } catch (Exception ex)
                {
                    break;
                }
                i++;
            }

            if (headerMap.containsKey("Subscription"))
            {
                if (headerMap.containsKey("Title"))
                {
                    if (headerMap.containsKey("Category"))
                    {
                        Subscription s = new Subscription();
                        s.setURL(headerMap.get("Subscription"));
                        s.setTitle(headerMap.get("Title"));
                        s.setCategory(headerMap.get("Category"));
                        
                        if (Functions.xor(headerMap.containsKey("Verification"),headerMap.containsKey("Verification-ID")))
                        {
                            if (headerMap.containsKey("Verification"))
                            {
                                AskForPasswordForm afpf = new AskForPasswordForm(new JFrame(),true);
                                afpf.setVisible(true);
                                
                                if (afpf.getEntered() || afpf.getPassword().equals(""))
                                {
                                    MD5 md5 = new MD5(afpf.getPassword());
                                    MD5 hash = new MD5(s.getTitle() + ":" + md5.hash() + ":" + headerMap.get("Verification-ID"));
                                    
                                    if (hash.hash().equals(headerMap.get("Verification")))
                                    {
                                        s.setPassword(afpf.getPassword());
                                    } else {
                                        status.setText("Error: Incorrect password entered");
                                        return;
                                    }
                                } else {
                                    status.setText("Error: No password entered");
                                    return;
                                }
                            } else {
                                s.setPassword("");
                            }
                            
                            Wrapper.addSubscription(s);

                            XmlRpc xmlrpc = new XmlRpc("addSubscription");
                            xmlrpc.addParam(headerMap.get("Subscription"));
                            xmlrpc.addParam(headerMap.get("Category"));
                            xmlrpc.execute();

                            status.setText("You've sucessfully subscribed to that website");
                        }
                    } else {
                        status.setText("Error: Subscription file is not well-formed");
                    }
                } else {
                    status.setText("Error: Subscription file is not well-formed");
                }
            } else {
                status.setText("Error: Subscription file is not well-formed");
            }
        } catch (FileNotFoundException ex)
        {
            status.setText("Error: Subscription File doesn't exist");
        } catch (IOException ex)
        {
            Logger.getLogger(SubscriptionFileThread.class.getName()).log(Level.SEVERE, null, ex);
        } finally
        {
            try
            {
                is.close();
            } catch (IOException ex)
            {
                Logger.getLogger(SubscriptionFileThread.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
}