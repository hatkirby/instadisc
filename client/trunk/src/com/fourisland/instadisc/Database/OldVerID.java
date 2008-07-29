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
public class OldVerID {
    
    @PrimaryKey
    private Integer ID;
    
    public void setID(Integer ID)
    {
        this.ID = ID;
    }
    
    public Integer getID()
    {
        return ID;
    }

}
