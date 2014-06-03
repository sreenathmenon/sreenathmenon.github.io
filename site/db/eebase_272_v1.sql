# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.25)
# Database: eebase
# Generation Time: 2013-11-06 02:31:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table exp_accessories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_accessories`;

CREATE TABLE `exp_accessories` (
  `accessory_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(75) NOT NULL DEFAULT '',
  `member_groups` varchar(255) NOT NULL DEFAULT 'all',
  `controllers` text,
  `accessory_version` varchar(12) NOT NULL,
  PRIMARY KEY (`accessory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_accessories` WRITE;
/*!40000 ALTER TABLE `exp_accessories` DISABLE KEYS */;

INSERT INTO `exp_accessories` (`accessory_id`, `class`, `member_groups`, `controllers`, `accessory_version`)
VALUES
	(2,'Devotee_acc','1|5','addons|addons_accessories|addons_extensions|addons_fieldtypes|addons_modules|addons_plugins|admin_content|admin_system|content|content_edit|content_files|content_files_modal|content_publish|design|homepage|members|myaccount|tools|tools_communicate|tools_data|tools_logs|tools_utilities','1.2.4'),
	(3,'Freeform_acc','1|5','addons|addons_accessories|addons_extensions|addons_fieldtypes|addons_modules|addons_plugins|admin_content|admin_system|content|content_edit|content_files|content_files_modal|content_publish|design|homepage|members|myaccount|tools|tools_communicate|tools_data|tools_logs|tools_utilities','4.1.3');

/*!40000 ALTER TABLE `exp_accessories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_actions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_actions`;

CREATE TABLE `exp_actions` (
  `action_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `csrf_exempt` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_actions` WRITE;
/*!40000 ALTER TABLE `exp_actions` DISABLE KEYS */;

INSERT INTO `exp_actions` (`action_id`, `class`, `method`, `csrf_exempt`)
VALUES
	(1,'Channel','submit_entry',0),
	(2,'Channel','combo_loader',0),
	(3,'Channel','submit_entry',0),
	(4,'Channel','filemanager_endpoint',0),
	(5,'Channel','smiley_pop',0),
	(6,'Member','registration_form',0),
	(7,'Member','register_member',0),
	(8,'Member','activate_member',0),
	(9,'Member','member_login',0),
	(10,'Member','member_logout',0),
	(11,'Member','send_reset_token',0),
	(12,'Member','process_reset_password',0),
	(13,'Member','send_member_email',0),
	(14,'Member','update_un_pw',0),
	(15,'Member','member_search',0),
	(16,'Member','member_delete',0),
	(17,'Rte','get_js',0),
	(18,'Updater','actionGeneralRouter',0),
	(20,'Freeform','save_form',0);

/*!40000 ALTER TABLE `exp_actions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_captcha
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_captcha`;

CREATE TABLE `exp_captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) unsigned NOT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `word` varchar(20) NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_categories`;

CREATE TABLE `exp_categories` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_id` int(6) unsigned NOT NULL,
  `parent_id` int(4) unsigned NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  `cat_url_title` varchar(75) NOT NULL,
  `cat_description` text,
  `cat_image` varchar(120) DEFAULT NULL,
  `cat_order` int(4) unsigned NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `group_id` (`group_id`),
  KEY `cat_name` (`cat_name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_category_field_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_category_field_data`;

CREATE TABLE `exp_category_field_data` (
  `cat_id` int(4) unsigned NOT NULL,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_id` int(4) unsigned NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `site_id` (`site_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_category_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_category_fields`;

CREATE TABLE `exp_category_fields` (
  `field_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_id` int(4) unsigned NOT NULL,
  `field_name` varchar(32) NOT NULL DEFAULT '',
  `field_label` varchar(50) NOT NULL DEFAULT '',
  `field_type` varchar(12) NOT NULL DEFAULT 'text',
  `field_list_items` text NOT NULL,
  `field_maxl` smallint(3) NOT NULL DEFAULT '128',
  `field_ta_rows` tinyint(2) NOT NULL DEFAULT '8',
  `field_default_fmt` varchar(40) NOT NULL DEFAULT 'none',
  `field_show_fmt` char(1) NOT NULL DEFAULT 'y',
  `field_text_direction` char(3) NOT NULL DEFAULT 'ltr',
  `field_required` char(1) NOT NULL DEFAULT 'n',
  `field_order` int(3) unsigned NOT NULL,
  PRIMARY KEY (`field_id`),
  KEY `site_id` (`site_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_category_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_category_groups`;

CREATE TABLE `exp_category_groups` (
  `group_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_name` varchar(50) NOT NULL,
  `sort_order` char(1) NOT NULL DEFAULT 'a',
  `exclude_group` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_html_formatting` char(4) NOT NULL DEFAULT 'all',
  `can_edit_categories` text,
  `can_delete_categories` text,
  PRIMARY KEY (`group_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_category_posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_category_posts`;

CREATE TABLE `exp_category_posts` (
  `entry_id` int(10) unsigned NOT NULL,
  `cat_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`entry_id`,`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_channel_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_channel_data`;

CREATE TABLE `exp_channel_data` (
  `entry_id` int(10) unsigned NOT NULL,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `channel_id` int(4) unsigned NOT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `channel_id` (`channel_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_channel_entries_autosave
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_channel_entries_autosave`;

CREATE TABLE `exp_channel_entries_autosave` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `original_entry_id` int(10) unsigned NOT NULL,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `channel_id` int(4) unsigned NOT NULL,
  `author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `forum_topic_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `title` varchar(100) NOT NULL,
  `url_title` varchar(75) NOT NULL,
  `status` varchar(50) NOT NULL,
  `versioning_enabled` char(1) NOT NULL DEFAULT 'n',
  `view_count_one` int(10) unsigned NOT NULL DEFAULT '0',
  `view_count_two` int(10) unsigned NOT NULL DEFAULT '0',
  `view_count_three` int(10) unsigned NOT NULL DEFAULT '0',
  `view_count_four` int(10) unsigned NOT NULL DEFAULT '0',
  `allow_comments` varchar(1) NOT NULL DEFAULT 'y',
  `sticky` varchar(1) NOT NULL DEFAULT 'n',
  `entry_date` int(10) NOT NULL,
  `year` char(4) NOT NULL,
  `month` char(2) NOT NULL,
  `day` char(3) NOT NULL,
  `expiration_date` int(10) NOT NULL DEFAULT '0',
  `comment_expiration_date` int(10) NOT NULL DEFAULT '0',
  `edit_date` bigint(14) DEFAULT NULL,
  `recent_comment_date` int(10) DEFAULT NULL,
  `comment_total` int(4) unsigned NOT NULL DEFAULT '0',
  `entry_data` text,
  PRIMARY KEY (`entry_id`),
  KEY `channel_id` (`channel_id`),
  KEY `author_id` (`author_id`),
  KEY `url_title` (`url_title`),
  KEY `status` (`status`),
  KEY `entry_date` (`entry_date`),
  KEY `expiration_date` (`expiration_date`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_channel_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_channel_fields`;

CREATE TABLE `exp_channel_fields` (
  `field_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_id` int(4) unsigned NOT NULL,
  `field_name` varchar(32) NOT NULL,
  `field_label` varchar(50) NOT NULL,
  `field_instructions` text,
  `field_type` varchar(50) NOT NULL DEFAULT 'text',
  `field_list_items` text NOT NULL,
  `field_pre_populate` char(1) NOT NULL DEFAULT 'n',
  `field_pre_channel_id` int(6) unsigned DEFAULT NULL,
  `field_pre_field_id` int(6) unsigned DEFAULT NULL,
  `field_ta_rows` tinyint(2) DEFAULT '8',
  `field_maxl` smallint(3) DEFAULT NULL,
  `field_required` char(1) NOT NULL DEFAULT 'n',
  `field_text_direction` char(3) NOT NULL DEFAULT 'ltr',
  `field_search` char(1) NOT NULL DEFAULT 'n',
  `field_is_hidden` char(1) NOT NULL DEFAULT 'n',
  `field_fmt` varchar(40) NOT NULL DEFAULT 'xhtml',
  `field_show_fmt` char(1) NOT NULL DEFAULT 'y',
  `field_order` int(3) unsigned NOT NULL,
  `field_content_type` varchar(20) NOT NULL DEFAULT 'any',
  `field_settings` text,
  PRIMARY KEY (`field_id`),
  KEY `group_id` (`group_id`),
  KEY `field_type` (`field_type`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_channel_form_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_channel_form_settings`;

CREATE TABLE `exp_channel_form_settings` (
  `channel_form_settings_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '0',
  `channel_id` int(6) unsigned NOT NULL DEFAULT '0',
  `default_status` varchar(50) NOT NULL DEFAULT 'open',
  `require_captcha` char(1) NOT NULL DEFAULT 'n',
  `allow_guest_posts` char(1) NOT NULL DEFAULT 'n',
  `default_author` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`channel_form_settings_id`),
  KEY `site_id` (`site_id`),
  KEY `channel_id` (`channel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table exp_channel_member_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_channel_member_groups`;

CREATE TABLE `exp_channel_member_groups` (
  `group_id` smallint(4) unsigned NOT NULL,
  `channel_id` int(6) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_channel_titles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_channel_titles`;

CREATE TABLE `exp_channel_titles` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `channel_id` int(4) unsigned NOT NULL,
  `author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `forum_topic_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `title` varchar(100) NOT NULL,
  `url_title` varchar(75) NOT NULL,
  `status` varchar(50) NOT NULL,
  `versioning_enabled` char(1) NOT NULL DEFAULT 'n',
  `view_count_one` int(10) unsigned NOT NULL DEFAULT '0',
  `view_count_two` int(10) unsigned NOT NULL DEFAULT '0',
  `view_count_three` int(10) unsigned NOT NULL DEFAULT '0',
  `view_count_four` int(10) unsigned NOT NULL DEFAULT '0',
  `allow_comments` varchar(1) NOT NULL DEFAULT 'y',
  `sticky` varchar(1) NOT NULL DEFAULT 'n',
  `entry_date` int(10) NOT NULL,
  `year` char(4) NOT NULL,
  `month` char(2) NOT NULL,
  `day` char(3) NOT NULL,
  `expiration_date` int(10) NOT NULL DEFAULT '0',
  `comment_expiration_date` int(10) NOT NULL DEFAULT '0',
  `edit_date` bigint(14) DEFAULT NULL,
  `recent_comment_date` int(10) DEFAULT NULL,
  `comment_total` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`entry_id`),
  KEY `channel_id` (`channel_id`),
  KEY `author_id` (`author_id`),
  KEY `url_title` (`url_title`),
  KEY `status` (`status`),
  KEY `entry_date` (`entry_date`),
  KEY `expiration_date` (`expiration_date`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_channels
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_channels`;

CREATE TABLE `exp_channels` (
  `channel_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `channel_name` varchar(40) NOT NULL,
  `channel_title` varchar(100) NOT NULL,
  `channel_url` varchar(100) NOT NULL,
  `channel_description` varchar(255) DEFAULT NULL,
  `channel_lang` varchar(12) NOT NULL,
  `total_entries` mediumint(8) NOT NULL DEFAULT '0',
  `total_comments` mediumint(8) NOT NULL DEFAULT '0',
  `last_entry_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_comment_date` int(10) unsigned NOT NULL DEFAULT '0',
  `cat_group` varchar(255) DEFAULT NULL,
  `status_group` int(4) unsigned DEFAULT NULL,
  `deft_status` varchar(50) NOT NULL DEFAULT 'open',
  `field_group` int(4) unsigned DEFAULT NULL,
  `search_excerpt` int(4) unsigned DEFAULT NULL,
  `deft_category` varchar(60) DEFAULT NULL,
  `deft_comments` char(1) NOT NULL DEFAULT 'y',
  `channel_require_membership` char(1) NOT NULL DEFAULT 'y',
  `channel_max_chars` int(5) unsigned DEFAULT NULL,
  `channel_html_formatting` char(4) NOT NULL DEFAULT 'all',
  `channel_allow_img_urls` char(1) NOT NULL DEFAULT 'y',
  `channel_auto_link_urls` char(1) NOT NULL DEFAULT 'n',
  `channel_notify` char(1) NOT NULL DEFAULT 'n',
  `channel_notify_emails` varchar(255) DEFAULT NULL,
  `comment_url` varchar(80) DEFAULT NULL,
  `comment_system_enabled` char(1) NOT NULL DEFAULT 'y',
  `comment_require_membership` char(1) NOT NULL DEFAULT 'n',
  `comment_use_captcha` char(1) NOT NULL DEFAULT 'n',
  `comment_moderate` char(1) NOT NULL DEFAULT 'n',
  `comment_max_chars` int(5) unsigned DEFAULT '5000',
  `comment_timelock` int(5) unsigned NOT NULL DEFAULT '0',
  `comment_require_email` char(1) NOT NULL DEFAULT 'y',
  `comment_text_formatting` char(5) NOT NULL DEFAULT 'xhtml',
  `comment_html_formatting` char(4) NOT NULL DEFAULT 'safe',
  `comment_allow_img_urls` char(1) NOT NULL DEFAULT 'n',
  `comment_auto_link_urls` char(1) NOT NULL DEFAULT 'y',
  `comment_notify` char(1) NOT NULL DEFAULT 'n',
  `comment_notify_authors` char(1) NOT NULL DEFAULT 'n',
  `comment_notify_emails` varchar(255) DEFAULT NULL,
  `comment_expiration` int(4) unsigned NOT NULL DEFAULT '0',
  `search_results_url` varchar(80) DEFAULT NULL,
  `show_button_cluster` char(1) NOT NULL DEFAULT 'y',
  `rss_url` varchar(80) DEFAULT NULL,
  `enable_versioning` char(1) NOT NULL DEFAULT 'n',
  `max_revisions` smallint(4) unsigned NOT NULL DEFAULT '10',
  `default_entry_title` varchar(100) DEFAULT NULL,
  `url_title_prefix` varchar(80) DEFAULT NULL,
  `live_look_template` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`channel_id`),
  KEY `cat_group` (`cat_group`),
  KEY `status_group` (`status_group`),
  KEY `field_group` (`field_group`),
  KEY `channel_name` (`channel_name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_content_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_content_types`;

CREATE TABLE `exp_content_types` (
  `content_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`content_type_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `exp_content_types` WRITE;
/*!40000 ALTER TABLE `exp_content_types` DISABLE KEYS */;

INSERT INTO `exp_content_types` (`content_type_id`, `name`)
VALUES
	(1,'channel'),
	(2,'grid');

/*!40000 ALTER TABLE `exp_content_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_cp_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_cp_log`;

CREATE TABLE `exp_cp_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `member_id` int(10) unsigned NOT NULL,
  `username` varchar(32) NOT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `act_date` int(10) NOT NULL,
  `action` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_cp_log` WRITE;
/*!40000 ALTER TABLE `exp_cp_log` DISABLE KEYS */;

INSERT INTO `exp_cp_log` (`id`, `site_id`, `member_id`, `username`, `ip_address`, `act_date`, `action`)
VALUES
	(1,1,1,'confluxgroup','127.0.0.1',1338421593,'Logged in'),
	(2,1,1,'confluxgroup','127.0.0.1',1338499078,'Logged in'),
	(3,1,1,'confluxgroup','127.0.0.1',1339165814,'Logged in'),
	(4,1,1,'confluxgroup','127.0.0.1',1339165844,'Logged out'),
	(5,1,1,'confluxgroup','127.0.0.1',1339166177,'Logged in'),
	(6,1,1,'confluxgroup','127.0.0.1',1340667396,'Logged in'),
	(7,1,1,'confluxgroup','127.0.0.1',1340667982,'Logged out'),
	(8,1,1,'confluxgroup','127.0.0.1',1343587094,'Logged in'),
	(9,1,1,'confluxgroup','127.0.0.1',1345684368,'Logged in'),
	(10,1,1,'confluxgroup','127.0.0.1',1345684471,'Logged out'),
	(11,1,1,'confluxgroup','127.0.0.1',1345937280,'Logged in'),
	(12,1,1,'confluxgroup','127.0.0.1',1353262955,'Logged in'),
	(13,1,1,'confluxgroup','127.0.0.1',1373052265,'Logged in'),
	(14,1,1,'confluxgroup','127.0.0.1',1373053325,'Logged in'),
	(15,1,1,'confluxgroup','127.0.0.1',1379722807,'Logged in'),
	(16,1,1,'confluxgroup','127.0.0.1',1379783385,'Logged in'),
	(17,1,1,'confluxgroup','127.0.0.1',1383704732,'Logged in');

/*!40000 ALTER TABLE `exp_cp_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_cp_search_index
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_cp_search_index`;

CREATE TABLE `exp_cp_search_index` (
  `search_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `controller` varchar(20) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `access` varchar(50) DEFAULT NULL,
  `keywords` text,
  PRIMARY KEY (`search_id`),
  FULLTEXT KEY `keywords` (`keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table exp_developer_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_developer_log`;

CREATE TABLE `exp_developer_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL,
  `viewed` char(1) NOT NULL DEFAULT 'n',
  `description` text,
  `function` varchar(100) DEFAULT NULL,
  `line` int(10) unsigned DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `deprecated_since` varchar(10) DEFAULT NULL,
  `use_instead` varchar(100) DEFAULT NULL,
  `template_id` int(10) unsigned NOT NULL DEFAULT '0',
  `template_name` varchar(100) DEFAULT NULL,
  `template_group` varchar(100) DEFAULT NULL,
  `addon_module` varchar(100) DEFAULT NULL,
  `addon_method` varchar(100) DEFAULT NULL,
  `snippets` text,
  `hash` char(32) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_devotee_hidden_addons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_devotee_hidden_addons`;

CREATE TABLE `exp_devotee_hidden_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) unsigned NOT NULL,
  `package` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_email_cache
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_email_cache`;

CREATE TABLE `exp_email_cache` (
  `cache_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `cache_date` int(10) unsigned NOT NULL DEFAULT '0',
  `total_sent` int(6) unsigned NOT NULL,
  `from_name` varchar(70) NOT NULL,
  `from_email` varchar(70) NOT NULL,
  `recipient` text NOT NULL,
  `cc` text NOT NULL,
  `bcc` text NOT NULL,
  `recipient_array` mediumtext NOT NULL,
  `subject` varchar(120) NOT NULL,
  `message` mediumtext NOT NULL,
  `plaintext_alt` mediumtext NOT NULL,
  `mailinglist` char(1) NOT NULL DEFAULT 'n',
  `mailtype` varchar(6) NOT NULL,
  `text_fmt` varchar(40) NOT NULL,
  `wordwrap` char(1) NOT NULL DEFAULT 'y',
  `priority` char(1) NOT NULL DEFAULT '3',
  PRIMARY KEY (`cache_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_email_cache_mg
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_email_cache_mg`;

CREATE TABLE `exp_email_cache_mg` (
  `cache_id` int(6) unsigned NOT NULL,
  `group_id` smallint(4) NOT NULL,
  PRIMARY KEY (`cache_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_email_cache_ml
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_email_cache_ml`;

CREATE TABLE `exp_email_cache_ml` (
  `cache_id` int(6) unsigned NOT NULL,
  `list_id` smallint(4) NOT NULL,
  PRIMARY KEY (`cache_id`,`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_email_console_cache
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_email_console_cache`;

CREATE TABLE `exp_email_console_cache` (
  `cache_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `cache_date` int(10) unsigned NOT NULL DEFAULT '0',
  `member_id` int(10) unsigned NOT NULL,
  `member_name` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `recipient` varchar(70) NOT NULL,
  `recipient_name` varchar(50) NOT NULL,
  `subject` varchar(120) NOT NULL,
  `message` mediumtext NOT NULL,
  PRIMARY KEY (`cache_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_entry_versioning
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_entry_versioning`;

CREATE TABLE `exp_entry_versioning` (
  `version_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(10) unsigned NOT NULL,
  `channel_id` int(4) unsigned NOT NULL,
  `author_id` int(10) unsigned NOT NULL,
  `version_date` int(10) NOT NULL,
  `version_data` mediumtext NOT NULL,
  PRIMARY KEY (`version_id`),
  KEY `entry_id` (`entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_extensions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_extensions`;

CREATE TABLE `exp_extensions` (
  `extension_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(50) NOT NULL DEFAULT '',
  `method` varchar(50) NOT NULL DEFAULT '',
  `hook` varchar(50) NOT NULL DEFAULT '',
  `settings` text NOT NULL,
  `priority` int(2) NOT NULL DEFAULT '10',
  `version` varchar(10) NOT NULL DEFAULT '',
  `enabled` char(1) NOT NULL DEFAULT 'y',
  PRIMARY KEY (`extension_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_extensions` WRITE;
/*!40000 ALTER TABLE `exp_extensions` DISABLE KEYS */;

INSERT INTO `exp_extensions` (`extension_id`, `class`, `method`, `hook`, `settings`, `priority`, `version`, `enabled`)
VALUES
	(2,'Rte_ext','myaccount_nav_setup','myaccount_nav_setup','',10,'1.0','y'),
	(3,'Rte_ext','cp_menu_array','cp_menu_array','',10,'1.0','y'),
	(5,'Snippet_sync_ext','sessions_end','sessions_end','a:7:{s:6:\"hashes\";a:0:{}s:6:\"prefix\";s:8:\"snippet:\";s:16:\"subfolder_suffix\";s:1:\"_\";s:19:\"suffix_as_separator\";s:1:\"n\";s:17:\"msm_shared_folder\";s:6:\"shared\";s:9:\"hide_menu\";s:1:\"n\";s:14:\"enable_cleanup\";s:1:\"y\";}',2,'1.3.5','y'),
	(6,'Snippet_sync_ext','show_full_control_panel_end','show_full_control_panel_end','a:7:{s:6:\"hashes\";a:0:{}s:6:\"prefix\";s:8:\"snippet:\";s:16:\"subfolder_suffix\";s:1:\"_\";s:19:\"suffix_as_separator\";s:1:\"n\";s:17:\"msm_shared_folder\";s:6:\"shared\";s:9:\"hide_menu\";s:1:\"n\";s:14:\"enable_cleanup\";s:1:\"y\";}',2,'1.3.5','y'),
	(7,'Updater_ext','cp_menu_array','cp_menu_array','a:0:{}',100,'3.2.8','y');

/*!40000 ALTER TABLE `exp_extensions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_field_formatting
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_field_formatting`;

CREATE TABLE `exp_field_formatting` (
  `formatting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field_id` int(10) unsigned NOT NULL,
  `field_fmt` varchar(40) NOT NULL,
  PRIMARY KEY (`formatting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_field_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_field_groups`;

CREATE TABLE `exp_field_groups` (
  `group_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_name` varchar(50) NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_fieldtypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_fieldtypes`;

CREATE TABLE `exp_fieldtypes` (
  `fieldtype_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `version` varchar(12) NOT NULL,
  `settings` text,
  `has_global_settings` char(1) DEFAULT 'n',
  PRIMARY KEY (`fieldtype_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_fieldtypes` WRITE;
/*!40000 ALTER TABLE `exp_fieldtypes` DISABLE KEYS */;

INSERT INTO `exp_fieldtypes` (`fieldtype_id`, `name`, `version`, `settings`, `has_global_settings`)
VALUES
	(1,'select','1.0','YTowOnt9','n'),
	(2,'text','1.0','YTowOnt9','n'),
	(3,'textarea','1.0','YTowOnt9','n'),
	(4,'date','1.0','YTowOnt9','n'),
	(5,'file','1.0','YTowOnt9','n'),
	(6,'multi_select','1.0','YTowOnt9','n'),
	(7,'checkboxes','1.0','YTowOnt9','n'),
	(8,'radio','1.0','YTowOnt9','n'),
	(9,'relationship','1.0','YTowOnt9','n'),
	(10,'rte','1.0','YTowOnt9','n'),
	(11,'grid','1.0','YTowOnt9','n');

/*!40000 ALTER TABLE `exp_fieldtypes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_file_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_file_categories`;

CREATE TABLE `exp_file_categories` (
  `file_id` int(10) unsigned DEFAULT NULL,
  `cat_id` int(10) unsigned DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT '0',
  `is_cover` char(1) DEFAULT 'n',
  KEY `file_id` (`file_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_file_dimensions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_file_dimensions`;

CREATE TABLE `exp_file_dimensions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned DEFAULT '1',
  `upload_location_id` int(4) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `short_name` varchar(255) DEFAULT '',
  `resize_type` varchar(50) DEFAULT '',
  `width` int(10) DEFAULT '0',
  `height` int(10) DEFAULT '0',
  `watermark_id` int(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `upload_location_id` (`upload_location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_file_watermarks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_file_watermarks`;

CREATE TABLE `exp_file_watermarks` (
  `wm_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `wm_name` varchar(80) DEFAULT NULL,
  `wm_type` varchar(10) DEFAULT 'text',
  `wm_image_path` varchar(100) DEFAULT NULL,
  `wm_test_image_path` varchar(100) DEFAULT NULL,
  `wm_use_font` char(1) DEFAULT 'y',
  `wm_font` varchar(30) DEFAULT NULL,
  `wm_font_size` int(3) unsigned DEFAULT NULL,
  `wm_text` varchar(100) DEFAULT NULL,
  `wm_vrt_alignment` varchar(10) DEFAULT 'top',
  `wm_hor_alignment` varchar(10) DEFAULT 'left',
  `wm_padding` int(3) unsigned DEFAULT NULL,
  `wm_opacity` int(3) unsigned DEFAULT NULL,
  `wm_hor_offset` int(4) unsigned DEFAULT NULL,
  `wm_vrt_offset` int(4) unsigned DEFAULT NULL,
  `wm_x_transp` int(4) DEFAULT NULL,
  `wm_y_transp` int(4) DEFAULT NULL,
  `wm_font_color` varchar(7) DEFAULT NULL,
  `wm_use_drop_shadow` char(1) DEFAULT 'y',
  `wm_shadow_distance` int(3) unsigned DEFAULT NULL,
  `wm_shadow_color` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`wm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_files`;

CREATE TABLE `exp_files` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned DEFAULT '1',
  `title` varchar(255) DEFAULT NULL,
  `upload_location_id` int(4) unsigned DEFAULT '0',
  `rel_path` varchar(255) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` int(10) DEFAULT '0',
  `description` text,
  `credit` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `uploaded_by_member_id` int(10) unsigned DEFAULT '0',
  `upload_date` int(10) DEFAULT NULL,
  `modified_by_member_id` int(10) unsigned DEFAULT '0',
  `modified_date` int(10) DEFAULT NULL,
  `file_hw_original` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`file_id`),
  KEY `upload_location_id` (`upload_location_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_freeform_composer_layouts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_composer_layouts`;

CREATE TABLE `exp_freeform_composer_layouts` (
  `composer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `composer_data` text,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  `preview` char(1) NOT NULL DEFAULT 'n',
  `entry_date` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_date` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`composer_id`),
  KEY `preview` (`preview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_freeform_composer_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_composer_templates`;

CREATE TABLE `exp_freeform_composer_templates` (
  `template_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  `template_name` varchar(150) NOT NULL DEFAULT 'default',
  `template_label` varchar(150) NOT NULL DEFAULT 'default',
  `template_description` text,
  `enable_template` char(1) NOT NULL DEFAULT 'y',
  `template_data` text,
  `param_data` text,
  PRIMARY KEY (`template_id`),
  KEY `template_name` (`template_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_freeform_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_fields`;

CREATE TABLE `exp_freeform_fields` (
  `field_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  `field_name` varchar(150) NOT NULL DEFAULT 'default',
  `field_label` varchar(150) NOT NULL DEFAULT 'default',
  `field_type` varchar(50) NOT NULL DEFAULT 'text',
  `settings` text,
  `author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_date` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_date` int(10) unsigned NOT NULL DEFAULT '0',
  `required` char(1) NOT NULL DEFAULT 'n',
  `submissions_page` char(1) NOT NULL DEFAULT 'y',
  `moderation_page` char(1) NOT NULL DEFAULT 'y',
  `composer_use` char(1) NOT NULL DEFAULT 'y',
  `field_description` text,
  PRIMARY KEY (`field_id`),
  KEY `field_name` (`field_name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_freeform_fields` WRITE;
/*!40000 ALTER TABLE `exp_freeform_fields` DISABLE KEYS */;

INSERT INTO `exp_freeform_fields` (`field_id`, `site_id`, `field_name`, `field_label`, `field_type`, `settings`, `author_id`, `entry_date`, `edit_date`, `required`, `submissions_page`, `moderation_page`, `composer_use`, `field_description`)
VALUES
	(1,1,'first_name','First Name','text','{\"field_length\":150,\"field_content_type\":\"any\"}',1,1379784223,0,'n','y','y','y','This field contains the user\'s first name.'),
	(2,1,'last_name','Last Name','text','{\"field_length\":150,\"field_content_type\":\"any\"}',1,1379784223,0,'n','y','y','y','This field contains the user\'s last name.'),
	(3,1,'email','Email','text','{\"field_length\":150,\"field_content_type\":\"email\"}',1,1379784223,0,'n','y','y','y','A basic email field for collecting stuff like an email address.'),
	(4,1,'user_message','Message','textarea','{\"field_ta_rows\":6}',1,1379784223,0,'n','y','y','y','This field contains the user\'s message.');

/*!40000 ALTER TABLE `exp_freeform_fields` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_freeform_fieldtypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_fieldtypes`;

CREATE TABLE `exp_freeform_fieldtypes` (
  `fieldtype_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fieldtype_name` varchar(250) DEFAULT NULL,
  `settings` text,
  `default_field` char(1) NOT NULL DEFAULT 'n',
  `version` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`fieldtype_id`),
  KEY `fieldtype_name` (`fieldtype_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_freeform_fieldtypes` WRITE;
/*!40000 ALTER TABLE `exp_freeform_fieldtypes` DISABLE KEYS */;

INSERT INTO `exp_freeform_fieldtypes` (`fieldtype_id`, `fieldtype_name`, `settings`, `default_field`, `version`)
VALUES
	(1,'file_upload','[]','n','4.1.2'),
	(2,'mailinglist','[]','n','4.1.2'),
	(3,'text','[]','n','4.1.2'),
	(4,'textarea','[]','n','4.1.2');

/*!40000 ALTER TABLE `exp_freeform_fieldtypes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_freeform_file_uploads
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_file_uploads`;

CREATE TABLE `exp_freeform_file_uploads` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  `form_id` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_id` int(10) unsigned NOT NULL DEFAULT '0',
  `field_id` int(10) unsigned NOT NULL DEFAULT '0',
  `server_path` varchar(750) DEFAULT NULL,
  `filename` varchar(250) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `filesize` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`file_id`),
  KEY `entry_id` (`entry_id`),
  KEY `field_id` (`field_id`),
  KEY `extension` (`extension`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_freeform_form_entries_1
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_form_entries_1`;

CREATE TABLE `exp_freeform_form_entries_1` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  `author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `complete` varchar(1) NOT NULL DEFAULT 'y',
  `ip_address` varchar(40) NOT NULL DEFAULT '0',
  `entry_date` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_date` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(50) DEFAULT NULL,
  `form_field_1` text,
  `form_field_2` text,
  `form_field_3` text,
  `form_field_4` text,
  PRIMARY KEY (`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `exp_freeform_form_entries_1` WRITE;
/*!40000 ALTER TABLE `exp_freeform_form_entries_1` DISABLE KEYS */;

INSERT INTO `exp_freeform_form_entries_1` (`entry_id`, `site_id`, `author_id`, `complete`, `ip_address`, `entry_date`, `edit_date`, `status`, `form_field_1`, `form_field_2`, `form_field_3`, `form_field_4`)
VALUES
	(1,1,0,'y','127.0.0.1',1379784223,0,'pending','Jake','Solspace','support@solspace.com','Welcome to Freeform. We hope that you will enjoy Solspace software.');

/*!40000 ALTER TABLE `exp_freeform_form_entries_1` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_freeform_forms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_forms`;

CREATE TABLE `exp_freeform_forms` (
  `form_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  `form_name` varchar(150) NOT NULL DEFAULT 'default',
  `form_label` varchar(150) NOT NULL DEFAULT 'default',
  `default_status` varchar(150) NOT NULL DEFAULT 'default',
  `notify_user` char(1) NOT NULL DEFAULT 'n',
  `notify_admin` char(1) NOT NULL DEFAULT 'n',
  `user_email_field` varchar(150) NOT NULL DEFAULT '',
  `user_notification_id` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_notification_id` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_notification_email` text,
  `form_description` text,
  `field_ids` text,
  `field_order` text,
  `template_id` int(10) unsigned NOT NULL DEFAULT '0',
  `composer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_date` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_date` int(10) unsigned NOT NULL DEFAULT '0',
  `settings` text,
  PRIMARY KEY (`form_id`),
  KEY `form_name` (`form_name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_freeform_forms` WRITE;
/*!40000 ALTER TABLE `exp_freeform_forms` DISABLE KEYS */;

INSERT INTO `exp_freeform_forms` (`form_id`, `site_id`, `form_name`, `form_label`, `default_status`, `notify_user`, `notify_admin`, `user_email_field`, `user_notification_id`, `admin_notification_id`, `admin_notification_email`, `form_description`, `field_ids`, `field_order`, `template_id`, `composer_id`, `author_id`, `entry_date`, `edit_date`, `settings`)
VALUES
	(1,1,'contact','Contact','pending','n','y','',0,1,'projects@confluxgroup.com','This is a basic contact form.','1|2|3|4',NULL,0,0,1,1379784223,0,NULL);

/*!40000 ALTER TABLE `exp_freeform_forms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_freeform_multipage_hashes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_multipage_hashes`;

CREATE TABLE `exp_freeform_multipage_hashes` (
  `hash_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  `form_id` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_id` int(10) unsigned NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL DEFAULT '',
  `ip_address` varchar(40) NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `edit` char(1) NOT NULL DEFAULT 'n',
  `data` text,
  PRIMARY KEY (`hash_id`),
  KEY `hash` (`hash`),
  KEY `ip_address` (`ip_address`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_freeform_notification_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_notification_templates`;

CREATE TABLE `exp_freeform_notification_templates` (
  `notification_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  `notification_name` varchar(150) NOT NULL DEFAULT 'default',
  `notification_label` varchar(150) NOT NULL DEFAULT 'default',
  `notification_description` text,
  `wordwrap` char(1) NOT NULL DEFAULT 'y',
  `allow_html` char(1) NOT NULL DEFAULT 'n',
  `from_name` varchar(150) NOT NULL DEFAULT '',
  `from_email` varchar(250) NOT NULL DEFAULT '',
  `reply_to_email` varchar(250) NOT NULL DEFAULT '',
  `email_subject` varchar(128) NOT NULL DEFAULT 'default',
  `include_attachments` char(1) NOT NULL DEFAULT 'n',
  `template_data` text,
  PRIMARY KEY (`notification_id`),
  KEY `notification_name` (`notification_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_freeform_params
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_params`;

CREATE TABLE `exp_freeform_params` (
  `params_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_date` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text,
  PRIMARY KEY (`params_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_freeform_preferences
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_preferences`;

CREATE TABLE `exp_freeform_preferences` (
  `preference_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `preference_name` varchar(80) DEFAULT NULL,
  `preference_value` text,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`preference_id`),
  KEY `preference_name` (`preference_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_freeform_preferences` WRITE;
/*!40000 ALTER TABLE `exp_freeform_preferences` DISABLE KEYS */;

INSERT INTO `exp_freeform_preferences` (`preference_id`, `preference_name`, `preference_value`, `site_id`)
VALUES
	(1,'ffp','n',0);

/*!40000 ALTER TABLE `exp_freeform_preferences` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_freeform_user_email
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_freeform_user_email`;

CREATE TABLE `exp_freeform_user_email` (
  `email_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL DEFAULT '1',
  `author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(40) NOT NULL DEFAULT '0',
  `entry_date` int(10) unsigned NOT NULL DEFAULT '0',
  `form_id` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email_count` int(10) unsigned NOT NULL DEFAULT '0',
  `email_addresses` text,
  PRIMARY KEY (`email_id`),
  KEY `ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_global_variables
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_global_variables`;

CREATE TABLE `exp_global_variables` (
  `variable_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `variable_name` varchar(50) NOT NULL,
  `variable_data` text NOT NULL,
  PRIMARY KEY (`variable_id`),
  KEY `variable_name` (`variable_name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_grid_columns
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_grid_columns`;

CREATE TABLE `exp_grid_columns` (
  `col_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field_id` int(10) unsigned DEFAULT NULL,
  `content_type` varchar(50) DEFAULT NULL,
  `col_order` int(3) unsigned DEFAULT NULL,
  `col_type` varchar(50) DEFAULT NULL,
  `col_label` varchar(50) DEFAULT NULL,
  `col_name` varchar(32) DEFAULT NULL,
  `col_instructions` text,
  `col_required` char(1) DEFAULT NULL,
  `col_search` char(1) DEFAULT NULL,
  `col_width` int(3) unsigned DEFAULT NULL,
  `col_settings` text,
  PRIMARY KEY (`col_id`),
  KEY `field_id` (`field_id`),
  KEY `content_type` (`content_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table exp_html_buttons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_html_buttons`;

CREATE TABLE `exp_html_buttons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `member_id` int(10) NOT NULL DEFAULT '0',
  `tag_name` varchar(32) NOT NULL,
  `tag_open` varchar(120) NOT NULL,
  `tag_close` varchar(120) NOT NULL,
  `accesskey` varchar(32) NOT NULL,
  `tag_order` int(3) unsigned NOT NULL,
  `tag_row` char(1) NOT NULL DEFAULT '1',
  `classname` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_html_buttons` WRITE;
/*!40000 ALTER TABLE `exp_html_buttons` DISABLE KEYS */;

INSERT INTO `exp_html_buttons` (`id`, `site_id`, `member_id`, `tag_name`, `tag_open`, `tag_close`, `accesskey`, `tag_order`, `tag_row`, `classname`)
VALUES
	(1,1,0,'b','<strong>','</strong>','b',1,'1','btn_b'),
	(2,1,0,'i','<em>','</em>','i',2,'1','btn_i'),
	(3,1,0,'blockquote','<blockquote>','</blockquote>','q',3,'1','btn_blockquote'),
	(4,1,0,'a','<a href=\"[![Link:!:http://]!]\"(!( title=\"[![Title]!]\")!)>','</a>','a',4,'1','btn_a'),
	(5,1,0,'img','<img src=\"[![Link:!:http://]!]\" alt=\"[![Alternative text]!]\" />','','',5,'1','btn_img');

/*!40000 ALTER TABLE `exp_html_buttons` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_layout_publish
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_layout_publish`;

CREATE TABLE `exp_layout_publish` (
  `layout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `member_group` int(4) unsigned NOT NULL DEFAULT '0',
  `channel_id` int(4) unsigned NOT NULL DEFAULT '0',
  `field_layout` text,
  PRIMARY KEY (`layout_id`),
  KEY `site_id` (`site_id`),
  KEY `member_group` (`member_group`),
  KEY `channel_id` (`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_member_bulletin_board
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_member_bulletin_board`;

CREATE TABLE `exp_member_bulletin_board` (
  `bulletin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL,
  `bulletin_group` int(8) unsigned NOT NULL,
  `bulletin_date` int(10) unsigned NOT NULL,
  `hash` varchar(10) NOT NULL DEFAULT '',
  `bulletin_expires` int(10) unsigned NOT NULL DEFAULT '0',
  `bulletin_message` text NOT NULL,
  PRIMARY KEY (`bulletin_id`),
  KEY `sender_id` (`sender_id`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_member_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_member_data`;

CREATE TABLE `exp_member_data` (
  `member_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_member_data` WRITE;
/*!40000 ALTER TABLE `exp_member_data` DISABLE KEYS */;

INSERT INTO `exp_member_data` (`member_id`)
VALUES
	(1);

/*!40000 ALTER TABLE `exp_member_data` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_member_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_member_fields`;

CREATE TABLE `exp_member_fields` (
  `m_field_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `m_field_name` varchar(32) NOT NULL,
  `m_field_label` varchar(50) NOT NULL,
  `m_field_description` text NOT NULL,
  `m_field_type` varchar(12) NOT NULL DEFAULT 'text',
  `m_field_list_items` text NOT NULL,
  `m_field_ta_rows` tinyint(2) DEFAULT '8',
  `m_field_maxl` smallint(3) NOT NULL,
  `m_field_width` varchar(6) NOT NULL,
  `m_field_search` char(1) NOT NULL DEFAULT 'y',
  `m_field_required` char(1) NOT NULL DEFAULT 'n',
  `m_field_public` char(1) NOT NULL DEFAULT 'y',
  `m_field_reg` char(1) NOT NULL DEFAULT 'n',
  `m_field_cp_reg` char(1) NOT NULL DEFAULT 'n',
  `m_field_fmt` char(5) NOT NULL DEFAULT 'none',
  `m_field_order` int(3) unsigned NOT NULL,
  PRIMARY KEY (`m_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_member_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_member_groups`;

CREATE TABLE `exp_member_groups` (
  `group_id` smallint(4) unsigned NOT NULL,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_title` varchar(100) NOT NULL,
  `group_description` text NOT NULL,
  `is_locked` char(1) NOT NULL DEFAULT 'y',
  `can_view_offline_system` char(1) NOT NULL DEFAULT 'n',
  `can_view_online_system` char(1) NOT NULL DEFAULT 'y',
  `can_access_cp` char(1) NOT NULL DEFAULT 'y',
  `can_access_content` char(1) NOT NULL DEFAULT 'n',
  `can_access_publish` char(1) NOT NULL DEFAULT 'n',
  `can_access_edit` char(1) NOT NULL DEFAULT 'n',
  `can_access_files` char(1) NOT NULL DEFAULT 'n',
  `can_access_fieldtypes` char(1) NOT NULL DEFAULT 'n',
  `can_access_design` char(1) NOT NULL DEFAULT 'n',
  `can_access_addons` char(1) NOT NULL DEFAULT 'n',
  `can_access_modules` char(1) NOT NULL DEFAULT 'n',
  `can_access_extensions` char(1) NOT NULL DEFAULT 'n',
  `can_access_accessories` char(1) NOT NULL DEFAULT 'n',
  `can_access_plugins` char(1) NOT NULL DEFAULT 'n',
  `can_access_members` char(1) NOT NULL DEFAULT 'n',
  `can_access_admin` char(1) NOT NULL DEFAULT 'n',
  `can_access_sys_prefs` char(1) NOT NULL DEFAULT 'n',
  `can_access_content_prefs` char(1) NOT NULL DEFAULT 'n',
  `can_access_tools` char(1) NOT NULL DEFAULT 'n',
  `can_access_comm` char(1) NOT NULL DEFAULT 'n',
  `can_access_utilities` char(1) NOT NULL DEFAULT 'n',
  `can_access_data` char(1) NOT NULL DEFAULT 'n',
  `can_access_logs` char(1) NOT NULL DEFAULT 'n',
  `can_admin_channels` char(1) NOT NULL DEFAULT 'n',
  `can_admin_upload_prefs` char(1) NOT NULL DEFAULT 'n',
  `can_admin_design` char(1) NOT NULL DEFAULT 'n',
  `can_admin_members` char(1) NOT NULL DEFAULT 'n',
  `can_delete_members` char(1) NOT NULL DEFAULT 'n',
  `can_admin_mbr_groups` char(1) NOT NULL DEFAULT 'n',
  `can_admin_mbr_templates` char(1) NOT NULL DEFAULT 'n',
  `can_ban_users` char(1) NOT NULL DEFAULT 'n',
  `can_admin_modules` char(1) NOT NULL DEFAULT 'n',
  `can_admin_templates` char(1) NOT NULL DEFAULT 'n',
  `can_admin_accessories` char(1) NOT NULL DEFAULT 'n',
  `can_edit_categories` char(1) NOT NULL DEFAULT 'n',
  `can_delete_categories` char(1) NOT NULL DEFAULT 'n',
  `can_view_other_entries` char(1) NOT NULL DEFAULT 'n',
  `can_edit_other_entries` char(1) NOT NULL DEFAULT 'n',
  `can_assign_post_authors` char(1) NOT NULL DEFAULT 'n',
  `can_delete_self_entries` char(1) NOT NULL DEFAULT 'n',
  `can_delete_all_entries` char(1) NOT NULL DEFAULT 'n',
  `can_view_other_comments` char(1) NOT NULL DEFAULT 'n',
  `can_edit_own_comments` char(1) NOT NULL DEFAULT 'n',
  `can_delete_own_comments` char(1) NOT NULL DEFAULT 'n',
  `can_edit_all_comments` char(1) NOT NULL DEFAULT 'n',
  `can_delete_all_comments` char(1) NOT NULL DEFAULT 'n',
  `can_moderate_comments` char(1) NOT NULL DEFAULT 'n',
  `can_send_email` char(1) NOT NULL DEFAULT 'n',
  `can_send_cached_email` char(1) NOT NULL DEFAULT 'n',
  `can_email_member_groups` char(1) NOT NULL DEFAULT 'n',
  `can_email_mailinglist` char(1) NOT NULL DEFAULT 'n',
  `can_email_from_profile` char(1) NOT NULL DEFAULT 'n',
  `can_view_profiles` char(1) NOT NULL DEFAULT 'n',
  `can_edit_html_buttons` char(1) NOT NULL DEFAULT 'n',
  `can_delete_self` char(1) NOT NULL DEFAULT 'n',
  `mbr_delete_notify_emails` varchar(255) DEFAULT NULL,
  `can_post_comments` char(1) NOT NULL DEFAULT 'n',
  `exclude_from_moderation` char(1) NOT NULL DEFAULT 'n',
  `can_search` char(1) NOT NULL DEFAULT 'n',
  `search_flood_control` mediumint(5) unsigned NOT NULL,
  `can_send_private_messages` char(1) NOT NULL DEFAULT 'n',
  `prv_msg_send_limit` smallint(5) unsigned NOT NULL DEFAULT '20',
  `prv_msg_storage_limit` smallint(5) unsigned NOT NULL DEFAULT '60',
  `can_attach_in_private_messages` char(1) NOT NULL DEFAULT 'n',
  `can_send_bulletins` char(1) NOT NULL DEFAULT 'n',
  `include_in_authorlist` char(1) NOT NULL DEFAULT 'n',
  `include_in_memberlist` char(1) NOT NULL DEFAULT 'y',
  `include_in_mailinglists` char(1) NOT NULL DEFAULT 'y',
  PRIMARY KEY (`group_id`,`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_member_groups` WRITE;
/*!40000 ALTER TABLE `exp_member_groups` DISABLE KEYS */;

INSERT INTO `exp_member_groups` (`group_id`, `site_id`, `group_title`, `group_description`, `is_locked`, `can_view_offline_system`, `can_view_online_system`, `can_access_cp`, `can_access_content`, `can_access_publish`, `can_access_edit`, `can_access_files`, `can_access_fieldtypes`, `can_access_design`, `can_access_addons`, `can_access_modules`, `can_access_extensions`, `can_access_accessories`, `can_access_plugins`, `can_access_members`, `can_access_admin`, `can_access_sys_prefs`, `can_access_content_prefs`, `can_access_tools`, `can_access_comm`, `can_access_utilities`, `can_access_data`, `can_access_logs`, `can_admin_channels`, `can_admin_upload_prefs`, `can_admin_design`, `can_admin_members`, `can_delete_members`, `can_admin_mbr_groups`, `can_admin_mbr_templates`, `can_ban_users`, `can_admin_modules`, `can_admin_templates`, `can_admin_accessories`, `can_edit_categories`, `can_delete_categories`, `can_view_other_entries`, `can_edit_other_entries`, `can_assign_post_authors`, `can_delete_self_entries`, `can_delete_all_entries`, `can_view_other_comments`, `can_edit_own_comments`, `can_delete_own_comments`, `can_edit_all_comments`, `can_delete_all_comments`, `can_moderate_comments`, `can_send_email`, `can_send_cached_email`, `can_email_member_groups`, `can_email_mailinglist`, `can_email_from_profile`, `can_view_profiles`, `can_edit_html_buttons`, `can_delete_self`, `mbr_delete_notify_emails`, `can_post_comments`, `exclude_from_moderation`, `can_search`, `search_flood_control`, `can_send_private_messages`, `prv_msg_send_limit`, `prv_msg_storage_limit`, `can_attach_in_private_messages`, `can_send_bulletins`, `include_in_authorlist`, `include_in_memberlist`, `include_in_mailinglists`)
VALUES
	(1,1,'Super Admins','','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','y','','y','y','y',0,'y',20,60,'y','y','y','y','y'),
	(2,1,'Banned','','y','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','','n','n','n',60,'n',20,60,'n','n','n','n','n'),
	(3,1,'Guests','','y','n','y','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','y','n','n','n','n','','y','n','y',15,'n',20,60,'n','n','n','n','n'),
	(4,1,'Pending','','y','n','y','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','y','n','n','n','n','','y','n','y',15,'n',20,60,'n','n','n','n','n'),
	(5,1,'Members','','y','n','y','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','n','y','y','y','n','','y','n','y',10,'y',20,60,'y','n','n','y','y');

/*!40000 ALTER TABLE `exp_member_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_member_homepage
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_member_homepage`;

CREATE TABLE `exp_member_homepage` (
  `member_id` int(10) unsigned NOT NULL,
  `recent_entries` char(1) NOT NULL DEFAULT 'l',
  `recent_entries_order` int(3) unsigned NOT NULL DEFAULT '0',
  `recent_comments` char(1) NOT NULL DEFAULT 'l',
  `recent_comments_order` int(3) unsigned NOT NULL DEFAULT '0',
  `recent_members` char(1) NOT NULL DEFAULT 'n',
  `recent_members_order` int(3) unsigned NOT NULL DEFAULT '0',
  `site_statistics` char(1) NOT NULL DEFAULT 'r',
  `site_statistics_order` int(3) unsigned NOT NULL DEFAULT '0',
  `member_search_form` char(1) NOT NULL DEFAULT 'n',
  `member_search_form_order` int(3) unsigned NOT NULL DEFAULT '0',
  `notepad` char(1) NOT NULL DEFAULT 'r',
  `notepad_order` int(3) unsigned NOT NULL DEFAULT '0',
  `bulletin_board` char(1) NOT NULL DEFAULT 'r',
  `bulletin_board_order` int(3) unsigned NOT NULL DEFAULT '0',
  `pmachine_news_feed` char(1) NOT NULL DEFAULT 'n',
  `pmachine_news_feed_order` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_member_homepage` WRITE;
/*!40000 ALTER TABLE `exp_member_homepage` DISABLE KEYS */;

INSERT INTO `exp_member_homepage` (`member_id`, `recent_entries`, `recent_entries_order`, `recent_comments`, `recent_comments_order`, `recent_members`, `recent_members_order`, `site_statistics`, `site_statistics_order`, `member_search_form`, `member_search_form_order`, `notepad`, `notepad_order`, `bulletin_board`, `bulletin_board_order`, `pmachine_news_feed`, `pmachine_news_feed_order`)
VALUES
	(1,'l',1,'l',2,'n',0,'r',1,'n',0,'r',2,'r',0,'l',0);

/*!40000 ALTER TABLE `exp_member_homepage` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_member_search
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_member_search`;

CREATE TABLE `exp_member_search` (
  `search_id` varchar(32) NOT NULL,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `search_date` int(10) unsigned NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `fields` varchar(200) NOT NULL,
  `member_id` int(10) unsigned NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `total_results` int(8) unsigned NOT NULL,
  `query` text NOT NULL,
  PRIMARY KEY (`search_id`),
  KEY `member_id` (`member_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_members`;

CREATE TABLE `exp_members` (
  `member_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` smallint(4) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `screen_name` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL DEFAULT '',
  `unique_id` varchar(40) NOT NULL,
  `crypt_key` varchar(40) DEFAULT NULL,
  `authcode` varchar(10) DEFAULT NULL,
  `email` varchar(72) NOT NULL,
  `url` varchar(150) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `occupation` varchar(80) DEFAULT NULL,
  `interests` varchar(120) DEFAULT NULL,
  `bday_d` int(2) DEFAULT NULL,
  `bday_m` int(2) DEFAULT NULL,
  `bday_y` int(4) DEFAULT NULL,
  `aol_im` varchar(50) DEFAULT NULL,
  `yahoo_im` varchar(50) DEFAULT NULL,
  `msn_im` varchar(50) DEFAULT NULL,
  `icq` varchar(50) DEFAULT NULL,
  `bio` text,
  `signature` text,
  `avatar_filename` varchar(120) DEFAULT NULL,
  `avatar_width` int(4) unsigned DEFAULT NULL,
  `avatar_height` int(4) unsigned DEFAULT NULL,
  `photo_filename` varchar(120) DEFAULT NULL,
  `photo_width` int(4) unsigned DEFAULT NULL,
  `photo_height` int(4) unsigned DEFAULT NULL,
  `sig_img_filename` varchar(120) DEFAULT NULL,
  `sig_img_width` int(4) unsigned DEFAULT NULL,
  `sig_img_height` int(4) unsigned DEFAULT NULL,
  `ignore_list` text,
  `private_messages` int(4) unsigned NOT NULL DEFAULT '0',
  `accept_messages` char(1) NOT NULL DEFAULT 'y',
  `last_view_bulletins` int(10) NOT NULL DEFAULT '0',
  `last_bulletin_date` int(10) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `join_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_visit` int(10) unsigned NOT NULL DEFAULT '0',
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `total_entries` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `total_comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `total_forum_topics` mediumint(8) NOT NULL DEFAULT '0',
  `total_forum_posts` mediumint(8) NOT NULL DEFAULT '0',
  `last_entry_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_comment_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_forum_post_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_email_date` int(10) unsigned NOT NULL DEFAULT '0',
  `in_authorlist` char(1) NOT NULL DEFAULT 'n',
  `accept_admin_email` char(1) NOT NULL DEFAULT 'y',
  `accept_user_email` char(1) NOT NULL DEFAULT 'y',
  `notify_by_default` char(1) NOT NULL DEFAULT 'y',
  `notify_of_pm` char(1) NOT NULL DEFAULT 'y',
  `display_avatars` char(1) NOT NULL DEFAULT 'y',
  `display_signatures` char(1) NOT NULL DEFAULT 'y',
  `parse_smileys` char(1) NOT NULL DEFAULT 'y',
  `smart_notifications` char(1) NOT NULL DEFAULT 'y',
  `language` varchar(50) NOT NULL,
  `timezone` varchar(50) NOT NULL,
  `time_format` char(2) NOT NULL DEFAULT 'us',
  `cp_theme` varchar(32) DEFAULT NULL,
  `profile_theme` varchar(32) DEFAULT NULL,
  `forum_theme` varchar(32) DEFAULT NULL,
  `tracker` text,
  `template_size` varchar(2) NOT NULL DEFAULT '20',
  `notepad` text,
  `notepad_size` varchar(2) NOT NULL DEFAULT '18',
  `quick_links` text,
  `quick_tabs` text,
  `show_sidebar` char(1) NOT NULL DEFAULT 'n',
  `pmember_id` int(10) NOT NULL DEFAULT '0',
  `rte_enabled` char(1) NOT NULL DEFAULT 'y',
  `rte_toolset_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`),
  KEY `group_id` (`group_id`),
  KEY `unique_id` (`unique_id`),
  KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_members` WRITE;
/*!40000 ALTER TABLE `exp_members` DISABLE KEYS */;

INSERT INTO `exp_members` (`member_id`, `group_id`, `username`, `screen_name`, `password`, `salt`, `unique_id`, `crypt_key`, `authcode`, `email`, `url`, `location`, `occupation`, `interests`, `bday_d`, `bday_m`, `bday_y`, `aol_im`, `yahoo_im`, `msn_im`, `icq`, `bio`, `signature`, `avatar_filename`, `avatar_width`, `avatar_height`, `photo_filename`, `photo_width`, `photo_height`, `sig_img_filename`, `sig_img_width`, `sig_img_height`, `ignore_list`, `private_messages`, `accept_messages`, `last_view_bulletins`, `last_bulletin_date`, `ip_address`, `join_date`, `last_visit`, `last_activity`, `total_entries`, `total_comments`, `total_forum_topics`, `total_forum_posts`, `last_entry_date`, `last_comment_date`, `last_forum_post_date`, `last_email_date`, `in_authorlist`, `accept_admin_email`, `accept_user_email`, `notify_by_default`, `notify_of_pm`, `display_avatars`, `display_signatures`, `parse_smileys`, `smart_notifications`, `language`, `timezone`, `time_format`, `cp_theme`, `profile_theme`, `forum_theme`, `tracker`, `template_size`, `notepad`, `notepad_size`, `quick_links`, `quick_tabs`, `show_sidebar`, `pmember_id`, `rte_enabled`, `rte_toolset_id`)
VALUES
	(1,1,'confluxgroup','confluxgroup','e77214f587198d8e931f6c5ec503eadcd8957406ee893f71d00f243f22198c87351b0709c3897d6372935e965879b22d982a5826c209a56f1934fb76df07f86f','zSKr\\nvh{A@~-}Qy*Znf:i3=5a2bn#{*r3z:?ap</l[0VY`a)x-`dxy!A(%SxhGdq\'ExOFwJp@]pV\\w*~?h|y=U.H.W&+jX3ah>_;]=\"`EB]63Ts?IqL(7<G[}u\"@e&G','b44b8b2c881937623d85bda305f0ab4e8c1f428c','ce522fc4c3a57689ab3220761291b97471b6618a',NULL,'projects@confluxgroup.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'y',0,0,'127.0.0.1',1338421576,1379784614,1383704733,0,0,0,0,0,0,0,0,'n','y','y','y','y','y','y','y','y','english','UM5','us',NULL,NULL,NULL,NULL,'20',NULL,'18','',NULL,'n',0,'y',0);

/*!40000 ALTER TABLE `exp_members` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_message_attachments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_message_attachments`;

CREATE TABLE `exp_message_attachments` (
  `attachment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL DEFAULT '0',
  `message_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attachment_name` varchar(50) NOT NULL DEFAULT '',
  `attachment_hash` varchar(40) NOT NULL DEFAULT '',
  `attachment_extension` varchar(20) NOT NULL DEFAULT '',
  `attachment_location` varchar(150) NOT NULL DEFAULT '',
  `attachment_date` int(10) unsigned NOT NULL DEFAULT '0',
  `attachment_size` int(10) unsigned NOT NULL DEFAULT '0',
  `is_temp` char(1) NOT NULL DEFAULT 'y',
  PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_message_copies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_message_copies`;

CREATE TABLE `exp_message_copies` (
  `copy_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sender_id` int(10) unsigned NOT NULL DEFAULT '0',
  `recipient_id` int(10) unsigned NOT NULL DEFAULT '0',
  `message_received` char(1) NOT NULL DEFAULT 'n',
  `message_read` char(1) NOT NULL DEFAULT 'n',
  `message_time_read` int(10) unsigned NOT NULL DEFAULT '0',
  `attachment_downloaded` char(1) NOT NULL DEFAULT 'n',
  `message_folder` int(10) unsigned NOT NULL DEFAULT '1',
  `message_authcode` varchar(10) NOT NULL DEFAULT '',
  `message_deleted` char(1) NOT NULL DEFAULT 'n',
  `message_status` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`copy_id`),
  KEY `message_id` (`message_id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `sender_id` (`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_message_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_message_data`;

CREATE TABLE `exp_message_data` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL DEFAULT '0',
  `message_date` int(10) unsigned NOT NULL DEFAULT '0',
  `message_subject` varchar(255) NOT NULL DEFAULT '',
  `message_body` text NOT NULL,
  `message_tracking` char(1) NOT NULL DEFAULT 'y',
  `message_attachments` char(1) NOT NULL DEFAULT 'n',
  `message_recipients` varchar(200) NOT NULL DEFAULT '',
  `message_cc` varchar(200) NOT NULL DEFAULT '',
  `message_hide_cc` char(1) NOT NULL DEFAULT 'n',
  `message_sent_copy` char(1) NOT NULL DEFAULT 'n',
  `total_recipients` int(5) unsigned NOT NULL DEFAULT '0',
  `message_status` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`message_id`),
  KEY `sender_id` (`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_message_folders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_message_folders`;

CREATE TABLE `exp_message_folders` (
  `member_id` int(10) unsigned NOT NULL DEFAULT '0',
  `folder1_name` varchar(50) NOT NULL DEFAULT 'InBox',
  `folder2_name` varchar(50) NOT NULL DEFAULT 'Sent',
  `folder3_name` varchar(50) NOT NULL DEFAULT '',
  `folder4_name` varchar(50) NOT NULL DEFAULT '',
  `folder5_name` varchar(50) NOT NULL DEFAULT '',
  `folder6_name` varchar(50) NOT NULL DEFAULT '',
  `folder7_name` varchar(50) NOT NULL DEFAULT '',
  `folder8_name` varchar(50) NOT NULL DEFAULT '',
  `folder9_name` varchar(50) NOT NULL DEFAULT '',
  `folder10_name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_message_listed
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_message_listed`;

CREATE TABLE `exp_message_listed` (
  `listed_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) unsigned NOT NULL DEFAULT '0',
  `listed_member` int(10) unsigned NOT NULL DEFAULT '0',
  `listed_description` varchar(100) NOT NULL DEFAULT '',
  `listed_type` varchar(10) NOT NULL DEFAULT 'blocked',
  PRIMARY KEY (`listed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_module_member_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_module_member_groups`;

CREATE TABLE `exp_module_member_groups` (
  `group_id` smallint(4) unsigned NOT NULL,
  `module_id` mediumint(5) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_modules`;

CREATE TABLE `exp_modules` (
  `module_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(50) NOT NULL,
  `module_version` varchar(12) NOT NULL,
  `has_cp_backend` char(1) NOT NULL DEFAULT 'n',
  `has_publish_fields` char(1) NOT NULL DEFAULT 'n',
  `settings` text,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_modules` WRITE;
/*!40000 ALTER TABLE `exp_modules` DISABLE KEYS */;

INSERT INTO `exp_modules` (`module_id`, `module_name`, `module_version`, `has_cp_backend`, `has_publish_fields`, `settings`)
VALUES
	(1,'Pages','2.2','y','y',NULL),
	(2,'Safecracker','2.1','y','n',NULL),
	(3,'Channel','2.0.1','n','n',NULL),
	(4,'Member','2.1','n','n',NULL),
	(6,'Rte','1.0.1','y','n',NULL),
	(7,'Updater','3.2.8','y','n','a:7:{s:20:\"file_transfer_method\";s:5:\"local\";s:3:\"ftp\";a:6:{s:8:\"hostname\";s:0:\"\";s:4:\"port\";s:2:\"21\";s:8:\"username\";s:0:\"\";s:8:\"password\";s:0:\"\";s:7:\"passive\";s:3:\"yes\";s:3:\"ssl\";s:2:\"no\";}s:4:\"sftp\";a:8:{s:8:\"hostname\";s:0:\"\";s:4:\"port\";s:2:\"22\";s:8:\"username\";s:0:\"\";s:8:\"password\";s:0:\"\";s:11:\"auth_method\";s:8:\"password\";s:12:\"key_contents\";s:0:\"\";s:12:\"key_password\";s:0:\"\";s:8:\"key_path\";s:0:\"\";}s:8:\"path_map\";a:6:{s:4:\"root\";s:42:\"/Users/jeremygimbel/Sites/eebase/httpdocs/\";s:6:\"backup\";s:54:\"/Users/jeremygimbel/Sites/eebase/httpdocs/site/backup/\";s:6:\"system\";s:49:\"/Users/jeremygimbel/Sites/eebase/httpdocs/system/\";s:18:\"system_third_party\";s:59:\"/Users/jeremygimbel/Sites/eebase/httpdocs/site/third_party/\";s:6:\"themes\";s:49:\"/Users/jeremygimbel/Sites/eebase/httpdocs/themes/\";s:18:\"themes_third_party\";s:61:\"/Users/jeremygimbel/Sites/eebase/httpdocs/themes/third_party/\";}s:9:\"menu_link\";a:3:{s:4:\"root\";s:3:\"yes\";s:5:\"tools\";s:3:\"yes\";s:5:\"admin\";s:3:\"yes\";}s:10:\"action_url\";a:1:{s:19:\"actionGeneralRouter\";s:0:\"\";}s:11:\"track_stats\";s:3:\"yes\";}'),
	(12,'Freeform','4.1.3','y','n',NULL);

/*!40000 ALTER TABLE `exp_modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_online_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_online_users`;

CREATE TABLE `exp_online_users` (
  `online_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `member_id` int(10) NOT NULL DEFAULT '0',
  `in_forum` char(1) NOT NULL DEFAULT 'n',
  `name` varchar(50) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `anon` char(1) NOT NULL,
  PRIMARY KEY (`online_id`),
  KEY `date` (`date`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_pages_configuration
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_pages_configuration`;

CREATE TABLE `exp_pages_configuration` (
  `configuration_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(8) unsigned NOT NULL DEFAULT '1',
  `configuration_name` varchar(60) NOT NULL,
  `configuration_value` varchar(100) NOT NULL,
  PRIMARY KEY (`configuration_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_password_lockout
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_password_lockout`;

CREATE TABLE `exp_password_lockout` (
  `lockout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login_date` int(10) unsigned NOT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `username` varchar(50) NOT NULL,
  PRIMARY KEY (`lockout_id`),
  KEY `login_date` (`login_date`),
  KEY `ip_address` (`ip_address`),
  KEY `user_agent` (`user_agent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_relationships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_relationships`;

CREATE TABLE `exp_relationships` (
  `relationship_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `child_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order` int(10) unsigned NOT NULL DEFAULT '0',
  `field_id` int(10) unsigned NOT NULL DEFAULT '0',
  `grid_field_id` int(10) unsigned NOT NULL DEFAULT '0',
  `grid_col_id` int(10) unsigned NOT NULL DEFAULT '0',
  `grid_row_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`relationship_id`),
  KEY `rel_parent_id` (`parent_id`),
  KEY `rel_child_id` (`child_id`),
  KEY `field_id` (`field_id`),
  KEY `grid_row_id` (`grid_row_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_remember_me
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_remember_me`;

CREATE TABLE `exp_remember_me` (
  `remember_me_id` varchar(40) NOT NULL DEFAULT '0',
  `member_id` int(10) DEFAULT '0',
  `ip_address` varchar(45) DEFAULT '0',
  `user_agent` varchar(120) DEFAULT '',
  `admin_sess` tinyint(1) DEFAULT '0',
  `site_id` int(4) DEFAULT '1',
  `expiration` int(10) DEFAULT '0',
  `last_refresh` int(10) DEFAULT '0',
  PRIMARY KEY (`remember_me_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_reset_password
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_reset_password`;

CREATE TABLE `exp_reset_password` (
  `reset_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) unsigned NOT NULL,
  `resetcode` varchar(12) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`reset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_revision_tracker
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_revision_tracker`;

CREATE TABLE `exp_revision_tracker` (
  `tracker_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `item_table` varchar(20) NOT NULL,
  `item_field` varchar(20) NOT NULL,
  `item_date` int(10) NOT NULL,
  `item_author_id` int(10) unsigned NOT NULL,
  `item_data` mediumtext NOT NULL,
  PRIMARY KEY (`tracker_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_rte_tools
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_rte_tools`;

CREATE TABLE `exp_rte_tools` (
  `tool_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(75) DEFAULT NULL,
  `class` varchar(75) DEFAULT NULL,
  `enabled` char(1) DEFAULT 'y',
  PRIMARY KEY (`tool_id`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_rte_tools` WRITE;
/*!40000 ALTER TABLE `exp_rte_tools` DISABLE KEYS */;

INSERT INTO `exp_rte_tools` (`tool_id`, `name`, `class`, `enabled`)
VALUES
	(1,'Blockquote','Blockquote_rte','y'),
	(2,'Bold','Bold_rte','y'),
	(3,'Headings','Headings_rte','y'),
	(4,'Image','Image_rte','y'),
	(5,'Italic','Italic_rte','y'),
	(6,'Link','Link_rte','y'),
	(7,'Ordered List','Ordered_list_rte','y'),
	(8,'Underline','Underline_rte','y'),
	(9,'Unordered List','Unordered_list_rte','y'),
	(10,'View Source','View_source_rte','y');

/*!40000 ALTER TABLE `exp_rte_tools` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_rte_toolsets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_rte_toolsets`;

CREATE TABLE `exp_rte_toolsets` (
  `toolset_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  `tools` text,
  `enabled` char(1) DEFAULT 'y',
  PRIMARY KEY (`toolset_id`),
  KEY `member_id` (`member_id`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_rte_toolsets` WRITE;
/*!40000 ALTER TABLE `exp_rte_toolsets` DISABLE KEYS */;

INSERT INTO `exp_rte_toolsets` (`toolset_id`, `member_id`, `name`, `tools`, `enabled`)
VALUES
	(1,0,'Default','3|2|5|1|9|7|6|4|10','y');

/*!40000 ALTER TABLE `exp_rte_toolsets` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_security_hashes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_security_hashes`;

CREATE TABLE `exp_security_hashes` (
  `hash_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) unsigned NOT NULL,
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `hash` varchar(40) NOT NULL,
  `used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`hash_id`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_security_hashes` WRITE;
/*!40000 ALTER TABLE `exp_security_hashes` DISABLE KEYS */;

INSERT INTO `exp_security_hashes` (`hash_id`, `date`, `session_id`, `hash`, `used`)
VALUES
	(168,1383704728,'0','c23cd96a37130960f6436a3170ddaa75c953555c',0),
	(169,1383704728,'0','896ef5cd711a43f3d5562799bf985cb4e0186905',0),
	(170,1383704728,'0','f8def99554181b975ff41d98821f867b2176b1cd',1),
	(171,1383704728,'0','4b300be608e5131f7e1a0e2d3d78bfe0acd42b63',0),
	(172,1383704732,'0','58c88e5a51803ddc9322c654d0b319b8c7cfd134',0),
	(173,1383704733,'ba3243507d211e4e766ae566b4460f8e06261a91','e7327ec52331c091eac2f04b1bbdf06d0c381d6a',0),
	(174,1383704733,'ba3243507d211e4e766ae566b4460f8e06261a91','c6ef6a00783fca7406597dec7b2099601b702138',0),
	(175,1383704733,'ba3243507d211e4e766ae566b4460f8e06261a91','a9cd49c870ee853a7b61f901c253f973610d377b',0),
	(176,1383704736,'ba3243507d211e4e766ae566b4460f8e06261a91','f4a1d75ddc5c2a1cc7d94d636ba57771a0196ef1',0),
	(177,1383704737,'ba3243507d211e4e766ae566b4460f8e06261a91','7253531174749af2746b864b17236e565c769d7a',0),
	(178,1383704737,'ba3243507d211e4e766ae566b4460f8e06261a91','7360fc0e91656843f66fb4ae5434fab68fdf9a48',0),
	(179,1383704737,'ba3243507d211e4e766ae566b4460f8e06261a91','07ffafaa6c48b10e7b86dcd4b75df4438b60c458',0),
	(180,1383704737,'ba3243507d211e4e766ae566b4460f8e06261a91','e1691637ef9e8f7258955a61d6662752fa755832',0),
	(181,1383704824,'ba3243507d211e4e766ae566b4460f8e06261a91','4456697b6d35d206d181ee83950ad47c893d44e3',0),
	(182,1383704824,'ba3243507d211e4e766ae566b4460f8e06261a91','b489aeb3e3b81007f88ec5ca4a179f08d133c89a',0),
	(183,1383704824,'ba3243507d211e4e766ae566b4460f8e06261a91','535e2f3149fb61f5d032026ac6653eac3a9944f5',0),
	(184,1383704824,'ba3243507d211e4e766ae566b4460f8e06261a91','08bfa4b3e3e75c02663cd217f9b70eb1a7f0a1ea',0),
	(185,1383704825,'ba3243507d211e4e766ae566b4460f8e06261a91','106ad072ec935f5b1d9ff773fead53624da5bbf9',0),
	(186,1383704825,'ba3243507d211e4e766ae566b4460f8e06261a91','f20feca89265243b3442f3003c3aa0907006c1bd',0),
	(187,1383704825,'ba3243507d211e4e766ae566b4460f8e06261a91','c9a076179411f2d8c5579e041af1a17221067958',0),
	(188,1383704825,'ba3243507d211e4e766ae566b4460f8e06261a91','2bf0271a43fe09724e52d79b354217c756007ace',0),
	(189,1383704825,'ba3243507d211e4e766ae566b4460f8e06261a91','af11edfbb555df25f2d31ece95bbbdd3159964e1',0),
	(190,1383704825,'ba3243507d211e4e766ae566b4460f8e06261a91','7da14f8a320c64b0601f626de9ab68f279350b12',0),
	(191,1383704825,'ba3243507d211e4e766ae566b4460f8e06261a91','1051788c48963304e2a61f7757fda5cc986606d8',0),
	(192,1383704826,'ba3243507d211e4e766ae566b4460f8e06261a91','f8020931ac70b281be5fdb91eb8805a90098e83c',0),
	(193,1383704869,'ba3243507d211e4e766ae566b4460f8e06261a91','088466e0d627327f70990161ae4869bb72215248',0),
	(194,1383704935,'ba3243507d211e4e766ae566b4460f8e06261a91','5697aa5099dff5f38e52d91a5c1deb1a4851cc26',0),
	(195,1383704941,'ba3243507d211e4e766ae566b4460f8e06261a91','927a8c0eb653d02aafe50c5e5838bdae5a24d9c5',0),
	(196,1383704941,'ba3243507d211e4e766ae566b4460f8e06261a91','52a7d3f84d7be698541cb2ad23cfef8c1954b637',0),
	(197,1383704941,'ba3243507d211e4e766ae566b4460f8e06261a91','29701934d6d720c1a22bf45b88b76ed8aa219c84',0),
	(198,1383704941,'ba3243507d211e4e766ae566b4460f8e06261a91','fffdc37022d5992eb2e8c30bdeec6fb60446163f',0),
	(199,1383704941,'ba3243507d211e4e766ae566b4460f8e06261a91','b60653f846a5b0d2cd683c30d81f6ee63619455c',0),
	(200,1383704941,'ba3243507d211e4e766ae566b4460f8e06261a91','c56fd366e2cbddac0181636338ffd69d42d4306f',0),
	(201,1383704941,'ba3243507d211e4e766ae566b4460f8e06261a91','9004193257207e7145139ce3101e57f21a57a9b7',0),
	(202,1383704941,'ba3243507d211e4e766ae566b4460f8e06261a91','0ea8c98ef2ecc3eaf1428879b247fe2c8c72954e',0),
	(203,1383704941,'ba3243507d211e4e766ae566b4460f8e06261a91','ee313464168298017150e4ca2e218a31d55f70a6',0),
	(204,1383704942,'ba3243507d211e4e766ae566b4460f8e06261a91','e35b61ce9d55da2a7d0f37ae7617adbbde1aa7f1',0),
	(205,1383704942,'ba3243507d211e4e766ae566b4460f8e06261a91','c0cd8f97d3db02811d112f8cb1e90fb6f95cf9f1',0),
	(206,1383704942,'ba3243507d211e4e766ae566b4460f8e06261a91','72db4fcf2f08b1617d0a4489752b56a9e9aeb1bb',0),
	(207,1383704942,'ba3243507d211e4e766ae566b4460f8e06261a91','693b40b561dc3b0f866d8c579489bdd7b2f055ae',0),
	(208,1383704942,'ba3243507d211e4e766ae566b4460f8e06261a91','ee3d68af3e539b9164a2d96369ab87a2273f0746',0),
	(209,1383704942,'ba3243507d211e4e766ae566b4460f8e06261a91','464c5ca2a07e2f8e6bd3c43c943684e9ff7171bf',0),
	(210,1383704942,'ba3243507d211e4e766ae566b4460f8e06261a91','37f2d7ce3dc06a2a409a0b9d2406b6be61e09064',0),
	(211,1383704942,'ba3243507d211e4e766ae566b4460f8e06261a91','9c24b585c6dcc916eaad3c910166a8c2bbc33c76',0),
	(212,1383704943,'ba3243507d211e4e766ae566b4460f8e06261a91','b306fb483d70efc4827623d0f37b6b9a801e01db',0),
	(213,1383704943,'ba3243507d211e4e766ae566b4460f8e06261a91','8c69dfdadaf03c268e37553c3ee126484dccdb9e',0),
	(214,1383704943,'ba3243507d211e4e766ae566b4460f8e06261a91','a5bd30160e7577b57bca25b767cfe87c61b91839',0),
	(215,1383704943,'ba3243507d211e4e766ae566b4460f8e06261a91','ad47e4df289759cb63ebb5ec058b6935f9405de3',0),
	(216,1383704943,'ba3243507d211e4e766ae566b4460f8e06261a91','9efac7ba2cdfb13a9c75c4d25719cae5c3e9f7ae',0),
	(217,1383704943,'ba3243507d211e4e766ae566b4460f8e06261a91','f6ad2654342be3db57712d6ec543986bf3528612',0),
	(218,1383704943,'ba3243507d211e4e766ae566b4460f8e06261a91','dcc80c992cdf21310c87f379979566607a8b4ecb',0),
	(219,1383704943,'ba3243507d211e4e766ae566b4460f8e06261a91','74d953508a062e8b82989a995cd2c68bd73bd138',0),
	(220,1383704944,'ba3243507d211e4e766ae566b4460f8e06261a91','aa2ec4c4a3464aa5cd072fbd6fd86e2ccd300896',0),
	(221,1383704944,'ba3243507d211e4e766ae566b4460f8e06261a91','ba311237e63b98267aa972592a342a69e0ee73ae',0),
	(222,1383704944,'ba3243507d211e4e766ae566b4460f8e06261a91','3120b9017facdba865218896c53f8b9ea3da12cf',0),
	(223,1383704944,'ba3243507d211e4e766ae566b4460f8e06261a91','89be80a294dca531087ff934563510b84cf713d5',0),
	(224,1383704944,'ba3243507d211e4e766ae566b4460f8e06261a91','9dfeda26d251617b26a6a8ca88ae9fb746ca91f6',0),
	(225,1383704944,'ba3243507d211e4e766ae566b4460f8e06261a91','462ce70d0435b70fb15b994a92bb6de5fa01b3c7',0),
	(226,1383704945,'ba3243507d211e4e766ae566b4460f8e06261a91','3b36631e945d907367e85631d321f5b79cbda8e2',0),
	(227,1383704945,'ba3243507d211e4e766ae566b4460f8e06261a91','f9e3535cced84f4148f42c49ac26b822d7dab4ff',0),
	(228,1383704945,'ba3243507d211e4e766ae566b4460f8e06261a91','938f5531ffce8ce0a987fd0de8ad9cb75a16196b',0),
	(229,1383704946,'ba3243507d211e4e766ae566b4460f8e06261a91','ca8bb482a322db48155682197a917e606c591505',0),
	(230,1383704946,'ba3243507d211e4e766ae566b4460f8e06261a91','06ff459318330a9e75326c15dd1afbf0ae10f3e1',0),
	(231,1383704946,'ba3243507d211e4e766ae566b4460f8e06261a91','46d777dfdc42781aa0bbeff6f84617b6d565bc21',0),
	(232,1383704946,'ba3243507d211e4e766ae566b4460f8e06261a91','9a2bce9c621ea2d3b144cf1d1a47a24ba5f587a6',0),
	(233,1383704946,'ba3243507d211e4e766ae566b4460f8e06261a91','bc177601612164bfff18b8ce9790e15961583884',0),
	(234,1383704947,'ba3243507d211e4e766ae566b4460f8e06261a91','adde4b0858a5b12d46143f41125a5d07bdac4e8d',0),
	(235,1383704947,'ba3243507d211e4e766ae566b4460f8e06261a91','97065a23fe663e564bd7d1cd4ff61b236071d160',0),
	(236,1383704947,'ba3243507d211e4e766ae566b4460f8e06261a91','f60c139efd6e7e0a94116169d5a692ce9e959e27',0),
	(237,1383704947,'ba3243507d211e4e766ae566b4460f8e06261a91','f0b2b0ad9f3b21c3f32c2b759a48a24498b8a019',0),
	(238,1383704947,'ba3243507d211e4e766ae566b4460f8e06261a91','c51fc43ab8a9de529397afb9783cae09552f16e0',0),
	(239,1383704947,'ba3243507d211e4e766ae566b4460f8e06261a91','1a4db7d553da0e63ad50eaaa98249fc0a609de0e',0),
	(240,1383704947,'ba3243507d211e4e766ae566b4460f8e06261a91','1064c6cdf337b298ab39836b4eb2961983476025',0),
	(241,1383704948,'ba3243507d211e4e766ae566b4460f8e06261a91','8a8c39905225f1c61e1c8520bb8025769ba15f8f',0),
	(242,1383704948,'ba3243507d211e4e766ae566b4460f8e06261a91','4ab5f0ad067861f49ec93229462e52dac2cb3d0b',0),
	(243,1383704948,'ba3243507d211e4e766ae566b4460f8e06261a91','0139e6772f9f21a3e8a87a30171222f9c9c7fa1f',0),
	(244,1383704948,'ba3243507d211e4e766ae566b4460f8e06261a91','d945c643ba1ecf50bde305e3388b3fba9ecb415a',0),
	(245,1383704948,'ba3243507d211e4e766ae566b4460f8e06261a91','edf6d458fc5ee699e213142dd536596bd6a4a338',0),
	(246,1383704948,'ba3243507d211e4e766ae566b4460f8e06261a91','2ac9f0ea904f63b02d12fcdc859421d182179f2c',0),
	(247,1383704948,'ba3243507d211e4e766ae566b4460f8e06261a91','9b5cc08713bc66802c970618750abd944587b47c',0),
	(248,1383704949,'ba3243507d211e4e766ae566b4460f8e06261a91','0bfa16db924d3678f9c0f5447016620f6a0a9a96',0),
	(249,1383704949,'ba3243507d211e4e766ae566b4460f8e06261a91','2e159ba48358c8513a299a25e5e1c0eab2c3c514',0),
	(250,1383704949,'ba3243507d211e4e766ae566b4460f8e06261a91','c399a919edb72fb1e0b5bf529b66c9c0e0a183a9',0),
	(251,1383704949,'ba3243507d211e4e766ae566b4460f8e06261a91','344d5905e39103b417c12c7d110923ef5ee7cc79',0),
	(252,1383704949,'ba3243507d211e4e766ae566b4460f8e06261a91','b3c3064dffda684a36b9fd6da905ec01d6dc505d',0),
	(253,1383704949,'ba3243507d211e4e766ae566b4460f8e06261a91','5aff41adfe8554b9dca1e0bd92efa228d50992f2',0),
	(254,1383704950,'ba3243507d211e4e766ae566b4460f8e06261a91','9650e1920101521d042dbf9749366a3155014ac2',0),
	(255,1383704950,'ba3243507d211e4e766ae566b4460f8e06261a91','5f92fdd891ff504f5e3ce559ce5144ff5ab6ef40',0),
	(256,1383704950,'ba3243507d211e4e766ae566b4460f8e06261a91','46adef2b499bf414b1ddbfe596426a305829708c',0),
	(257,1383704950,'ba3243507d211e4e766ae566b4460f8e06261a91','670bb13cbc95c5b453236e2628f8cdab042dc266',0),
	(258,1383704951,'ba3243507d211e4e766ae566b4460f8e06261a91','3d8329ecfa9e16831e6f0164190125c17ddb9fb7',0),
	(259,1383704951,'ba3243507d211e4e766ae566b4460f8e06261a91','67e0753e25e1cd4c4416abfe16e512bafe5a6312',0),
	(260,1383704951,'ba3243507d211e4e766ae566b4460f8e06261a91','a3f3e38efa878b6daaf48a47771c87745ffb0d51',0),
	(261,1383704951,'ba3243507d211e4e766ae566b4460f8e06261a91','38ca05827b0c3500e28d4f489ae1f77f7eeb7e90',0),
	(262,1383704951,'ba3243507d211e4e766ae566b4460f8e06261a91','cdc0b0add0b08e83d1e3c278e03a69e26e8f0148',0),
	(263,1383704951,'ba3243507d211e4e766ae566b4460f8e06261a91','24d2951422ec69c0a900b94208225f48401b0752',0),
	(264,1383704951,'ba3243507d211e4e766ae566b4460f8e06261a91','a75633ed780d0aa46acfda5489dbefae4eb47529',0),
	(265,1383704952,'ba3243507d211e4e766ae566b4460f8e06261a91','06ff60267c11782b68f76b47cbe8feaf329ecfd0',0),
	(266,1383704952,'ba3243507d211e4e766ae566b4460f8e06261a91','3ea51c1493a3328ea52c4ac8fd7176cf74b95c49',0),
	(267,1383704952,'ba3243507d211e4e766ae566b4460f8e06261a91','69c6eceaf9a1f3165143f5e0fad1d01f845f5137',0),
	(268,1383704952,'ba3243507d211e4e766ae566b4460f8e06261a91','081dc34ba0a99346c805d75df2c2baa34c8af01c',0),
	(269,1383704952,'ba3243507d211e4e766ae566b4460f8e06261a91','9dafee40b293238bd5cba18819b659d5bd274f88',0),
	(270,1383704952,'ba3243507d211e4e766ae566b4460f8e06261a91','590eb85b7c966862c9d392795704e557a889bef7',0),
	(271,1383704952,'ba3243507d211e4e766ae566b4460f8e06261a91','8ec7de74d8665a81f060c6439d4b11c8bdfaaccb',0),
	(272,1383704952,'ba3243507d211e4e766ae566b4460f8e06261a91','60651c80db7fb4962c6c20c6dd87aeed4e2c7199',0),
	(273,1383704953,'ba3243507d211e4e766ae566b4460f8e06261a91','45bb9e080d1e84625c886805db1f0010b5a86bde',0),
	(274,1383704953,'ba3243507d211e4e766ae566b4460f8e06261a91','551b141c37a95381edf1cbe4b5aa48693b088b43',0),
	(275,1383704953,'ba3243507d211e4e766ae566b4460f8e06261a91','fce9b77a2ee3d813df46af02fabe364b133cce2f',0),
	(276,1383704953,'ba3243507d211e4e766ae566b4460f8e06261a91','756ba64480667d7294357268d74f4b7a8dab103b',0),
	(277,1383704953,'ba3243507d211e4e766ae566b4460f8e06261a91','4d10649f5cba9ed7bd34c36dea25f5235ec0c14d',0),
	(278,1383704953,'ba3243507d211e4e766ae566b4460f8e06261a91','ff31083cda86185b54107af4c513ae7192a39a84',0),
	(279,1383704953,'ba3243507d211e4e766ae566b4460f8e06261a91','70be06fbe08301363dd88fcd148c01380837108f',0),
	(280,1383704953,'ba3243507d211e4e766ae566b4460f8e06261a91','46e09e2c9b42600d2a7d67c89c97d349ac1c3dfe',0),
	(281,1383704954,'ba3243507d211e4e766ae566b4460f8e06261a91','5260e461f7ae5644a2330022ddefd49975590612',0),
	(282,1383704954,'ba3243507d211e4e766ae566b4460f8e06261a91','e0443bd07727319ed3183c49e48591b87a39f0b7',0),
	(283,1383704954,'ba3243507d211e4e766ae566b4460f8e06261a91','291d8d01b12962e960c31d47f771382e97457ff0',0),
	(284,1383704954,'ba3243507d211e4e766ae566b4460f8e06261a91','653537b8fc7462793a5b82ecf968c771fd8867f6',0),
	(285,1383704954,'ba3243507d211e4e766ae566b4460f8e06261a91','b47186db5e063819b6c477dbf5d5b79778cff042',0),
	(286,1383704954,'ba3243507d211e4e766ae566b4460f8e06261a91','d374b6a452c09c8da234a305abe2e6efb0eb2063',0),
	(287,1383704954,'ba3243507d211e4e766ae566b4460f8e06261a91','b4311b8ca2e57364ed9f740b631791167b9ce6ad',0),
	(288,1383704954,'ba3243507d211e4e766ae566b4460f8e06261a91','6a1624d26c96bad433cc7ad5247ce7c7d3ca4a1b',0),
	(289,1383704955,'ba3243507d211e4e766ae566b4460f8e06261a91','367b8d3a9117a6e622a7e409188f3416fd802b49',0),
	(290,1383704955,'ba3243507d211e4e766ae566b4460f8e06261a91','30902e8d71a9930d63189812906f95488d28bd0e',0),
	(291,1383704955,'ba3243507d211e4e766ae566b4460f8e06261a91','a5b7bbd1e9f1ae2d1cf3909d217ca04de5583054',0),
	(292,1383704955,'ba3243507d211e4e766ae566b4460f8e06261a91','9043cb89d78a8f0cc890b6ffc5ed852ec1d17557',0),
	(293,1383704955,'ba3243507d211e4e766ae566b4460f8e06261a91','f5ae4de6476ed387e4538a99920a2f586e377e98',0),
	(294,1383704955,'ba3243507d211e4e766ae566b4460f8e06261a91','1f5713442553d619b9c647531a13b73a74315d4d',0),
	(295,1383704955,'ba3243507d211e4e766ae566b4460f8e06261a91','6d8b7594818874e17e16cf33438505098e07e99b',0),
	(296,1383704956,'ba3243507d211e4e766ae566b4460f8e06261a91','0caea044f063477c08874e19c1754a2668a9f86c',0),
	(297,1383704956,'ba3243507d211e4e766ae566b4460f8e06261a91','6665cfa5bfca180a80efc6f297307ac446311636',0),
	(298,1383704956,'ba3243507d211e4e766ae566b4460f8e06261a91','87b1c7548647d4185ece39ea91e36354f58d14b2',0),
	(299,1383704956,'ba3243507d211e4e766ae566b4460f8e06261a91','b25f57f1614c21f3dd812a14d6f215bfc2ed7467',0),
	(300,1383704956,'ba3243507d211e4e766ae566b4460f8e06261a91','b23ff7f27a6b3d72194c76768d64cfd17023c712',0),
	(301,1383704956,'ba3243507d211e4e766ae566b4460f8e06261a91','87bc7a93db6dfaf9a293f7866bd789f2cb0e06e5',0),
	(302,1383704956,'ba3243507d211e4e766ae566b4460f8e06261a91','211f54e23658b63f98fe9587f99703491aa14ad6',0),
	(303,1383704956,'ba3243507d211e4e766ae566b4460f8e06261a91','fa3007130744219252f5d0e15fcb4756f5a79a51',0),
	(304,1383704956,'ba3243507d211e4e766ae566b4460f8e06261a91','c72fc0c69f56255e55c01084ddceee12e233477d',0),
	(305,1383704957,'ba3243507d211e4e766ae566b4460f8e06261a91','f7e99d51d27af82e507175218ed73b3c444a19d0',0),
	(306,1383704957,'ba3243507d211e4e766ae566b4460f8e06261a91','2f2a9fa251bd2f9b51eaeffc3a44253a4c9d126d',0),
	(307,1383704957,'ba3243507d211e4e766ae566b4460f8e06261a91','dfdc2a65c4fcd27b047e438d0e4bc76a22f6f1ce',0),
	(308,1383704957,'ba3243507d211e4e766ae566b4460f8e06261a91','da4d732ac28e8f234a4b97d64de3268b3227f606',0),
	(309,1383704957,'ba3243507d211e4e766ae566b4460f8e06261a91','eb04c9de0a80d0f46fc1a37f864ae7d9e6e57f49',0),
	(310,1383704957,'ba3243507d211e4e766ae566b4460f8e06261a91','15ca193316febada29e77b0dcb38484b070ba8b3',0),
	(311,1383704958,'ba3243507d211e4e766ae566b4460f8e06261a91','20452d11960e94601eb2963cb515a5cab93c42a1',0),
	(312,1383704958,'ba3243507d211e4e766ae566b4460f8e06261a91','db5beeba351029bc5c8dc341c6f1605a9bf8b89f',0),
	(313,1383704958,'ba3243507d211e4e766ae566b4460f8e06261a91','4eeb9957e51180efe0586c4e40d95a8791c5d633',0),
	(314,1383704958,'ba3243507d211e4e766ae566b4460f8e06261a91','e7f6057500b0d524fb9cbaaacd58a5d203e683e8',0),
	(315,1383704958,'ba3243507d211e4e766ae566b4460f8e06261a91','c14f2f18184e255e41e9cd4b0de1b94244d8a3a9',0),
	(316,1383704958,'ba3243507d211e4e766ae566b4460f8e06261a91','9fb11ca2d266e013827f1da16d56d7c80bc08523',0),
	(317,1383704958,'ba3243507d211e4e766ae566b4460f8e06261a91','c8613c76710a390cead3a98fd004d57cd5910202',0),
	(318,1383704958,'ba3243507d211e4e766ae566b4460f8e06261a91','aef8bca46785a12a52a9ee5b463b4154446db3fd',0),
	(319,1383704959,'ba3243507d211e4e766ae566b4460f8e06261a91','0cb03b70fd888bf1d815c58049f5cd13c3d74300',0),
	(320,1383704959,'ba3243507d211e4e766ae566b4460f8e06261a91','49d20dbde3211340a7f48146a484b85e39d6365a',0),
	(321,1383704959,'ba3243507d211e4e766ae566b4460f8e06261a91','62acef7740f6e94b8e639d7271d9258cbabae032',0),
	(322,1383704959,'ba3243507d211e4e766ae566b4460f8e06261a91','273355cd08a2030cb9279577d3a21353197b49df',0),
	(323,1383704959,'ba3243507d211e4e766ae566b4460f8e06261a91','f3288c040889d5e5dcc06ce5a81b39279ef8f7e5',0),
	(324,1383704959,'ba3243507d211e4e766ae566b4460f8e06261a91','eb5523905c6ab86d9f63e674f285c1b3eb20405a',0),
	(325,1383704959,'ba3243507d211e4e766ae566b4460f8e06261a91','4487feb22c340ebb3bdff9e818709667d42b1651',0),
	(326,1383704959,'ba3243507d211e4e766ae566b4460f8e06261a91','dccfb8c0896d386505e57fae2bd3dbf0659e66b0',0),
	(327,1383704960,'ba3243507d211e4e766ae566b4460f8e06261a91','3d27bfcc22965c4f9709457690c7852fbba20396',0),
	(328,1383704960,'ba3243507d211e4e766ae566b4460f8e06261a91','ec0410d7e8e07bca74056f78647c1f8628c436e8',0),
	(329,1383704960,'ba3243507d211e4e766ae566b4460f8e06261a91','ed69157462b569eec473c66057b525e36985b6d2',0),
	(330,1383704960,'ba3243507d211e4e766ae566b4460f8e06261a91','c6aaa7a4d82dd62d7876dda4ac6ebd6210c866b8',0),
	(331,1383704960,'ba3243507d211e4e766ae566b4460f8e06261a91','b54551c43a148de1bfda4e629832bf68c3731855',0),
	(332,1383704960,'ba3243507d211e4e766ae566b4460f8e06261a91','cedc9d891a5cf51dd1ef4ecdada9ebc54bd3e964',0),
	(333,1383704960,'ba3243507d211e4e766ae566b4460f8e06261a91','2da40b1a0b1c9852665afbb651b0b2bcc2535f19',0),
	(334,1383704960,'ba3243507d211e4e766ae566b4460f8e06261a91','0f5bc5e93b54138fb9a20115a1ac1c070f85b82f',0),
	(335,1383704960,'ba3243507d211e4e766ae566b4460f8e06261a91','69fde0ccbbf3990cda36fae181bcd6e2914918ca',0),
	(336,1383704961,'ba3243507d211e4e766ae566b4460f8e06261a91','b893216a07e2886a1cb8a36808b34369372ee7b2',0),
	(337,1383704961,'ba3243507d211e4e766ae566b4460f8e06261a91','2d75fd92d968900bfe131db8f7234fd2a026bf61',0),
	(338,1383704961,'ba3243507d211e4e766ae566b4460f8e06261a91','335887891dbbd84c26ffcc24734704388fe7be24',0),
	(339,1383704961,'ba3243507d211e4e766ae566b4460f8e06261a91','dd17b17dd4bd54d29093039ae348f7ba38073f16',0),
	(340,1383704962,'ba3243507d211e4e766ae566b4460f8e06261a91','22372d170eb2c1a732b3b97e7652b32919e79428',0),
	(341,1383704962,'ba3243507d211e4e766ae566b4460f8e06261a91','ca677b962f9690eecebe047aa83751706f9f3a4f',0),
	(342,1383704962,'ba3243507d211e4e766ae566b4460f8e06261a91','5bdc66d2b81dab683b3d6ac402018b2fae895071',0),
	(343,1383704962,'ba3243507d211e4e766ae566b4460f8e06261a91','981d4aa77bea3318d5bf7f34081d19bd595120f8',0),
	(344,1383704963,'ba3243507d211e4e766ae566b4460f8e06261a91','84e52c5f4c088f0adc11261a73500d8159d0cb04',0),
	(345,1383704963,'ba3243507d211e4e766ae566b4460f8e06261a91','4e727012e201999afbdf53d0d043f836519787f8',0),
	(346,1383704982,'ba3243507d211e4e766ae566b4460f8e06261a91','665b35bb293353d72421adc8ad469bfd2333cb3a',0),
	(347,1383704982,'ba3243507d211e4e766ae566b4460f8e06261a91','0dd4c30c60fef2e5bba667c23a7e6b53f35bb0f6',0),
	(348,1383704982,'ba3243507d211e4e766ae566b4460f8e06261a91','f43f9577dd0354a9cba5f75c13aee79571d04142',0),
	(349,1383704982,'ba3243507d211e4e766ae566b4460f8e06261a91','9243a92894966c4104bb3d88a51ee455ee8840ad',0),
	(350,1383704982,'ba3243507d211e4e766ae566b4460f8e06261a91','4a63703ab3e0c160e7b9ec48cbcc930db1fd2195',0),
	(351,1383704998,'ba3243507d211e4e766ae566b4460f8e06261a91','6f2ea007460dae976f76a5e99d1581f56ebe6917',0),
	(352,1383705001,'ba3243507d211e4e766ae566b4460f8e06261a91','0a94db063dbe60a018ae0d2d3de1b740d2fbce14',0),
	(353,1383705002,'ba3243507d211e4e766ae566b4460f8e06261a91','cae1df68803788c2a98a1160ec920e4b62288925',0);

/*!40000 ALTER TABLE `exp_security_hashes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_sessions`;

CREATE TABLE `exp_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `member_id` int(10) NOT NULL DEFAULT '0',
  `admin_sess` tinyint(1) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `sess_start` int(10) unsigned NOT NULL DEFAULT '0',
  `fingerprint` varchar(40) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`),
  KEY `member_id` (`member_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_sessions` WRITE;
/*!40000 ALTER TABLE `exp_sessions` DISABLE KEYS */;

INSERT INTO `exp_sessions` (`session_id`, `member_id`, `admin_sess`, `ip_address`, `user_agent`, `sess_start`, `fingerprint`, `last_activity`)
VALUES
	('ba3243507d211e4e766ae566b4460f8e06261a91',1,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36',1383704732,'ebfe21e2162bab2098f61c1a4193a2e4',1383705002);

/*!40000 ALTER TABLE `exp_sessions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_sites
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_sites`;

CREATE TABLE `exp_sites` (
  `site_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `site_label` varchar(100) NOT NULL DEFAULT '',
  `site_name` varchar(50) NOT NULL DEFAULT '',
  `site_description` text,
  `site_system_preferences` mediumtext NOT NULL,
  `site_mailinglist_preferences` text NOT NULL,
  `site_member_preferences` text NOT NULL,
  `site_template_preferences` text NOT NULL,
  `site_channel_preferences` text NOT NULL,
  `site_bootstrap_checksums` text NOT NULL,
  `site_pages` text NOT NULL,
  PRIMARY KEY (`site_id`),
  KEY `site_name` (`site_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_sites` WRITE;
/*!40000 ALTER TABLE `exp_sites` DISABLE KEYS */;

INSERT INTO `exp_sites` (`site_id`, `site_label`, `site_name`, `site_description`, `site_system_preferences`, `site_mailinglist_preferences`, `site_member_preferences`, `site_template_preferences`, `site_channel_preferences`, `site_bootstrap_checksums`, `site_pages`)
VALUES
	(1,'EE Site','default_site',NULL,'YTo4OTp7czoxMDoic2l0ZV9pbmRleCI7czowOiIiO3M6ODoic2l0ZV91cmwiO3M6MTg6Imh0dHA6Ly9lZWJhc2UuZGV2LyI7czoxNjoidGhlbWVfZm9sZGVyX3VybCI7czoyNToiaHR0cDovL2VlYmFzZS5kZXYvdGhlbWVzLyI7czoxNToid2VibWFzdGVyX2VtYWlsIjtzOjI1OiJwcm9qZWN0c0Bjb25mbHV4Z3JvdXAuY29tIjtzOjE0OiJ3ZWJtYXN0ZXJfbmFtZSI7czowOiIiO3M6MjA6ImNoYW5uZWxfbm9tZW5jbGF0dXJlIjtzOjc6ImNoYW5uZWwiO3M6MTA6Im1heF9jYWNoZXMiO3M6MzoiMTUwIjtzOjExOiJjYXB0Y2hhX3VybCI7czozNDoiaHR0cDovL2VlYmFzZS5kZXYvaW1hZ2VzL2NhcHRjaGFzLyI7czoxMjoiY2FwdGNoYV9wYXRoIjtzOjUzOiIvVXNlcnMvamdpbWJlbC9TaXRlcy9lZWJhc2UvaHR0cGRvY3MvaW1hZ2VzL2NhcHRjaGFzLyI7czoxMjoiY2FwdGNoYV9mb250IjtzOjE6InkiO3M6MTI6ImNhcHRjaGFfcmFuZCI7czoxOiJ5IjtzOjIzOiJjYXB0Y2hhX3JlcXVpcmVfbWVtYmVycyI7czoxOiJuIjtzOjE3OiJlbmFibGVfZGJfY2FjaGluZyI7czoxOiJuIjtzOjE4OiJlbmFibGVfc3FsX2NhY2hpbmciO3M6MToibiI7czoxODoiZm9yY2VfcXVlcnlfc3RyaW5nIjtzOjE6Im4iO3M6MTM6InNob3dfcHJvZmlsZXIiO3M6MToibiI7czoxODoidGVtcGxhdGVfZGVidWdnaW5nIjtzOjE6Im4iO3M6MTU6ImluY2x1ZGVfc2Vjb25kcyI7czoxOiJuIjtzOjEzOiJjb29raWVfZG9tYWluIjtzOjA6IiI7czoxMToiY29va2llX3BhdGgiO3M6MDoiIjtzOjE3OiJ1c2VyX3Nlc3Npb25fdHlwZSI7czoxOiJjIjtzOjE4OiJhZG1pbl9zZXNzaW9uX3R5cGUiO3M6MjoiY3MiO3M6MjE6ImFsbG93X3VzZXJuYW1lX2NoYW5nZSI7czoxOiJ5IjtzOjE4OiJhbGxvd19tdWx0aV9sb2dpbnMiO3M6MToieSI7czoxNjoicGFzc3dvcmRfbG9ja291dCI7czoxOiJ5IjtzOjI1OiJwYXNzd29yZF9sb2Nrb3V0X2ludGVydmFsIjtzOjE6IjEiO3M6MjA6InJlcXVpcmVfaXBfZm9yX2xvZ2luIjtzOjE6InkiO3M6MjI6InJlcXVpcmVfaXBfZm9yX3Bvc3RpbmciO3M6MToieSI7czoyNDoicmVxdWlyZV9zZWN1cmVfcGFzc3dvcmRzIjtzOjE6Im4iO3M6MTk6ImFsbG93X2RpY3Rpb25hcnlfcHciO3M6MToieSI7czoyMzoibmFtZV9vZl9kaWN0aW9uYXJ5X2ZpbGUiO3M6MDoiIjtzOjE3OiJ4c3NfY2xlYW5fdXBsb2FkcyI7czoxOiJ5IjtzOjE1OiJyZWRpcmVjdF9tZXRob2QiO3M6ODoicmVkaXJlY3QiO3M6OToiZGVmdF9sYW5nIjtzOjc6ImVuZ2xpc2giO3M6ODoieG1sX2xhbmciO3M6MjoiZW4iO3M6MTI6InNlbmRfaGVhZGVycyI7czoxOiJ5IjtzOjExOiJnemlwX291dHB1dCI7czoxOiJuIjtzOjEzOiJsb2dfcmVmZXJyZXJzIjtzOjE6Im4iO3M6MTM6Im1heF9yZWZlcnJlcnMiO3M6MzoiNTAwIjtzOjExOiJ0aW1lX2Zvcm1hdCI7czoyOiJ1cyI7czoxMzoic2VydmVyX29mZnNldCI7czowOiIiO3M6MTY6ImRheWxpZ2h0X3NhdmluZ3MiO3M6MToibiI7czoyMToiZGVmYXVsdF9zaXRlX3RpbWV6b25lIjtzOjM6IlVNNSI7czoxMzoibWFpbF9wcm90b2NvbCI7czo0OiJtYWlsIjtzOjExOiJzbXRwX3NlcnZlciI7czowOiIiO3M6MTM6InNtdHBfdXNlcm5hbWUiO3M6MDoiIjtzOjEzOiJzbXRwX3Bhc3N3b3JkIjtzOjA6IiI7czoxMToiZW1haWxfZGVidWciO3M6MToibiI7czoxMzoiZW1haWxfY2hhcnNldCI7czo1OiJ1dGYtOCI7czoxNToiZW1haWxfYmF0Y2htb2RlIjtzOjE6Im4iO3M6MTY6ImVtYWlsX2JhdGNoX3NpemUiO3M6MDoiIjtzOjExOiJtYWlsX2Zvcm1hdCI7czo1OiJwbGFpbiI7czo5OiJ3b3JkX3dyYXAiO3M6MToieSI7czoyMjoiZW1haWxfY29uc29sZV90aW1lbG9jayI7czoxOiI1IjtzOjIyOiJsb2dfZW1haWxfY29uc29sZV9tc2dzIjtzOjE6InkiO3M6ODoiY3BfdGhlbWUiO3M6ODoic2Fzc3lfY3AiO3M6MjE6ImVtYWlsX21vZHVsZV9jYXB0Y2hhcyI7czoxOiJuIjtzOjE2OiJsb2dfc2VhcmNoX3Rlcm1zIjtzOjE6InkiO3M6MTI6InNlY3VyZV9mb3JtcyI7czoxOiJ5IjtzOjE5OiJkZW55X2R1cGxpY2F0ZV9kYXRhIjtzOjE6InkiO3M6MjQ6InJlZGlyZWN0X3N1Ym1pdHRlZF9saW5rcyI7czoxOiJuIjtzOjE2OiJlbmFibGVfY2Vuc29yaW5nIjtzOjE6Im4iO3M6MTQ6ImNlbnNvcmVkX3dvcmRzIjtzOjA6IiI7czoxODoiY2Vuc29yX3JlcGxhY2VtZW50IjtzOjA6IiI7czoxMDoiYmFubmVkX2lwcyI7czowOiIiO3M6MTM6ImJhbm5lZF9lbWFpbHMiO3M6MDoiIjtzOjE2OiJiYW5uZWRfdXNlcm5hbWVzIjtzOjA6IiI7czoxOToiYmFubmVkX3NjcmVlbl9uYW1lcyI7czowOiIiO3M6MTA6ImJhbl9hY3Rpb24iO3M6ODoicmVzdHJpY3QiO3M6MTE6ImJhbl9tZXNzYWdlIjtzOjM0OiJUaGlzIHNpdGUgaXMgY3VycmVudGx5IHVuYXZhaWxhYmxlIjtzOjE1OiJiYW5fZGVzdGluYXRpb24iO3M6MjE6Imh0dHA6Ly93d3cueWFob28uY29tLyI7czoxNjoiZW5hYmxlX2Vtb3RpY29ucyI7czoxOiJ5IjtzOjEyOiJlbW90aWNvbl91cmwiO3M6MzM6Imh0dHA6Ly9lZWJhc2UuZGV2L2ltYWdlcy9zbWlsZXlzLyI7czoxOToicmVjb3VudF9iYXRjaF90b3RhbCI7czo0OiIxMDAwIjtzOjE3OiJuZXdfdmVyc2lvbl9jaGVjayI7czoxOiJuIjtzOjE3OiJlbmFibGVfdGhyb3R0bGluZyI7czoxOiJuIjtzOjE3OiJiYW5pc2hfbWFza2VkX2lwcyI7czoxOiJ5IjtzOjE0OiJtYXhfcGFnZV9sb2FkcyI7czoyOiIxMCI7czoxMzoidGltZV9pbnRlcnZhbCI7czoxOiI4IjtzOjEyOiJsb2Nrb3V0X3RpbWUiO3M6MjoiMzAiO3M6MTU6ImJhbmlzaG1lbnRfdHlwZSI7czo3OiJtZXNzYWdlIjtzOjE0OiJiYW5pc2htZW50X3VybCI7czowOiIiO3M6MTg6ImJhbmlzaG1lbnRfbWVzc2FnZSI7czo1MDoiWW91IGhhdmUgZXhjZWVkZWQgdGhlIGFsbG93ZWQgcGFnZSBsb2FkIGZyZXF1ZW5jeS4iO3M6MTc6ImVuYWJsZV9zZWFyY2hfbG9nIjtzOjE6InkiO3M6MTk6Im1heF9sb2dnZWRfc2VhcmNoZXMiO3M6MzoiNTAwIjtzOjE3OiJ0aGVtZV9mb2xkZXJfcGF0aCI7czo0NDoiL1VzZXJzL2pnaW1iZWwvU2l0ZXMvZWViYXNlL2h0dHBkb2NzL3RoZW1lcy8iO3M6MTA6ImlzX3NpdGVfb24iO3M6MToieSI7czoxMToicnRlX2VuYWJsZWQiO3M6MToieSI7czoyMjoicnRlX2RlZmF1bHRfdG9vbHNldF9pZCI7czoxOiIxIjt9','YTozOntzOjE5OiJtYWlsaW5nbGlzdF9lbmFibGVkIjtzOjE6InkiO3M6MTg6Im1haWxpbmdsaXN0X25vdGlmeSI7czoxOiJuIjtzOjI1OiJtYWlsaW5nbGlzdF9ub3RpZnlfZW1haWxzIjtzOjA6IiI7fQ==','YTo0NDp7czoxMDoidW5fbWluX2xlbiI7czoxOiI0IjtzOjEwOiJwd19taW5fbGVuIjtzOjE6IjUiO3M6MjU6ImFsbG93X21lbWJlcl9yZWdpc3RyYXRpb24iO3M6MToibiI7czoyNToiYWxsb3dfbWVtYmVyX2xvY2FsaXphdGlvbiI7czoxOiJ5IjtzOjE4OiJyZXFfbWJyX2FjdGl2YXRpb24iO3M6NToiZW1haWwiO3M6MjM6Im5ld19tZW1iZXJfbm90aWZpY2F0aW9uIjtzOjE6Im4iO3M6MjM6Im1icl9ub3RpZmljYXRpb25fZW1haWxzIjtzOjA6IiI7czoyNDoicmVxdWlyZV90ZXJtc19vZl9zZXJ2aWNlIjtzOjE6InkiO3M6MjI6InVzZV9tZW1iZXJzaGlwX2NhcHRjaGEiO3M6MToibiI7czoyMDoiZGVmYXVsdF9tZW1iZXJfZ3JvdXAiO3M6MToiNSI7czoxNToicHJvZmlsZV90cmlnZ2VyIjtzOjY6Im1lbWJlciI7czoxMjoibWVtYmVyX3RoZW1lIjtzOjc6ImRlZmF1bHQiO3M6MTQ6ImVuYWJsZV9hdmF0YXJzIjtzOjE6InkiO3M6MjA6ImFsbG93X2F2YXRhcl91cGxvYWRzIjtzOjE6Im4iO3M6MTA6ImF2YXRhcl91cmwiO3M6MzM6Imh0dHA6Ly9lZWJhc2UuZGV2L2ltYWdlcy9hdmF0YXJzLyI7czoxMToiYXZhdGFyX3BhdGgiO3M6NTI6Ii9Vc2Vycy9qZ2ltYmVsL1NpdGVzL2VlYmFzZS9odHRwZG9jcy9pbWFnZXMvYXZhdGFycy8iO3M6MTY6ImF2YXRhcl9tYXhfd2lkdGgiO3M6MzoiMTAwIjtzOjE3OiJhdmF0YXJfbWF4X2hlaWdodCI7czozOiIxMDAiO3M6MTM6ImF2YXRhcl9tYXhfa2IiO3M6MjoiNTAiO3M6MTM6ImVuYWJsZV9waG90b3MiO3M6MToibiI7czo5OiJwaG90b191cmwiO3M6Mzk6Imh0dHA6Ly9lZWJhc2UuZGV2L2ltYWdlcy9tZW1iZXJfcGhvdG9zLyI7czoxMDoicGhvdG9fcGF0aCI7czo1ODoiL1VzZXJzL2pnaW1iZWwvU2l0ZXMvZWViYXNlL2h0dHBkb2NzL2ltYWdlcy9tZW1iZXJfcGhvdG9zLyI7czoxNToicGhvdG9fbWF4X3dpZHRoIjtzOjM6IjEwMCI7czoxNjoicGhvdG9fbWF4X2hlaWdodCI7czozOiIxMDAiO3M6MTI6InBob3RvX21heF9rYiI7czoyOiI1MCI7czoxNjoiYWxsb3dfc2lnbmF0dXJlcyI7czoxOiJ5IjtzOjEzOiJzaWdfbWF4bGVuZ3RoIjtzOjM6IjUwMCI7czoyMToic2lnX2FsbG93X2ltZ19ob3RsaW5rIjtzOjE6Im4iO3M6MjA6InNpZ19hbGxvd19pbWdfdXBsb2FkIjtzOjE6Im4iO3M6MTE6InNpZ19pbWdfdXJsIjtzOjQ3OiJodHRwOi8vZWViYXNlLmRldi9pbWFnZXMvc2lnbmF0dXJlX2F0dGFjaG1lbnRzLyI7czoxMjoic2lnX2ltZ19wYXRoIjtzOjY2OiIvVXNlcnMvamdpbWJlbC9TaXRlcy9lZWJhc2UvaHR0cGRvY3MvaW1hZ2VzL3NpZ25hdHVyZV9hdHRhY2htZW50cy8iO3M6MTc6InNpZ19pbWdfbWF4X3dpZHRoIjtzOjM6IjQ4MCI7czoxODoic2lnX2ltZ19tYXhfaGVpZ2h0IjtzOjI6IjgwIjtzOjE0OiJzaWdfaW1nX21heF9rYiI7czoyOiIzMCI7czoxOToicHJ2X21zZ191cGxvYWRfcGF0aCI7czo1OToiL1VzZXJzL2pnaW1iZWwvU2l0ZXMvZWViYXNlL2h0dHBkb2NzL2ltYWdlcy9wbV9hdHRhY2htZW50cy8iO3M6MjM6InBydl9tc2dfbWF4X2F0dGFjaG1lbnRzIjtzOjE6IjMiO3M6MjI6InBydl9tc2dfYXR0YWNoX21heHNpemUiO3M6MzoiMjUwIjtzOjIwOiJwcnZfbXNnX2F0dGFjaF90b3RhbCI7czozOiIxMDAiO3M6MTk6InBydl9tc2dfaHRtbF9mb3JtYXQiO3M6NDoic2FmZSI7czoxODoicHJ2X21zZ19hdXRvX2xpbmtzIjtzOjE6InkiO3M6MTc6InBydl9tc2dfbWF4X2NoYXJzIjtzOjQ6IjYwMDAiO3M6MTk6Im1lbWJlcmxpc3Rfb3JkZXJfYnkiO3M6MTE6InRvdGFsX3Bvc3RzIjtzOjIxOiJtZW1iZXJsaXN0X3NvcnRfb3JkZXIiO3M6NDoiZGVzYyI7czoyMDoibWVtYmVybGlzdF9yb3dfbGltaXQiO3M6MjoiMjAiO30=','YTo2OntzOjExOiJzdHJpY3RfdXJscyI7czoxOiJuIjtzOjg6InNpdGVfNDA0IjtzOjA6IiI7czoxOToic2F2ZV90bXBsX3JldmlzaW9ucyI7czoxOiJuIjtzOjE4OiJtYXhfdG1wbF9yZXZpc2lvbnMiO3M6MToiNSI7czoxNToic2F2ZV90bXBsX2ZpbGVzIjtzOjE6Im4iO3M6MTg6InRtcGxfZmlsZV9iYXNlcGF0aCI7czo3MToiL1VzZXJzL2pnaW1iZWwvU2l0ZXMvZWViYXNlL2h0dHBkb2NzL3N5c3RlbS9leHByZXNzaW9uZW5naW5lL3RlbXBsYXRlcy8iO30=','YTo5OntzOjIxOiJpbWFnZV9yZXNpemVfcHJvdG9jb2wiO3M6MzoiZ2QyIjtzOjE4OiJpbWFnZV9saWJyYXJ5X3BhdGgiO3M6MDoiIjtzOjE2OiJ0aHVtYm5haWxfcHJlZml4IjtzOjU6InRodW1iIjtzOjE0OiJ3b3JkX3NlcGFyYXRvciI7czo0OiJkYXNoIjtzOjE3OiJ1c2VfY2F0ZWdvcnlfbmFtZSI7czoxOiJuIjtzOjIyOiJyZXNlcnZlZF9jYXRlZ29yeV93b3JkIjtzOjg6ImNhdGVnb3J5IjtzOjIzOiJhdXRvX2NvbnZlcnRfaGlnaF9hc2NpaSI7czoxOiJuIjtzOjIyOiJuZXdfcG9zdHNfY2xlYXJfY2FjaGVzIjtzOjE6InkiO3M6MjM6ImF1dG9fYXNzaWduX2NhdF9wYXJlbnRzIjtzOjE6InkiO30=','YTozOntzOjc6ImVtYWlsZWQiO2E6MDp7fXM6NTE6Ii9Vc2Vycy9qZXJlbXlnaW1iZWwvU2l0ZXMvZWViYXNlL2h0dHBkb2NzL2luZGV4LnBocCI7czozMjoiYTJlYmE4YWQwMjc4NzAwZGQ0ZTljNjQ2MTNlMjJlMmYiO3M6NTE6Ii9Vc2Vycy9qZXJlbXlnaW1iZWwvU2l0ZXMvY2xpZW50X2JiYy9SZXBvL2luZGV4LnBocCI7czozMjoiYTJlYmE4YWQwMjc4NzAwZGQ0ZTljNjQ2MTNlMjJlMmYiO30=','YToxOntpOjE7YToxOntzOjM6InVybCI7czoxODoiaHR0cDovL2VlYmFzZS5kZXYvIjt9fQ==');

/*!40000 ALTER TABLE `exp_sites` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_snippets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_snippets`;

CREATE TABLE `exp_snippets` (
  `snippet_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) NOT NULL,
  `snippet_name` varchar(75) NOT NULL,
  `snippet_contents` text,
  PRIMARY KEY (`snippet_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_specialty_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_specialty_templates`;

CREATE TABLE `exp_specialty_templates` (
  `template_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `enable_template` char(1) NOT NULL DEFAULT 'y',
  `template_name` varchar(50) NOT NULL,
  `data_title` varchar(80) NOT NULL,
  `template_data` text NOT NULL,
  PRIMARY KEY (`template_id`),
  KEY `template_name` (`template_name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_specialty_templates` WRITE;
/*!40000 ALTER TABLE `exp_specialty_templates` DISABLE KEYS */;

INSERT INTO `exp_specialty_templates` (`template_id`, `site_id`, `enable_template`, `template_name`, `data_title`, `template_data`)
VALUES
	(1,1,'y','offline_template','','<html>\n<head>\n\n<title>System Offline</title>\n\n<style type=\"text/css\">\n\nbody { \nbackground-color:	#ffffff; \nmargin:				50px; \nfont-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;\nfont-size:			11px;\ncolor:				#000;\nbackground-color:	#fff;\n}\n\na {\nfont-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;\nfont-weight:		bold;\nletter-spacing:		.09em;\ntext-decoration:	none;\ncolor:			  #330099;\nbackground-color:	transparent;\n}\n  \na:visited {\ncolor:				#330099;\nbackground-color:	transparent;\n}\n\na:hover {\ncolor:				#000;\ntext-decoration:	underline;\nbackground-color:	transparent;\n}\n\n#content  {\nborder:				#999999 1px solid;\npadding:			22px 25px 14px 25px;\n}\n\nh1 {\nfont-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;\nfont-weight:		bold;\nfont-size:			14px;\ncolor:				#000;\nmargin-top: 		0;\nmargin-bottom:		14px;\n}\n\np {\nfont-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;\nfont-size: 			12px;\nfont-weight: 		normal;\nmargin-top: 		12px;\nmargin-bottom: 		14px;\ncolor: 				#000;\n}\n</style>\n\n</head>\n\n<body>\n\n<div id=\"content\">\n\n<h1>System Offline</h1>\n\n<p>This site is currently offline</p>\n\n</div>\n\n</body>\n\n</html>'),
	(2,1,'y','message_template','','<html>\n<head>\n\n<title>{title}</title>\n\n<meta http-equiv=\'content-type\' content=\'text/html; charset={charset}\' />\n\n{meta_refresh}\n\n<style type=\"text/css\">\n\nbody { \nbackground-color:	#ffffff; \nmargin:				50px; \nfont-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;\nfont-size:			11px;\ncolor:				#000;\nbackground-color:	#fff;\n}\n\na {\nfont-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;\nletter-spacing:		.09em;\ntext-decoration:	none;\ncolor:			  #330099;\nbackground-color:	transparent;\n}\n  \na:visited {\ncolor:				#330099;\nbackground-color:	transparent;\n}\n\na:active {\ncolor:				#ccc;\nbackground-color:	transparent;\n}\n\na:hover {\ncolor:				#000;\ntext-decoration:	underline;\nbackground-color:	transparent;\n}\n\n#content  {\nborder:				#000 1px solid;\nbackground-color: 	#DEDFE3;\npadding:			22px 25px 14px 25px;\n}\n\nh1 {\nfont-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;\nfont-weight:		bold;\nfont-size:			14px;\ncolor:				#000;\nmargin-top: 		0;\nmargin-bottom:		14px;\n}\n\np {\nfont-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;\nfont-size: 			12px;\nfont-weight: 		normal;\nmargin-top: 		12px;\nmargin-bottom: 		14px;\ncolor: 				#000;\n}\n\nul {\nmargin-bottom: 		16px;\n}\n\nli {\nlist-style:			square;\nfont-family:		Verdana, Arial, Tahoma, Trebuchet MS, Sans-serif;\nfont-size: 			12px;\nfont-weight: 		normal;\nmargin-top: 		8px;\nmargin-bottom: 		8px;\ncolor: 				#000;\n}\n\n</style>\n\n</head>\n\n<body>\n\n<div id=\"content\">\n\n<h1>{heading}</h1>\n\n{content}\n\n<p>{link}</p>\n\n</div>\n\n</body>\n\n</html>'),
	(3,1,'y','admin_notify_reg','Notification of new member registration','New member registration site: {site_name}\n\nScreen name: {name}\nUser name: {username}\nEmail: {email}\n\nYour control panel URL: {control_panel_url}'),
	(4,1,'y','admin_notify_entry','A new channel entry has been posted','A new entry has been posted in the following channel:\n{channel_name}\n\nThe title of the entry is:\n{entry_title}\n\nPosted by: {name}\nEmail: {email}\n\nTo read the entry please visit: \n{entry_url}\n'),
	(5,1,'y','admin_notify_mailinglist','Someone has subscribed to your mailing list','A new mailing list subscription has been accepted.\n\nEmail Address: {email}\nMailing List: {mailing_list}'),
	(6,1,'y','admin_notify_comment','You have just received a comment','You have just received a comment for the following channel:\n{channel_name}\n\nThe title of the entry is:\n{entry_title}\n\nLocated at: \n{comment_url}\n\nPosted by: {name}\nEmail: {email}\nURL: {url}\nLocation: {location}\n\n{comment}'),
	(7,1,'y','mbr_activation_instructions','Enclosed is your activation code','Thank you for your new member registration.\n\nTo activate your new account, please visit the following URL:\n\n{unwrap}{activation_url}{/unwrap}\n\nThank You!\n\n{site_name}\n\n{site_url}'),
	(8,1,'y','forgot_password_instructions','Login information','{name},\n\nTo reset your password, please go to the following page:\n\n{reset_url}\n\nIf you do not wish to reset your password, ignore this message. It will expire in 24 hours.\n\n{site_name}\n{site_url}'),
	(10,1,'y','validated_member_notify','Your membership account has been activated','{name},\n\nYour membership account has been activated and is ready for use.\n\nThank You!\n\n{site_name}\n{site_url}'),
	(11,1,'y','decline_member_validation','Your membership account has been declined','{name},\n\nWe\'re sorry but our staff has decided not to validate your membership.\n\n{site_name}\n{site_url}'),
	(12,1,'y','mailinglist_activation_instructions','Email Confirmation','Thank you for joining the \"{mailing_list}\" mailing list!\n\nPlease click the link below to confirm your email.\n\nIf you do not want to be added to our list, ignore this email.\n\n{unwrap}{activation_url}{/unwrap}\n\nThank You!\n\n{site_name}'),
	(13,1,'y','comment_notification','Someone just responded to your comment','{name_of_commenter} just responded to the entry you subscribed to at:\n{channel_name}\n\nThe title of the entry is:\n{entry_title}\n\nYou can see the comment at the following URL:\n{comment_url}\n\n{comment}\n\nTo stop receiving notifications for this comment, click here:\n{notification_removal_url}'),
	(14,1,'y','comments_opened_notification','New comments have been added','Responses have been added to the entry you subscribed to at:\n{channel_name}\n\nThe title of the entry is:\n{entry_title}\n\nYou can see the comments at the following URL:\n{comment_url}\n\n{comments}\n{comment} \n{/comments}\n\nTo stop receiving notifications for this entry, click here:\n{notification_removal_url}'),
	(15,1,'y','private_message_notification','Someone has sent you a Private Message','\n{recipient_name},\n\n{sender_name} has just sent you a Private Message titled ‘{message_subject}’.\n\nYou can see the Private Message by logging in and viewing your inbox at:\n{site_url}\n\nContent:\n\n{message_content}\n\nTo stop receiving notifications of Private Messages, turn the option off in your Email Settings.\n\n{site_name}\n{site_url}'),
	(16,1,'y','pm_inbox_full','Your private message mailbox is full','{recipient_name},\n\n{sender_name} has just attempted to send you a Private Message,\nbut your inbox is full, exceeding the maximum of {pm_storage_limit}.\n\nPlease log in and remove unwanted messages from your inbox at:\n{site_url}');

/*!40000 ALTER TABLE `exp_specialty_templates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_stats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_stats`;

CREATE TABLE `exp_stats` (
  `stat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `total_members` mediumint(7) NOT NULL DEFAULT '0',
  `recent_member_id` int(10) NOT NULL DEFAULT '0',
  `recent_member` varchar(50) NOT NULL,
  `total_entries` mediumint(8) NOT NULL DEFAULT '0',
  `total_forum_topics` mediumint(8) NOT NULL DEFAULT '0',
  `total_forum_posts` mediumint(8) NOT NULL DEFAULT '0',
  `total_comments` mediumint(8) NOT NULL DEFAULT '0',
  `last_entry_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_forum_post_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_comment_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_visitor_date` int(10) unsigned NOT NULL DEFAULT '0',
  `most_visitors` mediumint(7) NOT NULL DEFAULT '0',
  `most_visitor_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_cache_clear` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`stat_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_stats` WRITE;
/*!40000 ALTER TABLE `exp_stats` DISABLE KEYS */;

INSERT INTO `exp_stats` (`stat_id`, `site_id`, `total_members`, `recent_member_id`, `recent_member`, `total_entries`, `total_forum_topics`, `total_forum_posts`, `total_comments`, `last_entry_date`, `last_forum_post_date`, `last_comment_date`, `last_visitor_date`, `most_visitors`, `most_visitor_date`, `last_cache_clear`)
VALUES
	(1,1,1,1,'confluxgroup',1,0,0,0,1338421576,0,0,0,0,0,1384309528);

/*!40000 ALTER TABLE `exp_stats` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_status_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_status_groups`;

CREATE TABLE `exp_status_groups` (
  `group_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_name` varchar(50) NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_status_groups` WRITE;
/*!40000 ALTER TABLE `exp_status_groups` DISABLE KEYS */;

INSERT INTO `exp_status_groups` (`group_id`, `site_id`, `group_name`)
VALUES
	(1,1,'Statuses');

/*!40000 ALTER TABLE `exp_status_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_status_no_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_status_no_access`;

CREATE TABLE `exp_status_no_access` (
  `status_id` int(6) unsigned NOT NULL,
  `member_group` smallint(4) unsigned NOT NULL,
  PRIMARY KEY (`status_id`,`member_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_statuses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_statuses`;

CREATE TABLE `exp_statuses` (
  `status_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_id` int(4) unsigned NOT NULL,
  `status` varchar(50) NOT NULL,
  `status_order` int(3) unsigned NOT NULL,
  `highlight` varchar(30) NOT NULL,
  PRIMARY KEY (`status_id`),
  KEY `group_id` (`group_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_statuses` WRITE;
/*!40000 ALTER TABLE `exp_statuses` DISABLE KEYS */;

INSERT INTO `exp_statuses` (`status_id`, `site_id`, `group_id`, `status`, `status_order`, `highlight`)
VALUES
	(1,1,1,'open',1,'009933'),
	(2,1,1,'closed',2,'990000');

/*!40000 ALTER TABLE `exp_statuses` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_template_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_template_groups`;

CREATE TABLE `exp_template_groups` (
  `group_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_name` varchar(50) NOT NULL,
  `group_order` int(3) unsigned NOT NULL,
  `is_site_default` char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`group_id`),
  KEY `site_id` (`site_id`),
  KEY `group_name_idx` (`group_name`),
  KEY `group_order_idx` (`group_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_template_groups` WRITE;
/*!40000 ALTER TABLE `exp_template_groups` DISABLE KEYS */;

INSERT INTO `exp_template_groups` (`group_id`, `site_id`, `group_name`, `group_order`, `is_site_default`)
VALUES
	(1,1,'site',1,'y');

/*!40000 ALTER TABLE `exp_template_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_template_member_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_template_member_groups`;

CREATE TABLE `exp_template_member_groups` (
  `group_id` smallint(4) unsigned NOT NULL,
  `template_group_id` mediumint(5) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`template_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_template_no_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_template_no_access`;

CREATE TABLE `exp_template_no_access` (
  `template_id` int(6) unsigned NOT NULL,
  `member_group` smallint(4) unsigned NOT NULL,
  PRIMARY KEY (`template_id`,`member_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_templates`;

CREATE TABLE `exp_templates` (
  `template_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `group_id` int(6) unsigned NOT NULL,
  `template_name` varchar(50) NOT NULL,
  `save_template_file` char(1) NOT NULL DEFAULT 'n',
  `template_type` varchar(16) NOT NULL DEFAULT 'webpage',
  `template_data` mediumtext,
  `template_notes` text,
  `edit_date` int(10) NOT NULL DEFAULT '0',
  `last_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cache` char(1) NOT NULL DEFAULT 'n',
  `refresh` int(6) unsigned NOT NULL DEFAULT '0',
  `no_auth_bounce` varchar(50) NOT NULL DEFAULT '',
  `enable_http_auth` char(1) NOT NULL DEFAULT 'n',
  `allow_php` char(1) NOT NULL DEFAULT 'n',
  `php_parse_location` char(1) NOT NULL DEFAULT 'o',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`template_id`),
  KEY `group_id` (`group_id`),
  KEY `template_name` (`template_name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `exp_templates` WRITE;
/*!40000 ALTER TABLE `exp_templates` DISABLE KEYS */;

INSERT INTO `exp_templates` (`template_id`, `site_id`, `group_id`, `template_name`, `save_template_file`, `template_type`, `template_data`, `template_notes`, `edit_date`, `last_author_id`, `cache`, `refresh`, `no_auth_bounce`, `enable_http_auth`, `allow_php`, `php_parse_location`, `hits`)
VALUES
	(1,1,1,'index','y','webpage','',NULL,1338422955,1,'n',0,'','n','n','o',0);

/*!40000 ALTER TABLE `exp_templates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_throttle
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_throttle`;

CREATE TABLE `exp_throttle` (
  `throttle_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL,
  `locked_out` char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`throttle_id`),
  KEY `ip_address` (`ip_address`),
  KEY `last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_update_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_update_log`;

CREATE TABLE `exp_update_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned DEFAULT NULL,
  `message` text,
  `method` varchar(100) DEFAULT NULL,
  `line` int(10) unsigned DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `exp_update_log` WRITE;
/*!40000 ALTER TABLE `exp_update_log` DISABLE KEYS */;

INSERT INTO `exp_update_log` (`log_id`, `timestamp`, `message`, `method`, `line`, `file`)
VALUES
	(1,1373053314,'Could not add column \'exp_sessions.fingerprint\'. Column already exists.','Smartforge::add_column',211,'/Users/jeremygimbel/Sites/eebase/httpdocs/system/expressionengine/cache/updater/1373053291_88407/system/installer/updates/ud_260.php'),
	(2,1373053314,'Could not add column \'exp_sessions.sess_start\'. Column already exists.','Smartforge::add_column',211,'/Users/jeremygimbel/Sites/eebase/httpdocs/system/expressionengine/cache/updater/1373053291_88407/system/installer/updates/ud_260.php'),
	(3,1373053314,'Could not drop column \'exp_category_groups.is_user_blog\'. Column does not exist.','Smartforge::drop_column',892,'/Users/jeremygimbel/Sites/eebase/httpdocs/system/expressionengine/cache/updater/1373053291_88407/system/installer/updates/ud_260.php'),
	(4,1373053314,'Smartforge::drop_column failed. Table \'exp_forum_topics\' does not exist.','Smartforge::drop_column',892,'/Users/jeremygimbel/Sites/eebase/httpdocs/system/expressionengine/cache/updater/1373053291_88407/system/installer/updates/ud_260.php'),
	(5,1373053314,'Could not drop column \'exp_upload_prefs.is_user_blog\'. Column does not exist.','Smartforge::drop_column',892,'/Users/jeremygimbel/Sites/eebase/httpdocs/system/expressionengine/cache/updater/1373053291_88407/system/installer/updates/ud_260.php');

/*!40000 ALTER TABLE `exp_update_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exp_upload_no_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_upload_no_access`;

CREATE TABLE `exp_upload_no_access` (
  `upload_id` int(6) unsigned NOT NULL,
  `upload_loc` varchar(3) NOT NULL,
  `member_group` smallint(4) unsigned NOT NULL,
  PRIMARY KEY (`upload_id`,`member_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table exp_upload_prefs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exp_upload_prefs`;

CREATE TABLE `exp_upload_prefs` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(4) unsigned NOT NULL DEFAULT '1',
  `name` varchar(50) NOT NULL,
  `server_path` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL,
  `allowed_types` varchar(3) NOT NULL DEFAULT 'img',
  `max_size` varchar(16) DEFAULT NULL,
  `max_height` varchar(6) DEFAULT NULL,
  `max_width` varchar(6) DEFAULT NULL,
  `properties` varchar(120) DEFAULT NULL,
  `pre_format` varchar(120) DEFAULT NULL,
  `post_format` varchar(120) DEFAULT NULL,
  `file_properties` varchar(120) DEFAULT NULL,
  `file_pre_format` varchar(120) DEFAULT NULL,
  `file_post_format` varchar(120) DEFAULT NULL,
  `cat_group` varchar(255) DEFAULT NULL,
  `batch_location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
