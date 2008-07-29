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
public class Subscription {

    @PrimaryKey
    private String url;
    private String category;
    private String title;
    
    public String getURL()
    {
        return url;
    }
    
    public String getCategory()
    {
        return category;
    }
    
    public String getTitle()
    {
        return title;
    }
    
    public void setURL(String url)
    {
        this.url = url;
    }
    
    public void setCategory(String category)
    {
        this.category = category;
    }
    
    public void setTitle(String title)
    {
        this.title = title;
    }
}
