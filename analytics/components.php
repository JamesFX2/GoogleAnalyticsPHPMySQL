<?php

function getSessionData($analytics, $VIEW_ID, $dateRange, $dimensionFilterClause)
{

	$data = array();
	global $db;
	
	$data[] = array("dimensions" => array("ga:dimension2", "ga:pagePath", "ga:hostname", "ga:dimension3", "ga:eventCategory", "ga:eventAction", "ga:eventLabel"), "metrics" => array("Unique Events" => "ga:uniqueEvents", "Event Value" => "ga:eventValue"), "table" => "hits_events", "mappings" => array("SessionID" => "ga:dimension2", "Type" => "Events", "Page URL" => "ga:pagePath", "Hostname" => "ga:hostname", "Event Category" => "ga:eventCategory", "Event Action" => "ga:eventAction", "Event Label" => "ga:eventLabel", "Event Value" => "Event Value", "Timestamp" => "ga:dimension3"), "hit" => TRUE);

	
	$data[] = array("dimensions" => array("ga:dimension2", "ga:landingPagePath", "ga:dimension3", "ga:exitPagePath", "ga:hostname"), "metrics" => array("Sessions" => "ga:sessions", "Duration" => "ga:sessionDuration", "Bounces" => "ga:bounces"), "table" => "sessions_usage", "mappings" => array("SessionID" => "ga:dimension2", "Landing Page" => "ga:landingPagePath", "Duration" => "Duration", "Bounced" => "Bounces", "Timestamp" => "ga:dimension3", "Exit Page" => "ga:exitPagePath", "Hostname" => "ga:hostname"), "hit" => false);
	
	$data[] = array("dimensions" => array("ga:dimension2", "ga:channelGrouping", "ga:source", "ga:medium", "ga:campaign", "ga:hasSocialSourceReferral", "ga:fullReferrer"), "metrics" => array("Sessions" => "ga:sessions"), "table" => "sessions_meta", "mappings" => array("SessionID" => "ga:dimension2", "Channel" => "ga:channelGrouping", "Source" => "ga:source", "Campaign" => "ga:campaign", "Medium" => "ga:medium", "Has Social Source" => "ga:hasSocialSourceReferral", "Full Referrer" => "ga:fullReferrer"), "hit" => false);
	
	$data[] = array("dimensions" => array("ga:dimension2", "ga:city", "ga:country"), "metrics" => array("Sessions" => "ga:sessions"), "table" => "sessions_location", "mappings" => array("SessionID" => "ga:dimension2", "City" => "ga:city", "Country" => "ga:country"), "hit" => false);
	
	$data[] = array("dimensions" => array("ga:dimension2", "ga:deviceCategory", "ga:browser", "ga:browserVersion", "ga:operatingSystem", "ga:operatingSystemVersion", "ga:mobileDeviceInfo"), "metrics" => array("Sessions" => "ga:sessions"), "table" => "sessions_device", "mappings" => array("SessionID" => "ga:dimension2", "Device Category" => "ga:deviceCategory", "Browser" => "ga:browser", "Browser Version" => "ga:browserVersion", "Operating System" => "ga:operatingSystem", "Operating System Version" => "ga:operatingSystemVersion", "Mobile Device Info" => "ga:mobileDeviceInfo"), "hit" => false);

	$data[] = array("dimensions" => array("ga:dimension2", "ga:date"), "metrics" => array("Sessions" => "ga:sessions"), "table" => "sessions_date", "mappings" => array("SessionID" => "ga:dimension2", "Date" => "ga:date"), "hit" => false);
	
	
	$data[] = array("dimensions" => array("ga:dimension2", "ga:pagePath", "ga:hostname", "ga:dimension3", "ga:pageTitle"), "metrics" => array("Pageviews" => "ga:pageviews", "Exited" => "ga:exits", "Entrance" => "ga:entrances","Time On Page" => "ga:timeOnPage" ), "table" => "hits_pages", "mappings" => array("SessionID" => "ga:dimension2", "Type" => "Pageview", "Page URL" => "ga:pagePath", "Hostname" => "ga:hostname", "Page Title" => "ga:pageTitle", "Exited" => "Exited", "Entrance" => "Entrance", "Time On Page" => "Time On Page", "Timestamp" => "ga:dimension3"), "hit" => true);	
	
	$data[] =  array("dimensions" => array("ga:dimension2", "ga:pagePath", "ga:hostname", "ga:dimension3", "ga:searchKeyword", "ga:searchStartPage", "ga:searchAfterDestinationPage"), "metrics" => array("Searches" => "ga:searchUniques" ), "table" => "hits_searches", "mappings" => array("SessionID" => "ga:dimension2", "Type" => "Searches", "Page URL" => "ga:pagePath", "Hostname" => "ga:hostname", "Searched From" => "ga:searchStartPage", "Destination" => "ga:searchAfterDestinationPage", "Keyword" => "ga:searchKeyword", "Timestamp" => "ga:dimension3"), "hit" => true);
	// repeating device because of bug with mobile device info on desktop
	
	$data[] = array("dimensions" => array("ga:dimension2", "ga:deviceCategory", "ga:browser", "ga:browserVersion", "ga:operatingSystem", "ga:operatingSystemVersion"), "metrics" => array("Sessions" => "ga:sessions"), "table" => "sessions_device", "mappings" => array("SessionID" => "ga:dimension2", "Device Category" => "ga:deviceCategory", "Browser" => "ga:browser", "Browser Version" => "ga:browserVersion", "Operating System" => "ga:operatingSystem", "Operating System Version" => "ga:operatingSystemVersion", "Mobile Device Info" => "n/a"), "hit" => false);
	

	
	for($i=0; $i<count($data); $i++)
	{	
		$result = $data[$i];
		$metricsDimensionsContainer = createMetricsandDimensions(array($result["dimensions"]),array($result["metrics"]));
		$response = getReport($analytics,$VIEW_ID,$dateRange,$metricsDimensionsContainer,$dimensionFilterClause);
		insertCommit(printResults($response,$result["hit"]),$result["table"],$result["mappings"],500,false);
	}
	$db->safeClose();
	
}


