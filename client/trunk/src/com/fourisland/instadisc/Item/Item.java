/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.fourisland.instadisc.Item;

import com.fourisland.instadisc.XmlRpc;
import java.util.HashMap;

/**
 *
 * @author hatkirby
 */
public class Item {
    
    HashMap<String,String> headerMap;

    public Item(HashMap<String,String> headerMap)
    {
        this.headerMap = headerMap;
    }
    
    public void start()
    {
        WellFormedItem wfi = new WellFormedItem(this);
        if (wfi.check())
        {
            XmlRpc xmlrpc = new XmlRpc("deleteItem");
            xmlrpc.addParam(Integer.decode(headerMap.get("ID")));
            xmlrpc.execute();
        }
    }
}
