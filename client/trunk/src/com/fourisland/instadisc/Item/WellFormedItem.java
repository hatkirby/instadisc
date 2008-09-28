/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Item;

import com.fourisland.instadisc.Database.Filter;
import com.fourisland.instadisc.Database.Subscription;
import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.DownloadItem.ModeControl;
import com.fourisland.instadisc.Functions;
import com.fourisland.instadisc.Item.Categories.Category;
import com.fourisland.instadisc.XmlRpc;
import java.net.MalformedURLException;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;
import java.security.InvalidAlgorithmParameterException;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.util.Collection;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map.Entry;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.crypto.BadPaddingException;
import javax.crypto.Cipher;
import javax.crypto.IllegalBlockSizeException;
import javax.crypto.NoSuchPaddingException;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;

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
        good = (good ? checkForEncryption() : false);
        good = (good ? Category.checkForLegalCategory(aThis.headerMap) : false);
        good = (good ? Category.checkForRequiredSemantics(aThis.headerMap) : false);
        good = (good ? checkForProperVerification() : false);
        good = (good ? checkForFilterInvalidation() : false);
        good = (good ? checkForSafeURL() : false);
        return good;
    }

    private boolean checkForEncryption() {
        if (!Wrapper.getSubscription(aThis.headerMap.get("Subscription")).getPassword().equals(""))
        {
            try
            {
                Subscription s = Wrapper.getSubscription(aThis.headerMap.get("Subscription"));
                MD5 md5 = new MD5(Functions.padright(s.getPassword(), aThis.headerMap.get("Encryption-ID"), 16).substring(0, 16));
                String key = md5.hash().substring(0, 16);
                String iv = Functions.reverse(key);

                Cipher cipher = Cipher.getInstance("AES/CBC/NoPadding");
                SecretKeySpec keySpec = new SecretKeySpec(key.getBytes(), "AES");
                IvParameterSpec ivSpec = new IvParameterSpec(iv.getBytes());
                cipher.init(Cipher.DECRYPT_MODE, keySpec, ivSpec);

                aThis.headerMap.put("Title", new String(cipher.doFinal(Functions.hexToBytes(aThis.headerMap.get("Title")))).trim());
                aThis.headerMap.put("Author", new String(cipher.doFinal(Functions.hexToBytes(aThis.headerMap.get("Author")))).trim());
                aThis.headerMap.put("URL", new String(cipher.doFinal(Functions.hexToBytes(aThis.headerMap.get("URL")))).trim());
                
                HashMap<String, String> temp = new HashMap<String, String>(aThis.headerMap);
                temp.remove("ID");
                temp.remove("Verification");
                temp.remove("Verification-ID");
                temp.remove("Subscription");
                temp.remove("Title");
                temp.remove("Author");
                temp.remove("URL");
                temp.remove("Encryption-ID");
                
                Collection<Entry<String,String>> vals = temp.entrySet();
                Iterator<Entry<String,String>> i = vals.iterator();
                while (i.hasNext())
                {
                    Entry<String,String> e = (Entry<String,String>) i.next();
                    aThis.headerMap.put(e.getKey(), new String(cipher.doFinal(Functions.hexToBytes(e.getValue()))).trim());    
                }

                return true;
            } catch (IllegalBlockSizeException ex)
            {
                Logger.getLogger(WellFormedItem.class.getName()).log(Level.SEVERE, null, ex);
                return false;
            } catch (BadPaddingException ex)
            {
                Logger.getLogger(WellFormedItem.class.getName()).log(Level.SEVERE, null, ex);
                return false;
            } catch (InvalidKeyException ex)
            {
                Logger.getLogger(WellFormedItem.class.getName()).log(Level.SEVERE, null, ex);
                return false;
            } catch (InvalidAlgorithmParameterException ex)
            {
                Logger.getLogger(WellFormedItem.class.getName()).log(Level.SEVERE, null, ex);
                return false;
            } catch (NoSuchAlgorithmException ex)
            {
                Logger.getLogger(WellFormedItem.class.getName()).log(Level.SEVERE, null, ex);
                return false;
            } catch (NoSuchPaddingException ex)
            {
                Logger.getLogger(WellFormedItem.class.getName()).log(Level.SEVERE, null, ex);
                return false;
            }
        } else {
            return true;
        }
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

    private boolean checkForProperVerification() {
        boolean good = false;
        try {
            String vid = aThis.headerMap.get("Verification-ID");
            int ivid = Integer.decode(vid);
            Verification ver = new Verification(ivid);
            good = aThis.headerMap.get("Verification").equals(ver.getHash());
        } catch (VerificationIDReusedException ex) {
            ModeControl.INSTANCE.resendItem(Integer.decode(aThis.headerMap.get("ID")));
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
        if (!good)
        {
            Subscription s = new Subscription();
            s.setURL(aThis.headerMap.get("Subscription"));
            
            SubscriptionFile.deleteSubscription(s, false);
        } else {
            if (!Wrapper.getSubscription(aThis.headerMap.get("Subscription")).getPassword().equals(""))
            {
                good = (good ? checkForRequiredHeader("Encryption-ID") : false);
            }
        }
        
        return good;
    }
}
