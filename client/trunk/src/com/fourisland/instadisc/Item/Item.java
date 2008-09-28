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
import java.util.Calendar;
import java.util.HashMap;

/**
 *
 * @author hatkirby
 */
public class Item {

    public HashMap<String, String> headerMap;

    public Item(String result) {
        String[] headers = result.toString().split("\n");
        HashMap<String, String> tempHeaderMap = new HashMap<String, String>();
        int i = 0;
        while (1 == 1)
        {
            try
            {
                String[] nameVal = headers[i].split(": ");
                String name = nameVal[0];
                String value = nameVal[1].trim().replace("__INSTADISC__", ": ");
                tempHeaderMap.put(name, value);
            } catch (Exception ex)
            {
                break;
            }
            i++;
        }
        
        this.headerMap = tempHeaderMap;
    }

    public void start() {
        WellFormedItem wfi = new WellFormedItem(this);
        if (wfi.check()) {
            XmlRpc xmlrpc = new XmlRpc("deleteItem");
            xmlrpc.addParam(Integer.decode(headerMap.get("ID")));
            xmlrpc.execute();
            
            if (Wrapper.countItem() >= Integer.decode(Wrapper.getConfig("itemBufferSize"))) {
                while (Wrapper.countItem() >= Integer.decode(Wrapper.getConfig("itemBufferSize"))) {
                    Wrapper.dropFromTopItem();
                }
            }

            com.fourisland.instadisc.Database.Item item = new com.fourisland.instadisc.Database.Item();
            item.setID(Integer.decode(headerMap.get("ID")));
            item.setSubscription(headerMap.get("Subscription"));
            item.setTitle(headerMap.get("Title"));
            item.setAuthor(headerMap.get("Author"));
            item.setURL(headerMap.get("URL"));

            HashMap<String, String> temp = new HashMap<String, String>(headerMap);
            temp.remove("ID");
            temp.remove("Verification");
            temp.remove("Verification-ID");
            temp.remove("Subscription");
            temp.remove("Title");
            temp.remove("Author");
            temp.remove("URL");
            item.setSemantics(temp);
            
            item.setUnread(true);
            item.setRecieved(Calendar.getInstance().getTime());
            Wrapper.addItem(item);

            ((InstaDiscView) InstaDiscApp.getApplication().getMainView()).refreshItemPane();

            if (SystemTray.isSupported()) {
                InstaDiscApp.ti.displayMessage("New item recieved!", Wrapper.getSubscription(headerMap.get("Subscription")).getTitle() + ", " + headerMap.get("Title") + " by " + headerMap.get("Author"), MessageType.INFO);
            }
        }
    }
}
