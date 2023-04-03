<?php
// Start session
session_start();

// Check if user is logged in
if(!isset($_SESSION["username"])){
	header("Location: login.php");
	exit;
}

// Connect to database
$servername = "localhost";
$username = "admin";
$password = "adminpassword";
$dbname = "dashboard_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if(isset($_POST["add_user"])){
	// Add user to database
	$username = $_POST["username"];
	$password = $_POST["password"];

	$sql = "INSERT INTO users (username, password)
		VALUES ('$username', '$password')";

	if ($conn->query($sql) === TRUE) {
	    $message = "User added successfully";
	} else {
	    $error = "Error adding user: " . $conn->error;
	}
}
elseif(isset($_POST["edit_user"])){
	// Edit user in database
	$user_id = $_POST["user_id"];
	$username = $_POST["username"];
	$password = $_POST["password"];

	$sql = "UPDATE users SET username='$username', password='$password' WHERE id=$user_id";

	if ($conn->query($sql) === TRUE) {
	    $message = "User updated successfully";
	} else {
	    $error = "Error updating user: " . $conn->error;
	}
}
elseif(isset($_POST["delete_user"])){
	// Delete user from database
	$user_id = $_POST["user_id"];

	$sql = "DELETE FROM users WHERE id=$user_id";

	if ($conn->query($sql) === TRUE) {
	    $message = "User deleted successfully";
	} else {
	    $error = "Error deleting user: " . $conn->error;
	}
}
elseif(isset($_POST["add_dashboard"])){
	// Add dashboard to database
	$name = $_POST["name"];
	$url = $_POST["url"];
	$type = $_POST["type"];

	$sql = "INSERT INTO dashboards (name, url, type)
		VALUES ('$name', '$url', '$type')";

	if ($conn->query($sql) === TRUE) {
	    $message = "Dashboard added successfully";
	} else {
	    $error = "Error adding dashboard: " . $conn->error;
	}
}
elseif(isset($_POST["edit_dashboard"])){
	// Edit dashboard in database
	$dashboard_id = $_POST["dashboard_id"];
	$name = $_POST["name"];
	$url = $_POST["url"];
	$type = $_POST["type"];

	$sql = "UPDATE dashboards SET name='$name', url='$url', type='$type' WHERE id=$dashboard_id";

	if ($conn->query($sql) === TRUE) {
	    $message = "Dashboard updated successfully";
	} else {
	    $error = "Error updating dashboard: " . $conn->error;
	}
}
elseif(isset($_POST["delete_dashboard"])){
	// Delete dashboard from database
	$dashboard_id = $_POST["dashboard_id"];

	$sql = "DELETE FROM dashboards WHERE id=$dashboard_id";

	if ($conn->query($sql) === TRUE) {
	    $message = "Dashboard deleted successfully";
	} else {
	    $error = "Error deleting dashboard: " . $conn->error;
	}
}

// Get list of users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$users = array();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Get list of dashboards
$sql = "SELECT * FROM dashboards";
$result = $conn->query($sql);
$dashboards = array();

if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {
$dashboards[] = $row;
}
}

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Panel</title>
</head>
<body>
	<h1>Welcome, <?php echo $_SESSION["username"]; ?></h1>
	<a href="logout.php">Logout</a>
	<hr>
<h2>User Management</h2>

<?php
if(isset($message)){
	echo "<p style='color:green'>$message</p>";
}
elseif(isset($error)){
	echo "<p style='color:red'>$error</p>";
}
?>

<table>
	<tr>
		<th>ID</th>
		<th>Username</th>
		<th>Password</th>
		<th>Action</th>
	</tr>
	<?php foreach($users as $user): ?>
		<tr>
			<td><?php echo $user["id"]; ?></td>
			<td><?php echo $user["username"]; ?></td>
			<td><?php echo $user["password"]; ?></td>
			<td>
				<form method="post">
					<input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
					<input type="text" name="username" value="<?php echo $user["username"]; ?>">
					<input type="text" name="password" value="<?php echo $user["password"]; ?>">
					<input type="submit" name="edit_user" value="Edit">
					<input type="submit" name="delete_user" value="Delete">
				</form>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<form method="post">
			<td></td>
			<td><input type="text" name="username" placeholder="Username"></td>
			<td><input type="text" name="password" placeholder="Password"></td>
			<td><input type="submit" name="add_user" value="Add"></td>
		</form>
	</tr>
</table>

<hr>

<h2>Dashboard Management</h2>

