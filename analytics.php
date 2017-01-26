<?php

//////////// DATA //////////

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

$VIEW_ID = "XXX";
// not your UA-ID, your view ID

// Load the Google API PHP Client Library.
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/analytics/functions.php';
require __DIR__ . '/analytics/database.php';
require __DIR__ . '/analytics/management.php';
require __DIR__ . '/analytics/components.php';

// build database

buildDb($schema);

// populate meta tables for goals, custom dimensions, custom metrics

$serviceAnalytics = serviceAnalytics();
$dimensionsMetricsGoals = getDimensionsMetricsGoals($serviceAnalytics,array("ignoreListDimensions" => [1,2,3,4]));


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
