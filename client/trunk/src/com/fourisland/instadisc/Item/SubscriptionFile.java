/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Item;

import com.fourisland.instadisc.Database.Subscription;
import com.fourisland.instadisc.Database.Wrapper;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.HashMap;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JLabel;

/**
 *
 * @author hatkirby
 */
public class SubscriptionFile {

    public HashMap<String, String> headerMap;

    public SubscriptionFile(URL url, JLabel status) {
        status.setText("Checking....");
        try {
            HttpURLConnection urc = (HttpURLConnection) url.openConnection();
            Thread th = new Thread(new SubscriptionFileThread(urc, status));
            th.start();
        } catch (IOException ex) {
            Logger.getLogger(SubscriptionFile.class.getName()).log(Level.SEVERE, null, ex);
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
        try {
            is = urc.getInputStream();
            int[] buffer = new int[1000];
            int rs = 0;
            int i = 0;

            while (rs != -1) {
                try {
                    rs = is.read();

                    if (rs != -1) {
                        buffer[i] = rs;
                    }
                    i++;
                } catch (IOException ex) {
                    Logger.getLogger(SubscriptionFileThread.class.getName()).log(Level.SEVERE, null, ex);
                }
            }

            StringBuilder result = new StringBuilder();
            int j = 0;
            for (j = 0; j < i; j++) {
                result.append(Character.toString((char) buffer[j]));
            }

            String[] headers = result.toString().split("\n");
            HashMap<String, String> headerMap = new HashMap<String, String>();
            i = 0;
            while (1 == 1) {
                try {
                    String[] nameVal = headers[i].split(": ");
                    String name = nameVal[0];
                    String value = nameVal[1].trim();
                    headerMap.put(name, value);
                } catch (Exception ex) {
                    break;
                }
                i++;
            }

            if (headerMap.containsKey("Subscription")) {
                if (headerMap.containsKey("Title")) {
                    if (headerMap.containsKey("Category")) {
                        Subscription s = new Subscription();
                        s.setURL(headerMap.get("Subscription"));
                        s.setTitle(headerMap.get("Title"));
                        s.setCategory(headerMap.get("Category"));
                        Wrapper.addSubscription(s);

                        status.setText("You've sucessfully subscribed to that website");
                    } else {
                        status.setText("Error: Subscription file is not well-formed");
                    }
                } else {
                    status.setText("Error: Subscription file is not well-formed");
                }
            } else {
                status.setText("Error: Subscription file is not well-formed");
            }
        } catch (IOException ex) {
            Logger.getLogger(SubscriptionFileThread.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            try {
                is.close();
            } catch (IOException ex) {
                Logger.getLogger(SubscriptionFileThread.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
}