<table>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>URL</th>
		<th>Type</th>
		<th>Action</th>
	</tr>
	<?php foreach($dashboards as $dashboard): ?>
		<tr>
			<td><?php echo $dashboard["id"]; ?></td>
			<td><?php echo $dashboard["name"]; ?></td>
			<td><?php echo $dashboard["url"]; ?></td>
			<td><?php echo $dashboard["type"]; ?></td>
			<td>
				<form method="post">
					<input type="hidden" name="dashboard_id" value="<?php echo $dashboard["id"]; ?>">
					<input type="text" name="name" value="<?php echo $dashboard["name"]; ?>">
					<input type="text" name="url" value="<?php echo $dashboard["url"]; ?>">
					<select name="type">
						<option value="grafana" <?php echo ($dashboard
<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin"){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Edit user
    if(isset($_POST["edit_user"])){
		$user_id = $_POST["user_id"];
		$new_username = $_POST["username"];
		$new_password = $_POST["password"];

		// Prepare statement
		$sql = "UPDATE users SET username=?, password=? WHERE id=?";
		if($stmt = $conn->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$stmt->bind_param("ssi", $new_username, $new_password, $user_id);

			// Attempt to execute the prepared statement
			if($stmt->execute()){
				$message = "User updated successfully.";
			} else{
				$error = "Error updating user. Please try again later.";
			}

			// Close statement
			$stmt->close();
		}
    }

	// Delete user
    elseif(isset($_POST["delete_user"])){
		$user_id = $_POST["user_id"];

		// Prepare statement
		$sql = "DELETE FROM users WHERE id=?";
		if($stmt = $conn->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$stmt->bind_param("i", $user_id);

			// Attempt to execute the prepared statement
			if($stmt->execute()){
				$message = "User deleted successfully.";
			} else{
				$error = "Error deleting user. Please try again later.";
			}

			// Close statement
			$stmt->close();
		}
    }

	// Add user
    elseif(isset($_POST["add_user"])){
		$new_username = $_POST["username"];
		$new_password = $_POST["password"];

		// Prepare statement
		$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
		if($stmt = $conn->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$stmt->bind_param("ss", $new_username, $new_password);

			// Attempt to execute the prepared statement
			if($stmt->execute()){
				$message = "User added successfully.";
			} else{
				$error = "Error adding user. Please try again later.";
			}

			// Close statement
			$stmt->close();
		}
    }

	// Edit dashboard
	elseif(isset($_POST["edit_dashboard"])){
		$dashboard_id = $_POST["dashboard_id"];
		$new_name = $_POST["name"];
		$new_url = $_POST["url"];
		$new_type = $_POST["type"];

		// Prepare statement
		$sql = "UPDATE dashboards SET name=?, url=?, type=? WHERE id=?";
		if($stmt = $conn->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$stmt->bind_param("sssi", $new_name, $new_url, $new_type, $dashboard_id);

			// Attempt to execute the prepared statement
			if($stmt->execute()){
				$message = "Dashboard updated successfully.";
			} else{
				$error = "Error updating dashboard. Please try again later.";
			}
		// Close statement
		$stmt->close();
	}
}

// Delete dashboard
elseif(isset($_POST["delete_dashboard"])){
	$dashboard_id = $_POST["dashboard_id"];

	// Prepare statement
	$sql = "DELETE FROM dashboards WHERE id=?";
	if($stmt = $conn->prepare($sql)){
		// Bind variables to the prepared statement as parameters
		$stmt->bind_param("i", $dashboard_id);

		// Attempt to execute the prepared statement
		if($stmt->execute()){
			$message = "Dashboard deleted successfully.";
		} else{
			$error = "Error deleting dashboard. Please try again later.";
		}

		// Close statement
		$stmt->close();
	}
}

// Add dashboard
elseif(isset($_POST["add_dashboard"])){
	$new_name = $_POST["name"];
	$new_url = $_POST["url"];
	$new_type = $_POST["type"];

	// Prepare statement
	$sql = "INSERT INTO dashboards (name, url, type) VALUES (?, ?, ?)";
	if($stmt = $conn->prepare($sql)){
		// Bind variables to the prepared statement as parameters
		$stmt->bind_param("sss", $new_name, $new_url, $new_type);

		// Attempt to execute the prepared statement
		if($stmt->execute()){
			$message = "Dashboard added successfully.";
		} else{
			$error = "Error adding dashboard. Please try again later.";
		}

		// Close statement
		$stmt->close();
	}
}
}

// Retrieve users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Retrieve dashboards
$sql = "SELECT * FROM dashboards";
$result_dashboards = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="page-header">
            <h1>Admin Panel</h1>
        </div>
		<?php
		// Display error message if set
		if(isset($error)){
			echo '<div class="alert alert-danger">' . $error . '</div>';
		}
	// Display success message if set
	if(isset($message)){
		echo '<div class="alert alert-success">' . $message . '</div>';
	}
	?>
    <h2>Users</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
		<?php
		if($result->num_rows > 0){
			// Loop through each row
			while($row = $result->fetch_assoc()){
				echo '<tr>';
				echo '<td>' . $row["id"] . '</td>';
				echo '<td>' . $row["username"] . '</td>';
				echo '<td>' . $row["password"] . '</td>';
				echo '<td>';
				echo '<button type="button" class="btn
							echo '<input type="hidden" name="dashboard_id" value="' . $row["id"] . '">';
							echo '<button type="submit" class="btn btn-danger" name="delete_dashboard">Delete</button>';
							echo '</form>';
						echo '</td>';
						echo '</tr>';
					}
				}
				?>
			</tbody>
		</table>
		<h2>Add Dashboard</h2>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="form-group">
				<label>Name</label>
				<input type="text" name="name" class="form-control" required>
			</div>
			<div class="form-group">
				<label>URL</label>
				<input type="text" name="url" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Type</label>
				<select name="type" class="form-control" required>
					<option value="">Select type</option>
					<option value="grafana">Grafana</option>
					<option value="jira">JIRA</option>
				</select>
			</div>
			<button type="submit" class="btn btn-primary" name="add_dashboard">Add Dashboard</button>
		</form>
    </div>        
</div>
</body>
</html>