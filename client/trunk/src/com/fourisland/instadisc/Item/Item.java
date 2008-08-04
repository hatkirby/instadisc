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
import java.util.HashMap;

/**
 *
 * @author hatkirby
 */
public class Item {

    HashMap<String, String> headerMap;
    private Integer id;
    private String subscription;
    private String title;
    private String author;
    private String url;
    private HashMap<String, String> semantics;

    public Item() {
        semantics = new HashMap<String, String>();
    }

    public Integer getID() {
        return id;
    }

    public String getSubscription() {
        return subscription;
    }

    public String getTitle() {
        return title;
    }

    public String getAuthor() {
        return author;
    }

    public String getURL() {
        return url;
    }

    public HashMap<String, String> getSemantics() {
        return semantics;
    }

    public void setID(Integer id) {
        this.id = id;
    }

    public void setSubscription(String subscription) {
        this.subscription = subscription;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public void setAuthor(String author) {
        this.author = author;
    }

    public void setURL(String url) {
        this.url = url;
    }

    public void setSemantics(HashMap<String, String> semantics) {
        this.semantics = semantics;
    }

    public String getSemantics(String key) {
        return semantics.get(key);
    }

    public void putSemantics(String key, String value) {
        semantics.put(key, value);
    }

    public Item(HashMap<String, String> headerMap) {
        this();
        this.headerMap = headerMap;
    }

    public void start() {
        WellFormedItem wfi = new WellFormedItem(this);
        if (wfi.check()) {
            XmlRpc xmlrpc = new XmlRpc("deleteItem");
            xmlrpc.addParam(Integer.decode(headerMap.get("ID")));
            xmlrpc.execute();

            setID(Integer.decode(headerMap.get("ID")));
            setSubscription(headerMap.get("Subscription"));
            setTitle(headerMap.get("Title"));
            setAuthor(headerMap.get("Author"));
            setURL(headerMap.get("URL"));

            HashMap<String, String> temp = new HashMap<String, String>(headerMap);
            temp.remove("ID");
            temp.remove("Verification");
            temp.remove("Verification-ID");
            temp.remove("Subscription");
            temp.remove("Title");
            temp.remove("Author");
            temp.remove("URL");
            setSemantics(temp);

            ((InstaDiscView) InstaDiscApp.getApplication().getMainView()).addItemPane(this);

            if (SystemTray.isSupported()) {
                InstaDiscApp.ti.displayMessage("New item recieved!", Wrapper.getSubscription(headerMap.get("Subscription")).getTitle() + ", " + headerMap.get("Title") + " by " + headerMap.get("Author"), MessageType.INFO);
            }
        }
    }
}
