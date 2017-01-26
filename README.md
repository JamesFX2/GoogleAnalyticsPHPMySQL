# GoogleAnalyticsPHPMySQL
Script to export data from Google Analytics using the APIs. Needs https://www.simoahava.com/analytics/improve-data-collection-with-four-custom-dimensions/ - you need to setup custom dimensions and send them with each session/hit.

A hit timestamp (custom dimension 3) and a sessionID (custom dimension 2) needs to be sent with EVERY hit. A sessionID needs to be sent at least once during the session. A clientID relating to their GA ClientID needs to be sent at user scope at least once in the session - I do it as an event that drops a cookie in GTM. 

The above are used as keys so they're essential. Future versions will iterate past that. 

To be added - AdWords and Ecommerce. Ecommerce is going to be a bit longer coming because I need it to support EE. This was developed to aid custom report requirements which need to analyse usage beyond what's achievable in GA. They don't do ecommerce so.....

Possible long term goal - front end with GUI - I like the idea of GA data being used to personalise usage - previously viewed products, product recommendations, personalisation by segment (high spending customers).

It would really be appreciated if you could share some of your SQL thingies for recreating some of the basic reports.

P.S. You'll need Google's API Client for PHP - composer require google/apiclient:^2.0
https://github.com/google/google-api-php-client

And a file called secret.json uploaded to /analytics

Here.... from https://developers.google.com/analytics/devguides/reporting/core/v3/quickstart/service-php

## Create a client ID ##

- Open the Service accounts page. If prompted, select a project.
  https://console.developers.google.com/permissions/serviceaccounts

- Click Create service account.

- In the Create service account window, type a name for the service account, and select Furnish a new private key. If you want to grant G Suite domain-wide authority to the service account, also select Enable G Suite Domain-wide Delegation. Then click Create.

- Your new public/private key pair is generated and downloaded to your machine; it serves as the only copy of this key. You are responsible for storing it securely.

- When prompted for the Key type select JSON, and save the generated key as service-account-credentials.json; you will need it later in the tutorial. (I renamed this secret.json and stuck it in /analytics)

## Add service account to Google Analytics accoun t##

The newly created service account will have an email address, <projectId>-<uniqueId>@developer.gserviceaccount.com; Use this email address to add a user to the Google analytics account you want to access via the API. For this tutorial only Read & Analyze permissions are needed.

Step 2: Install the Google Client Library

You can obtain the Google APIs Client Library for PHP downloading the release or using Composer:

composer require google/apiclient:^2.0


