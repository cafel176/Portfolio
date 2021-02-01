<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/30
 * Time: 17:20
 */

function initTop($name)
{
    return 'CREATE TABLE IF NOT EXISTS `'.$name.'_table`
        (
        `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT \'empty\',
        `tag` varchar(255),
        `desc` varchar(255),
          PRIMARY KEY (`id`),
          UNIQUE (`name`)
        ) 
        DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
        ROW_FORMAT=DYNAMIC
        DELAY_KEY_WRITE=0';
}

function initTable($name)
{
    return 'CREATE TABLE IF NOT EXISTS `'.$name.'_table`
        (
        `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT \'empty\',
        `tag` varchar(255),
        `desc` varchar(255),
        `content` varchar(255),
        `text` varchar(255),
        `link` varchar(255),
        `group` varchar(255) NOT NULL,
        `subgroup` varchar(255) NOT NULL,
        `limit` int(8) unsigned NOT NULL DEFAULT \'1\',
        `canedit` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
          PRIMARY KEY (`id`)
        ) 
        DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
        ROW_FORMAT=DYNAMIC
        DELAY_KEY_WRITE=0';
}

function initGroup($name)
{
    return 'CREATE TABLE IF NOT EXISTS `'.$name.'`
        (
        `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT \'empty\',
        `tag` varchar(255),
        `desc` varchar(255),
        `limit` int(8) unsigned NOT NULL DEFAULT \'1\',
        `canedit` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
          PRIMARY KEY (`id`),
          UNIQUE (`name`)
        ) 
        DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
        ROW_FORMAT=DYNAMIC
        DELAY_KEY_WRITE=0';
}

function initSubGroup($name)
{
    return 'CREATE TABLE IF NOT EXISTS `'.$name.'_subgroup`
        (
        `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT \'empty\',
        `tag` varchar(255),
        `desc` varchar(255),
        `group` varchar(255) NOT NULL,
        `limit` int(8) unsigned NOT NULL DEFAULT \'1\',
        `canedit` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
          PRIMARY KEY (`id`),
          UNIQUE (`name`)
        ) 
        DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
        ROW_FORMAT=DYNAMIC
        DELAY_KEY_WRITE=0';
}

function initLimitInfo($name)
{
    return 'CREATE TABLE IF NOT EXISTS `'.$name.'_table`
        (
        `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT \'empty\',
        `tag` varchar(255),
        `desc` varchar(255),
          PRIMARY KEY (`id`),
          UNIQUE (`name`)
        ) 
        DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
        ROW_FORMAT=DYNAMIC
        DELAY_KEY_WRITE=0';
}

function initAdminInfo($name)
{
    return 'CREATE TABLE IF NOT EXISTS `'.$name.'_table`
        (
        `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT \'empty\',
        `password` varchar(255) NOT NULL DEFAULT \'empty\',
        `email` varchar(255),
        `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `limits` varchar(255) NOT NULL DEFAULT \'1\',
          PRIMARY KEY (`id`),
          UNIQUE (`name`)
        ) 
        DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
        ROW_FORMAT=DYNAMIC
        DELAY_KEY_WRITE=0';
}

function initFiles($name)
{
    return 'CREATE TABLE IF NOT EXISTS `'.$name.'_table`
        (
        `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT \'empty\',
        `fileName` varchar(255) NOT NULL DEFAULT \'empty\',
        `desc` varchar(255),
        `who` varchar(255) NOT NULL DEFAULT \'empty\',
        `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `limit` int(8) unsigned NOT NULL DEFAULT \'1\',
        `canedit` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
          PRIMARY KEY (`id`),
          UNIQUE (`name`),
          UNIQUE (`filename`)
        ) 
        DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
        ROW_FORMAT=DYNAMIC
        DELAY_KEY_WRITE=0';
}