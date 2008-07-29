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
public class IDConfig {
    
    @PrimaryKey
    private String key;
    
    private String value;
    
    public String getKey()
    {
        return key;
    }
    
    public String getValue()
    {
        return value;
    }
    
    public void setKey(String key)
    {
        this.key = key;
    }
    
    public void setValue(String value)
    {
        this.value = value;
    }

}
