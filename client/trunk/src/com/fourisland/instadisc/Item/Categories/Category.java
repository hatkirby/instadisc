/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.fourisland.instadisc.Item.Categories;

import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.Item.WellFormedItem;
import java.util.HashMap;
import javax.swing.Icon;
import javax.swing.ImageIcon;

/**
 *
 * @author hatkirby
 */
public class Category {

    public static Icon iconFromCategory(String category)
    {
        if (category.equals("blog-post"))
        {
            return new ImageIcon(Blogpost.blogpost);
        } else if (category.equals("instadisc"))
        {
            return new ImageIcon(InstaDiscIcon.instadiscicon);
        }
        return null;
    }
    
    public static boolean checkForRequiredSemantics(HashMap<String, String> headerMap) {
        boolean good = true;
        String category = Wrapper.getSubscription(headerMap.get("Subscription")).getCategory();
        if (category.equals("forum-post")) {
            good = (good ? WellFormedItem.checkForRequiredHeader(headerMap, "forum") : false);
        }
        return good;
    }
    
}
