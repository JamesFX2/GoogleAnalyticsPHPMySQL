<?php 


function platformSlashes($path) {
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
        $path = str_replace('/', '\\', $path);
    }
    return $path;
}

function initializeAnalytics()
{
	// Creates and returns the Analytics Reporting service object.

	// Use the developers console and download your service account
	// credentials in JSON format. Place them in this directory or
	// change the key file location if necessary.
	$KEY_FILE_LOCATION = __DIR__ . '/secret.json';

	// Create and configure a new client object.
	$client = new Google_Client();
	$client->setApplicationName("Google Analytics");
	// Create and configure a new client object.
	$client = new Google_Client();

	$client->setAuthConfig($KEY_FILE_LOCATION); 
	$client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
	$analytics = new Google_Service_AnalyticsReporting($client);

	return $analytics;
}


function setDate($startDate,$endDate) {
	
	$dateRange = new Google_Service_AnalyticsReporting_DateRange();
	$dateRange->setStartDate($startDate);
	$dateRange->setEndDate($endDate);
	return $dateRange;
}

function createMetricsandDimensions($dimensionsContainer,$metricsContainer) {
	
	$metricsOutput = array();
	$dimensionsOutput = array();
	
	for($j=0;$j<count($metricsContainer);$j++)
	{
		$metrics = array();
		$dimensions = array();
		$counter = 0;
		foreach($metricsContainer[$j] as $i => $value)
		{
			
			$metrics[$counter] = new Google_Service_AnalyticsReporting_Metric();
			$metrics[$counter]->setExpression($value);
			$metrics[$counter]->setAlias($i);  
			$counter++;
		}
			
		for($i=0; $i<count($dimensionsContainer[$j]); $i++)
		{
			$dimensions[$i] = new Google_Service_AnalyticsReporting_Dimension();
			$dimensions[$i]->setName($dimensionsContainer[$j][$i]);
		}
		$metricsOutput[] = $metrics;
		$dimensionsOutput[] = $dimensions;
	}
	return array("metrics" => $metricsOutput, "dimensions" => $dimensionsOutput);
}



function createFilters($filtersContainer)
{
	
	// https://developers.google.com/analytics/devguides/reporting/core/v4/rest/v4/reports/batchGet#dimensionfilterclause
	
	$filters = array();
	for($j=0; $j<count($filtersContainer["data"]); $j++)
	{
		$filters[$j] = new Google_Service_AnalyticsReporting_DimensionFilter();
		$filters[$j]->setDimensionName($filtersContainer["data"][$j]["dimension"]);
		$filters[$j]->setOperator($filtersContainer["data"][$j]["operator"]);
		$filters[$j]->setExpressions($filtersContainer["data"][$j]["expression"]);
		$filters[$j]->setNot(!$filtersContainer["data"][$j]["include"]);
		//$filters[$j]->setCaseSensitive(false);
		
	}
	
	$dimensionFilterClause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
	$dimensionFilterClause->setFilters($filters);
	$dimensionFilterClause->setOperator($filtersContainer["joinType"]);
	
	return $dimensionFilterClause;
}


function getReport($analytics,$VIEW_ID,$dateRange,$metricsDimensionsContainer,$dimensionFilterClause) {


	$metrics = $metricsDimensionsContainer["metrics"];
	$dimensions = $metricsDimensionsContainer["dimensions"];
	


	
	$respArray = array();

	for($j=0;$j<count($metrics);$j++)
	{
		time_nanosleep(1,0);

		
		$page = "0";
		do {
			
			
			$request = new Google_Service_AnalyticsReporting_ReportRequest();
			$request->setViewId($VIEW_ID);
			$request->setDateRanges($dateRange);
			$request->setDimensions($dimensions[$j]);
			$request->setMetrics($metrics[$j]);
			$request->setDimensionFilterClauses($dimensionFilterClause);
			$request->setPageSize(10000);
			$request->setPageToken((string) $page);

			$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
			$body->setReportRequests( array( $request) );
			$temp = $analytics->reports->batchGet( $body );
			
			$respArray[] = $temp;
			$page = $temp->getReports()[0]->getNextPageToken();

			if($page)
			{
				time_nanosleep(1,0);
			}

		} while ($page);
		
		

		
	}
	return $respArray;

}

function printResults($reports,$hit=false) {
	$output = array();
	$counter = 0;
	for($k = 0; $k < count($reports); $k++)
	{
		for ( $reportIndex = 0; $reportIndex < count( $reports[$k] ); $reportIndex++ ) 
		{
			$report = $reports[$k][ $reportIndex ];
			$header = $report->getColumnHeader();
			$dimensionHeaders = $header->getDimensions();
			$index = array_search('ga:dimension2',$dimensionHeaders);
			if($index !== NULL && $index !== FALSE)
			{
				$metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();

				$rows = $report->getData()->getRows();
				

				for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) 
				{
					$row = $rows[ $rowIndex ];
					$dimensions = $row->getDimensions();
					$metrics = $row->getMetrics();
					for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) 
					{
						if($hit)
						{
							$output[$counter][$dimensionHeaders[$i]] = $dimensions[$i];
						}	
						else {
							$output[$dimensions[$index]][$dimensionHeaders[$i]] = $dimensions[$i];
						}							
					}
					
					for ($j = 0; $j < count( $metricHeaders ); $j++) 
					{
						$entry = $metricHeaders[$j];
						$value = $metrics[0]->getValues()[ $j ];
						if($hit)
						{
							$output[$counter][$entry->getName()] = $value;
						}
						else {
							$output[$dimensions[$index]][$entry->getName()] = $value;
						}
						
					}
					$counter++;
				}
			}

		}		
		
	}
	return $output;
}

?>
