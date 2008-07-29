/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Database;

import com.sleepycat.persist.model.Entity;
import com.sleepycat.persist.model.PrimaryKey;
import java.util.HashMap;

/**
 *
 * @author hatkirby
 */
@Entity
public class Item {

    @PrimaryKey
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
}
