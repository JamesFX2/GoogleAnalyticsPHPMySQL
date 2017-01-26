<?php


function serviceAnalytics()
{
	
	$KEY_FILE_LOCATION = __DIR__ . '/secret.json';

	// Create and configure a new client object.
	$client = new Google_Client();
	$client->setApplicationName("Google Analytics");
	// Create and configure a new client object.
	$client = new Google_Client();

	$client->setAuthConfig($KEY_FILE_LOCATION); 
	$client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
	$analytics = new Google_Service_Analytics($client);
	return $analytics;
}



function getDimensionsMetricsGoals($analytics, $params = array())
{
	$customDimensions = array();
	$customMetrics = array();
	$goals = array();
	global $VIEW_ID;
  global $ACCOUNT_ID;
  global $WEB_PROPERTY_ID;
	
	extract($params, EXTR_PREFIX_ALL, "sa");

	
	if(!isset($sa_ignoreListDimensions))
	{
		$sa_ignoreListDimensions = array();
	}
	if(!isset($sa_accountId))
	{
		$sa_accountId = $ACCOUNT_ID;
	}
	if(!isset($sa_webPropertyId))
	{
		$sa_webPropertyId =  $WEB_PROPERTY_ID;
	}
	if(!isset($sa_ignoreListMetrics))
	{
		$sa_ignoreListMetrics = array();
	}
	if(!isset($sa_profileId))
	{
		$sa_profileId = $VIEW_ID;
	}
	// get custom dimensions
	try {
		$properties = $analytics->management_customDimensions
		->listManagementCustomDimensions($sa_accountId, $sa_webPropertyId);

	} catch (apiServiceException $e) {
		print 'There was an Analytics API service error '
		. $e->getCode() . ':' . $e->getMessage();

	} catch (apiException $e) {
		print 'There was a general API error '
		. $e->getCode() . ':' . $e->getMessage();
	}

	$processing = $properties->getItems();
	for($i = 0; $i<count($processing); $i++)
	{
		if(!in_array((int)$processing[$i]->getIndex(),$sa_ignoreListDimensions))
		{
			$customDimensions[] = array("id" => (int) $processing[$i]->getIndex(), "name" => $processing[$i]->getName(), "scope" => $processing[$i]->getScope() );
		}
		
	}
	
	// get custom metrics 
	
	try {
		$properties = $analytics->management_customMetrics
		->listManagementCustomMetrics($sa_accountId, $sa_webPropertyId);

	} catch (apiServiceException $e) {
		print 'There was an Analytics API service error '
		. $e->getCode() . ':' . $e->getMessage();

	} catch (apiException $e) {
		print 'There was a general API error '
		. $e->getCode() . ':' . $e->getMessage();
	}
	$processing = $properties->getItems();

	
	for($i = 0; $i<count($processing); $i++)
	{
		if(!in_array((int)$processing[$i]->getIndex(),$sa_ignoreListMetrics))
		{
			$customMetrics[] = array("id" => (int) $processing[$i]->getIndex(), "name" => $processing[$i]->getName(), "scope" => $processing[$i]->getScope(), "type" => $processing[$i]->getScope() );
		}
		
	}
	
	// get goals
	
	
	try {
		$properties = $analytics->management_goals
		->listManagementGoals($sa_accountId, $sa_webPropertyId, $sa_profileId);

	} catch (apiServiceException $e) {
		print 'There was an Analytics API service error '
		. $e->getCode() . ':' . $e->getMessage();

	} catch (apiException $e) {
		print 'There was a general API error '
		. $e->getCode() . ':' . $e->getMessage();
	}
	
	
	$processing = $properties->getItems();
	for($i = 0; $i<count($processing); $i++)
	{
		$goals[] = array("id" => (int) $processing[$i]->getId(), "name" => $processing[$i]->getName() );
	}
	
	
	
	$db = safeOpen();
	$db->query("TRUNCATE TABLE `dimensions_meta`");
	$db->query("TRUNCATE TABLE `hits_meta`");
	$db->query("TRUNCATE TABLE `goals_meta`");
	$db->commit();
	insertCommit($customDimensions,"dimensions_meta",array("id" => "id", "name" => "name", "scope" => "scope"));
	insertCommit($customMetrics,"metrics_meta",array("id" => "id", "name" => "name", "scope" => "scope", "type" => "type"));
	insertCommit($goals,"goals_meta",array("GoalID" => "id", "Goal Description" => "name"));	
	
	return array("goals" => $goals, "customMetrics" => $customMetrics, "customDimensions" => $customDimensions);
	
}



?>
