--
-- Table structure for table `t_form_admit`
--

CREATE TABLE IF NOT EXISTS `t_form_admit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `admit_to_ward` varchar(255) DEFAULT NULL,
  `admit_to_bed` varchar(255) DEFAULT NULL,
  `admit_date` date DEFAULT NULL,
  `discharge_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `diagnosis` text,
  `encounter` bigint(20) DEFAULT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

