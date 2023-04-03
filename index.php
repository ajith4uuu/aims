<!DOCTYPE html>
<html>
<head>
	<title>User Panel</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function(){
			// Display Grafana dashboards tab on page load
			$("#jira-tab").hide();
			$("#grafana-tab").show();
			
			// Switch to Grafana dashboards tab on click
			$("#grafana-link").click(function(){
				$("#jira-tab").hide();
				$("#grafana-tab").show();
			});
			
			// Switch to Jira board tab on click
			$("#jira-link").click(function(){
				$("#grafana-tab").hide();
				$("#jira-tab").show();
			});
			
			// Load Grafana dashboards on
$.ajax({
url: "grafana_api.php",
dataType: "json",
success: function(data){
// Parse Grafana API response
var dashboards = data;
				// Display Grafana dashboards
				$.each(dashboards, function(index, dashboard){
					var dashboard_url = dashboard.url;

					$("#grafana-tab").append('<iframe src="' + dashboard_url + '"></iframe>');
				});
			}
		});
		
		// Load Jira board on Jira tab
		$("#jira-tab").append('<iframe src="jira_board.php"></iframe>');
	});
</script>
