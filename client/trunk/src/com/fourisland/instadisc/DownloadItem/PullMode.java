package com.fourisland.instadisc.DownloadItem;

import com.fourisland.instadisc.Item.Item;
import com.fourisland.instadisc.XmlRpc;

public class PullMode implements DownloadItemMode
{
    public void modeInitalize() {
    }

    public void modeDeinitalize() {
    }

    public void requestRetained() {
        XmlRpc xmlrpc = new XmlRpc("requestRetained");
        Item item = new Item((String) xmlrpc.execute());
        item.start();
        
        while (item.headerMap.containsKey("More"))
        {
            xmlrpc = new XmlRpc("sendItem");
            xmlrpc.addParam(Integer.decode(item.headerMap.get("More")));
            item = new Item((String) xmlrpc.execute());
            item.start();
        }
    }
    
    public void resendItem(int id) {
        XmlRpc xmlrpc = new XmlRpc("resendItem");
        xmlrpc.addParam(id);
        Item item = new Item((String) xmlrpc.execute());
        item.start();
    }

    public int setTimer() {
        return (60 * 5); // 5 minutes
    }

    public void timerTick() {
        requestRetained();
    }
}