/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Item;

import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.InstaDiscApp;
import com.fourisland.instadisc.InstaDiscView;
import com.fourisland.instadisc.XmlRpc;
import java.awt.SystemTray;
import java.awt.TrayIcon.MessageType;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.HashMap;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hatkirby
 */
public class Item {

    HashMap<String, String> headerMap;

    public Item(HashMap<String, String> headerMap) {
        this.headerMap = headerMap;
    }

    public void start() {
        WellFormedItem wfi = new WellFormedItem(this);
        if (wfi.check()) {
            XmlRpc xmlrpc = new XmlRpc("deleteItem");
            xmlrpc.addParam(Integer.decode(headerMap.get("ID")));
            //xmlrpc.execute();
            
            if (Wrapper.countItem() >= Integer.decode(Wrapper.getConfig("itemsToHold")))
            {
                Wrapper.dropFromTopItem();
            }
            
            try {
                com.fourisland.instadisc.Database.Item di = new com.fourisland.instadisc.Database.Item();
                di.setID(Integer.decode(headerMap.get("ID")));
                di.setSubscription(headerMap.get("Subscription"));
                di.setTitle(headerMap.get("Title"));
                di.setAuthor(headerMap.get("Author"));
                di.setURL(new URL(headerMap.get("URL")).toString());
                
                HashMap<String, String> temp = (HashMap<String, String>) headerMap.clone();
                temp.remove("ID");
                temp.remove("Verification");
                temp.remove("Verification-ID");
                temp.remove("Subscription");
                temp.remove("Title");
                temp.remove("Author");
                temp.remove("URL");
                di.setSemantics(temp);
                
                Wrapper.addItem(di);
            } catch (MalformedURLException ex) {
                Logger.getLogger(Item.class.getName()).log(Level.SEVERE, null, ex);
            }
            
            if (SystemTray.isSupported())
            {
                InstaDiscApp.ti.displayMessage("New item recieved!", Wrapper.getSubscription(headerMap.get("Subscription")).getTitle() + ", " + headerMap.get("Title") + " by " + headerMap.get("Author"), MessageType.INFO);
            }

            ((InstaDiscView)InstaDiscApp.getApplication().getMainView()).refreshItemPane();
        }
    }
}
