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
        } else if (category.equals("blog-comment"))
        {
            return new ImageIcon(Comment.comment);
        } else if (category.equals("forum-post"))
        {
            return new ImageIcon(Fourm.fourm);
        } else if (category.equals("instadisc"))
        {
            return new ImageIcon(InstaDiscIcon.instadiscicon);
        } else if (category.equals("email"))
        {
            return new ImageIcon(Email.email);
        }
        return null;
    }
    
    public static boolean checkForRequiredSemantics(HashMap<String, String> headerMap) {
        boolean good = true;
        String category = Wrapper.getSubscription(headerMap.get("Subscription")).getCategory();
        String[] semantics = getRequiredSemantics(category);
        int i=0;
        
        for (i=0;i<semantics.length;i++)
        {
            good = (good ? WellFormedItem.checkForRequiredHeader(headerMap, semantics[i]) : false);
        }
        
        return good;
    }
    
    public static String[] getRequiredSemantics(String category)
    {
        if (category.equals("forum-post"))
        {
            return new String[] {"forum"};
        } else {
            return new String[] {};
        }
    }
    
}
