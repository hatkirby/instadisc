package com.fourisland.instadisc.DownloadItem;

public class DeinitalizeModeThread implements Runnable
{
    public void run() {
        ModeControl.INSTANCE.modeDeinitalize();
    }
}