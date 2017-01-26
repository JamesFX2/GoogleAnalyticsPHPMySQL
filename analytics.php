<?php

//////////// DATA //////////

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

$ACCOUNT_ID = "XXX";
// not your UA - grab this from https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/management/accounts/list 
// scroll down and hit the blue button - ctrl+f to find the account ID

$WEB_PROPERTY_ID = "UA-XXX";

// Can get this by knowing it or... remember the account ID from above - 
// https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/management/webproperties/list
// hit that blue button

$VIEW_ID = "XXX";

// not your UA-ID, your view ID - as above 
// https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/management/profiles/list
// enter the account id / web property ID and you should get this. 

// P.s you'll need to update this, 
// 1. your ignored custom dimensions (USER ID, Timestamp, Session ID, Client ID)

$ignoreListDimensions => [1,2,3,4];

// 2. customise which your sessionID dimension is (soz, this version, find and replace dimension2 
// with whatever yours is)

// 3. which your client ID is too - as above dimension1

// 4. which your timestamp is -  find and replace dimension3

// 5. Oh and the database settings in /analytics/database.php

// 6. And the other instructions in the readme....

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/analytics/functions.php';
require __DIR__ . '/analytics/database.php';
require __DIR__ . '/analytics/management.php';
require __DIR__ . '/analytics/components.php';

// build database

buildDb($schema);

// populate meta tables for goals, custom dimensions, custom metrics

$serviceAnalytics = serviceAnalytics();
$dimensionsMetricsGoals = getDimensionsMetricsGoals($serviceAnalytics,array("ignoreListDimensions" => $ignoreListDimensions));


// ACCOUNT


// FILTERS 
$filtersItems = array(
	
	array(
	"dimension" => "ga:hostname",
	"operator" => "IN_LIST",
	"expression" => ["www.yoursite.com", "subdomain.yoursite.com", "something.yoursite.com", "another.yoursite.com"],
	"include" => true
	),
		array(
	"dimension" => "ga:dimension1",
	"operator" => "REGEXP",
	"expression" => ["[0-9]"],
	"include" => true
	)
	);
	
	$filtersJOIN = "AND";
	// join type = or / and
	unset($filtersItems[1]);
	$filtersContainer = array("data" => $filtersItems, "joinType" => $filtersJOIN);
	
	
// DATE RANGE

$startDate = "yesterday";
//$startDate = "2017-01-14";
$endDate = "yesterday";

// session DATE = when session ends 


// DIMENSIONS

/* MAX OF 7 DIMENSIONS PER CALL */


$analytics = initializeAnalytics();
$dateRange = setDate($startDate,$endDate);
$dimensionFilterClause = createFilters($filtersContainer);


// BUILD USERS TABLE -> SOMETIMES HAVE NULL USERS - SPECIAL CASE

getUsers($analytics, $VIEW_ID, $dateRange, $dimensionFilterClause);

// Get goals, dimensions and metrics



populateGoalsDimensionsMetrics($analytics, $VIEW_ID, $dateRange, $dimensionFilterClause, $dimensionsMetricsGoals);


//flush();



getSessionData($analytics, $VIEW_ID, $dateRange, $dimensionFilterClause);

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';



// Metrics

/* MAX OF 10 PER CALL */
// THEY ARE MATCHED TO $dimensionsContainer so array item #0 is called with #0 in dimensions



?>
