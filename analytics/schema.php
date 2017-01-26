<?php 


$schema = array();
$schema[] = "CREATE TABLE IF NOT EXISTS `dimensions_meta` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `scope` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ";

$schema[] = "CREATE TABLE IF NOT EXISTS `goals_completions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(40) NOT NULL,
  `GoalID` int(11) NOT NULL,
  `GoalLocation` varchar(600) NOT NULL,
  `GoalValue` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueGoals` (`SessionID`,`GoalID`),
  KEY `SessionID` (`SessionID`),
  KEY `GoalID` (`GoalID`)
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `goals_meta` (
  `GoalID` int(11) NOT NULL,
  `Goal Description` varchar(255) NOT NULL,
  PRIMARY KEY (`GoalID`)
) ";

$schema[] = "CREATE TABLE IF NOT EXISTS `hits_dimensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(40) NOT NULL,
  `DimensionID` int(11) NOT NULL,
  `DimensionValue` varchar(255) NOT NULL,
  `Timestamp` decimal(15,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueDimensions` (`SessionID`,`Timestamp`,`DimensionID`),
  KEY `SessionID` (`SessionID`),
  KEY `Timestamp` (`Timestamp`)
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `hits_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(20) NOT NULL,
  `SessionID` varchar(40) NOT NULL,
  `Page URL` varchar(600) NOT NULL,
  `Hostname` varchar(80) NOT NULL,
  `Event Category` varchar(100) NOT NULL,
  `Event Action` varchar(100) NOT NULL,
  `Event Label` varchar(255) NOT NULL,
  `Event Value` int(11) NOT NULL,
  `Timestamp` decimal(15,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueEvents` (`SessionID`,`Event Category`,`Event Action`,`Event Label`,`Timestamp`),
  KEY `Page URL` (`Page URL`(255)),
  KEY `Exited` (`Event Action`),
  KEY `Entrance` (`Event Label`),
  KEY `Time On Page` (`Event Value`),
  KEY `SessionID` (`SessionID`),
  KEY `Timestamp` (`Timestamp`)
) ";

$schema[] = "CREATE TABLE IF NOT EXISTS `hits_metrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(40) NOT NULL,
  `MetricsID` int(11) NOT NULL,
  `MetricsValue` varchar(255) NOT NULL,
  `Timestamp` decimal(15,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueMetrics` (`SessionID`,`Timestamp`,`MetricsID`),
  KEY `SessionID` (`SessionID`),
  KEY `Timestamp` (`Timestamp`)
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `hits_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(20) NOT NULL,
  `SessionID` varchar(40) NOT NULL,
  `Page URL` varchar(600) NOT NULL,
  `Hostname` varchar(80) NOT NULL,
  `Page Title` varchar(300) NOT NULL,
  `Exited` tinyint(1) NOT NULL,
  `Entrance` tinyint(1) NOT NULL,
  `Time On Page` float NOT NULL,
  `Timestamp` decimal(15,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniquePageHits` (`SessionID`,`Timestamp`),
  KEY `Page URL` (`Page URL`(255)),
  KEY `Exited` (`Exited`),
  KEY `Entrance` (`Entrance`),
  KEY `Time On Page` (`Time On Page`),
  KEY `SessionID` (`SessionID`),
  KEY `Timestamp` (`Timestamp`)
) ";

$schema[] = "CREATE TABLE IF NOT EXISTS `hits_searches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(20) NOT NULL,
  `SessionID` varchar(40) NOT NULL,
  `Page URL` varchar(600) NOT NULL,
  `Hostname` varchar(80) NOT NULL,
  `Searched From` varchar(300) NOT NULL,
  `Destination` varchar(300) NOT NULL,
  `Keyword` varchar(120) NOT NULL,
  `Timestamp` decimal(15,4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniquePageHits` (`SessionID`,`Timestamp`),
  KEY `Page URL` (`Page URL`(255)),
  KEY `Exited` (`Destination`(255)),
  KEY `Entrance` (`Keyword`),
  KEY `SessionID` (`SessionID`),
  KEY `Timestamp` (`Timestamp`)
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `metrics_meta` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `scope` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ";

$schema[] = "CREATE TABLE IF NOT EXISTS `sessions_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(40) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueDates` (`SessionID`),
  KEY `SessionID` (`SessionID`)
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `sessions_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(40) NOT NULL,
  `Device Category` varchar(25) NOT NULL,
  `Browser` varchar(40) NOT NULL,
  `Browser Version` varchar(20) NOT NULL,
  `Operating System` varchar(40) NOT NULL,
  `Operating System Version` varchar(20) NOT NULL,
  `Mobile Device Info` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueDeviceSessions` (`SessionID`),
  KEY `Device Category` (`Device Category`),
  KEY `SessionID` (`SessionID`)
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `sessions_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(40) NOT NULL,
  `City` varchar(120) NOT NULL,
  `Country` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueLogins` (`SessionID`),
  KEY `SessionID` (`SessionID`)
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `sessions_loggedin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` varchar(40) NOT NULL,
  `SessionID` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueLogins` (`UserID`,`SessionID`),
  KEY `SessionID` (`SessionID`),
  KEY `UserID` (`UserID`)
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `sessions_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(40) NOT NULL,
  `Channel` varchar(60) NOT NULL,
  `Source` varchar(255) NOT NULL,
  `Campaign` varchar(120) NOT NULL,
  `Medium` varchar(80) NOT NULL,
  `Has Social Source` varchar(12) NOT NULL,
  `Full Referrer` varchar(600) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueSessionsMeta` (`SessionID`),
  KEY `SessionID` (`SessionID`,`Source`,`Medium`,`Has Social Source`,`Full Referrer`(255)),
  KEY `Source` (`Source`),
  KEY `Medium` (`Medium`),
  KEY `Has Social Source` (`Has Social Source`),
  KEY `Full Referrer` (`Full Referrer`(255))
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `sessions_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(80) NOT NULL,
  `Hostname` varchar(40) NOT NULL,
  `Landing Page` varchar(600) NOT NULL,
  `Duration` float NOT NULL,
  `Bounced` tinyint(1) NOT NULL,
  `Timestamp` decimal(15,4) NOT NULL,
  `Exit Page` varchar(600) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueSessions` (`SessionID`),
  KEY `SessionID` (`SessionID`,`Landing Page`(255),`Duration`,`Bounced`,`Exit Page`(255)),
  KEY `Landing Page` (`Landing Page`(255)),
  KEY `Duration` (`Duration`),
  KEY `Bounced` (`Bounced`),
  KEY `Exit Page` (`Exit Page`(255)),
  KEY `Timestamp` (`Timestamp`),
  KEY `Hostname` (`Hostname`)
)";

$schema[] = "CREATE TABLE IF NOT EXISTS `sessions_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ClientID` varchar(40) NOT NULL,
  `SessionID` varchar(40) NOT NULL,
  `Session Count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueUsers` (`ClientID`,`SessionID`),
  KEY `SessionID` (`SessionID`),
  KEY `ClientID` (`ClientID`)
)";

?>
