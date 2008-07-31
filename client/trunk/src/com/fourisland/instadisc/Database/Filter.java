/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Database;

import com.sleepycat.persist.model.Entity;
import com.sleepycat.persist.model.PrimaryKey;

/**
 *
 * @author hatkirby
 */
@Entity
public class Filter {

    @PrimaryKey
    private Integer id;
    private String subscription;
    private String field;
    private Boolean equal;
    private String test;

    public Integer getID() {
        return id;
    }

    public String getSubscription() {
        return subscription;
    }

    public String getField() {
        return field;
    }

    public Boolean getEqual() {
        return equal;
    }

    public String getTest() {
        return test;
    }

    public void setID(Integer id) {
        this.id = id;
    }

    public void setSubscription(String subscription) {
        this.subscription = subscription;
    }

    public void setField(String field) {
        this.field = field;
    }

    public void setEqual(Boolean equal) {
        this.equal = equal;
    }

    public void setTest(String test) {
        this.test = test;
    }
}
