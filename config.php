<?php

// Check the Tracking Code section of your Piwig server

return array(
    'piwik_host' => 'stats.' . $_SERVER['SERVER_NAME'], // default host: stats.$HOST
    'piwik_site_id' => '1', // default site id
    'piwik_image_tracking' => false, // Whether to use image tracking instead of javascript
    'piwik_image_page_name' => '', // Pagne name (Image tracking only)
    'piwik_track_subdomains' => true, // Track visitors across all subdomains
    'piwik_prepend_domain' => true, // Prepend the site domain to the page title when tracking
    'piwik_hide_aliases' =>  true // In the "Outlinks" report, hide clicks to known alias URLs of your domain
);