function findCodes($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function getUsers($analytics, $VIEW_ID, $dateRange, $dimensionFilterClause) {
	
	$metricsContainer = array(
	array("Sessions" => "ga:sessions")	);
	
	$dimensionsContainer = array(
	array("ga:dimension1","ga:dimension2","ga:sessionCount")
	);		
	
	$metricsDimensionsContainer = createMetricsandDimensions($dimensionsContainer,$metricsContainer);
	$response = getReport($analytics,$VIEW_ID,$dateRange,$metricsDimensionsContainer,$dimensionFilterClause);
	insertCommit(printResults($response),"sessions_users",array("ClientID"=>"ga:dimension1","SessionID"=>"ga:dimension2","Session Count" => "ga:sessionCount"));
	
}

function keyToValues($type, $value) {
	if($type == 'goals')
	{
		$metrics = array("goal" => "ga:goal".$value["id"]."Completions", "value" => "ga:goal".$value["id"]."Value");
		$dimensions = array("ga:dimension2", "ga:pagePath");
		$mappings = array("SessionID"=>"ga:dimension2", "GoalID" => (int)$value["id"], "GoalLocation" => "ga:pagePath", "GoalValue" => "value");
		$table = "goal_completions";
		$hit = false;
	}
	if($type == 'customMetrics')
	{
		$metrics = array("MetricsValue" => "ga:metric".$value["id"]);
		$dimensions = array("ga:dimension2", "ga:dimension3");
		$mappings = array("SessionID"=>"ga:dimension2", "MetricsID" => (int)$value["id"], "MetricsValue" => "ga:metric".$value["id"], "Timestamp" => "ga:dimension3");
		$table = "hits_metrics";
		$hit = true;
	}
	if($type == 'customDimensions')
	{
		$metrics = array("Hits" => "ga:hits");
		$dimensions = array("ga:dimension2", "ga:dimension3", "ga:dimension".$value["id"]);
		$mappings = array("SessionID"=>"ga:dimension2", "DimensionID" => (int)$value["id"], "DimensionValue" => "ga:dimension".$value["id"], "Timestamp" => "ga:dimension3");
		$table = "hits_dimensions";	
		$hit = true;
	}
	
	return array("metrics" => $metrics, "dimensions" => $dimensions, "table" => $table, "mappings" => $mappings, "hit" => $hit);
}


function populateGoalsDimensionsMetrics($analytics, $VIEW_ID, $dateRange, $dimensionFilterClause, $dimensionsMetricsGoals)
{
	
	foreach($dimensionsMetricsGoals as $type => $values)
	{
		for($i=0; $i<count($values); $i++)
		{
			$result = keyToValues($type, $values[$i]);
			$metricsDimensionsContainer = createMetricsandDimensions(array($result["dimensions"]),array($result["metrics"]));
			$response = getReport($analytics,$VIEW_ID,$dateRange,$metricsDimensionsContainer,$dimensionFilterClause);
			insertCommit(printResults($response,$result["hit"]),$result["table"],$result["mappings"],500,false);
		}
		
	}

	
}
