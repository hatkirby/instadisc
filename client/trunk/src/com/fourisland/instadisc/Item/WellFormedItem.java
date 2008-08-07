/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Item;

import com.fourisland.instadisc.Database.Filter;
import com.fourisland.instadisc.Database.Subscription;
import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.Item.Categories.Category;
import com.fourisland.instadisc.XmlRpc;
import java.net.MalformedURLException;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;
import java.util.HashMap;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hatkirby
 */
public class WellFormedItem {

    Item aThis;

    public WellFormedItem(Item aThis) {
        this.aThis = aThis;
    }

    public boolean check() {
        boolean good = true;
        good = (good ? checkForRequiredHeaders() : false);
        good = (good ? checkForSubscription() : false);
        good = (good ? Category.checkForLegalCategory(aThis.headerMap) : false);
        good = (good ? Category.checkForRequiredSemantics(aThis.headerMap) : false);
        good = (good ? checkForProperVerification() : false);
        good = (good ? checkForFilterInvalidation() : false);
        good = (good ? checkForSafeURL() : false);
        return good;
    }

    private boolean checkForEqualFilters() {
        boolean good = true;

        Filter[] filters = Wrapper.getAllFilter();
        int i = 0;
        for (i = 0; i < filters.length; i++) {
            if (filters[i].getSubscription().equals(aThis.headerMap.get("Subscription"))) {
                if (filters[i].getEqual()) {
                    good = (good ? aThis.headerMap.get(filters[i].getField()).contains(filters[i].getTest()) : false);
                }
            }
        }

        return good;
    }

    private boolean checkForFilterInvalidation() {
        boolean good = true;
        good = (good ? checkForEqualFilters() : false);
        good = (good ? checkForInequalFilters() : false);

        if (!good) {
            XmlRpc xmlrpc = new XmlRpc("deleteItem");
            xmlrpc.addParam(Integer.decode(aThis.headerMap.get("ID")));
            xmlrpc.execute();
        }

        return good;
    }

    private boolean checkForInequalFilters() {
        boolean good = true;
        boolean start = false;

        Filter[] filters = Wrapper.getAllFilter();
        int i = 0;
        for (i = 0; i < filters.length; i++) {
            if (filters[i].getSubscription().equals(aThis.headerMap.get("Subscription"))) {
                if (!filters[i].getEqual()) {
                    if (!start) {
                        good = false;
                        start = true;
                    }
                    good = (good ? true : !aThis.headerMap.get(filters[i].getField()).contains(filters[i].getTest()));
                }
            }
        }

        return good;
    }

    private boolean checkForLegalCategory(String string, boolean good) {
        return (good ? true : Wrapper.getSubscription(aThis.headerMap.get("Subscription")).getCategory().equals(string));
    }

    private boolean checkForProperVerification() {
        boolean good = false;
        try {
            String vid = aThis.headerMap.get("Verification-ID");
            int ivid = Integer.decode(vid);
            Verification ver = new Verification(ivid);
            good = aThis.headerMap.get("Verification").equals(ver.getHash());
        } catch (VerificationIDReusedException ex) {
            XmlRpc xmlrpc = new XmlRpc("resendItem");
            String id = aThis.headerMap.get("ID");
            int iid = Integer.decode(id);
            xmlrpc.addParam(iid);
            xmlrpc.execute();
        } catch (Exception ex) {
            Logger.getLogger(WellFormedItem.class.getName()).log(Level.SEVERE, null, ex);
        }
        return good;
    }

    private boolean checkForRequiredHeaders() {
        boolean good = true;
        good = (good ? checkForRequiredHeader("ID") : false);
        good = (good ? checkForRequiredHeader("Verification") : false);
        good = (good ? checkForRequiredHeader("Verification-ID") : false);
        good = (good ? checkForRequiredHeader("Subscription") : false);
        good = (good ? checkForRequiredHeader("Title") : false);
        good = (good ? checkForRequiredHeader("Author") : false);
        good = (good ? checkForRequiredHeader("URL") : false);
        return good;
    }

    private boolean checkForRequiredHeader(String string) {
        return checkForRequiredHeader(aThis.headerMap, string);
    }

    public static boolean checkForRequiredHeader(HashMap<String, String> headerMap, String string) {
        return headerMap.containsKey(string);
    }

    private boolean checkForSafeURL() {
        try {
            URL url = new URL(aThis.headerMap.get("URL"));
            URI subUrl = new URI(aThis.headerMap.get("Subscription"));

            return url.getHost().equals(subUrl.getHost());
        } catch (URISyntaxException ex) {
            Logger.getLogger(WellFormedItem.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        } catch (MalformedURLException ex) {
            Logger.getLogger(WellFormedItem.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }
    }

    private boolean checkForSubscription() {
        boolean good = Wrapper.existsSubscription(aThis.headerMap.get("Subscription"));
        if (!good) {
            Subscription s = new Subscription();
            s.setURL(aThis.headerMap.get("Subscription"));
            
            SubscriptionFile.deleteSubscription(s, false);
        }
        return good;
    }
}
