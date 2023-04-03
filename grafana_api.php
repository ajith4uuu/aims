<?php

// Replace with your Grafana server URL and API key
$grafana_url = "https://your-grafana-server.com";
$grafana_api_key = "your-api-key";

// Grafana API endpoint to retrieve list of dashboards
$endpoint = "/api/search?query=&type=dash-db";

// Construct the API request URL
$url = $grafana_url . $endpoint;

// Set up cURL options
$options = array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer " . $grafana_api_key
  )
);

// Send the API request using cURL
$curl = curl_init();
curl_setopt_array($curl, $options);
$response = curl_exec($curl);
curl_close($curl);

// Parse the JSON response from the API
$dashboards = json_decode($response, true);

// Return the list of dashboards as JSON
header('Content-Type: application/json');
echo json_encode($dashboards);
