-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Aug 03, 2008 at 04:54 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.3
-- 
-- Database: `instadisc`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `centralServers`
-- 

CREATE TABLE IF NOT EXISTS `centralServers` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `xmlrpc` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `config`
-- 

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `inbox`
-- 

CREATE TABLE IF NOT EXISTS `inbox` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `itemID` int(11) NOT NULL,
  `subscription` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `semantics` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `oldVerID`
-- 

CREATE TABLE IF NOT EXISTS `oldVerID` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `verID` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `pending`
-- 

CREATE TABLE IF NOT EXISTS `pending` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `pending2`
-- 

CREATE TABLE IF NOT EXISTS `pending2` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `subscriptions`
-- 

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` mediumint(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `owner` varchar(5) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `nextItemID` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

