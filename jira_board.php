<?php

// Replace with your Jira server URL and API credentials
$jira_url = "https://your-jira-server.com";
$jira_username = "your-username";
$jira_password = "your-password";

// Jira API endpoint to retrieve a board
$endpoint = "/rest/agile/1.0/board?projectKeyOrId=YOUR-PROJECT-KEY";

// Construct the API request URL
$url = $jira_url . $endpoint;

// Set up cURL options
$options = array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
  CURLOPT_USERPWD => "$jira_username:$jira_password",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  )
);

// Send the API request using cURL
$curl = curl_init();
curl_setopt_array($curl, $options);
$response = curl_exec($curl);
curl_close($curl);

// Parse the JSON response from the API
$board = json_decode($response, true);

// Return the board as JSON
header('Content-Type: application/json');
echo json_encode($board);
