<?php
// Start session
session_start();

// Include required files
require_once 'config.php';
require_once 'vendor/autoload.php';

// Initialize Grafana API client
$grafana = new Grafana\Client(GRAFANA_API_URL, GRAFANA_API_KEY);

// Initialize JIRA API client
$jira = new JiraRestApi\JiraRestApi(
    JIRA_API_URL,
    JIRA_API_USERNAME,
    JIRA_API_PASSWORD
);

// Handle add dashboard form submission
if (isset($_POST['add_dashboard'])) {
    // Get form data
    $name = $_POST['name'];
    $url = $_POST['url'];
    $type = $_POST['type'];

    // Insert dashboard into database
    $sql = "INSERT INTO dashboards (name, url, type) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $url, $type]);

    // Redirect to dashboard page
    header('Location: ' . BASE_URL);
    exit;
}

// Handle delete dashboard form submission
if (isset($_POST['delete_dashboard'])) {
    // Get dashboard ID
    $dashboardId = $_POST['dashboard_id'];

    // Delete dashboard from database
    $sql = "DELETE FROM dashboards WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dashboardId]);

    // Redirect to dashboard page
    header('Location: ' . BASE_URL);
    exit;
}

// Get list of dashboards from database
$sql = "SELECT * FROM dashboards";
$stmt = $pdo->query($sql);
$dashboards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's JIRA issues
$jql = "assignee = " . JIRA_API_USERNAME;
$issues = $jira->issue->search($jql);

// Set page title
$pageTitle = 'Dashboard';

// Include header
include 'header.php';
?>
<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h1><?php echo $pageTitle; ?></h1>
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link active" href="#grafana" data-toggle="tab">Grafana Dashboards</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#jira" data-toggle="tab">JIRA Board</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="grafana">
                    <?php foreach ($dashboards as $dashboard) {
                        if ($dashboard['type'] == 'grafana') {
                            echo '<iframe src="' . $dashboard['url'] . '" width="100%" height="600"></iframe>';
                        }
                    } ?>
                </div>
                <div class="tab-pane" id="jira">
                    <?php foreach ($issues->issues as $issue) {
                        echo '<h3>' . $issue->fields->summary . '</h3>';
                        echo '<p>' . $issue->fields->description . '</p>';
                    } ?>
                </div>
            </div>
        </div>
        <div class="col">
            <h2>Dashboards</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dashboards as $dashboard) { ?>
                    <tr>
                        <td><?php echo $dashboard['name']; ?></td>
                        <td><?php echo ucfirst($dashboard['type']); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="dashboard_id" value="<?php echo $dashboard['id']; ?>">
                                <button type="submit" name="delete_dashboard" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <h2>Add Dashboard</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="url">URL</label>
                <input type="url" class="form-control" id="url" name="url" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="grafana">Grafana</option>
                    <option value="jira">JIRA</option>
                </select>
            </div>
            <button type="submit" name="add_dashboard" class="btn btn-primary">Add Dashboard</button>
        </form>
    </div>
</div>
</div>
<?php
// Include footer
include 'footer.php';
?>