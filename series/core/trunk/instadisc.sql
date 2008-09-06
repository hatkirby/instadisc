-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Sep 06, 2008 at 05:38 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.3
-- 
-- Database: `instadisc_series`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `config`
-- 

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `subscriptions`
-- 

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL auto_increment,
  `identity` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `personal` varchar(5) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `identity` (`identity`,`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

