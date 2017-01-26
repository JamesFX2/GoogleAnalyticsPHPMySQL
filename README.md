# GoogleAnalyticsPHPMySQL
Script to export data from Google Analytics using the APIs. Needs https://www.simoahava.com/analytics/improve-data-collection-with-four-custom-dimensions/ - you need to setup custom dimensions and send them with each session/hit.

A hit timestamp (custom dimension 3) and a sessionID (custom dimension 2) needs to be sent with EVERY hit. A sessionID needs to be sent at least once during the session. A clientID relating to their GA ClientID needs to be sent at user scope at least once in the session - I do it as an event that drops a cookie in GTM. 

The above are used as keys so they're essential. Future versions will iterate past that. 

To be added - AdWords and Ecommerce. Ecommerce is going to be a bit longer coming because I need it to support EE. This was developed to aid custom report requirements which need to analyse usage beyond what's achievable in GA. They don't do ecommerce so.....

Possible long term goal - front end with GUI - I like the idea of GA data being used to personalise usage - previously viewed products, product recommendations, personalisation by segment (high spending customers).

It would really be appreciated if you could share some of your SQL thingies for recreating some of the basic reports.

