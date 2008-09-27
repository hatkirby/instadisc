package com.fourisland.instadisc.DownloadItem;

import java.util.Timer;

public interface DownloadItemMode
{
    public void modeInitalize();
    public void modeDeinitalize();
    
    public void requestRetained();
    
    public int setTimer();
    public void timerTick();
}