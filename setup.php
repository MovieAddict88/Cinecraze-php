<?php
// CineCraze Database Setup Script

// Include the database configuration
require_once 'config/db.php';

// --- Helper Functions ---

/**
 * Executes a multi-query SQL string from a file.
 *
 * @param mysqli $conn The database connection object.
 * @param string $filepath The path to the .sql file.
 * @return array An array containing 'success' and 'errors' arrays.
 */
function execute_sql_from_file($conn, $filepath) {
    $results = ['success' => [], 'errors' => []];

    // Check if the file exists
    if (!file_exists($filepath)) {
        $results['errors'][] = "Schema file not found: $filepath";
        return $results;
    }

    // Read the SQL file
    $sql = file_get_contents($filepath);
    if ($sql === false) {
        $results['errors'][] = "Could not read the schema file: $filepath";
        return $results;
    }

    // Execute multi-query
    if ($conn->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $conn->store_result()) {
                $result->free();
            }
            // Check for errors
            if ($conn->error) {
                $results['errors'][] = "Error executing query: " . $conn->error;
            } else {
                if ($conn->affected_rows > -1) {
                    $results['success'][] = "Query executed successfully. Affected rows: " . $conn->affected_rows;
                }
            }
        } while ($conn->more_results() && $conn->next_result());
    } else {
        $results['errors'][] = "Failed to execute multi-query: " . $conn->error;
    }

    // Final check for any remaining errors
    if ($conn->error) {
        $results['errors'][] = "Final error check: " . $conn->error;
    }

    return $results;
}

// --- Main Logic ---

$message = '';
$error = false;
$setup_results = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'setup_database') {
        // Try to establish a database connection
        $conn = get_db_connection();

        if ($conn) {
            // Execute the schema.sql file
            $setup_results = execute_sql_from_file($conn, 'schema.sql');
            $conn->close();

            if (empty($setup_results['errors'])) {
                $message = "Database setup completed successfully! It is highly recommended to DELETE this setup.php file now for security.";
                $error = false;
            } else {
                $message = "Database setup encountered errors. See details below.";
                $error = true;
            }
        } else {
            $message = "Database connection failed! Please check your credentials in <code>config/db.php</code>.";
            $error = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineCraze Database Setup</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #141414;
            color: #f5f5f5;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #1a1a1a;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            max-width: 800px;
            width: 100%;
            border: 1px solid #333;
        }
        h1 {
            color: #e50914;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            line-height: 1.6;
            text-align: center;
            color: #b3b3b3;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .setup-button {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .setup-button:hover {
            background-color: #b20710;
        }
        .message {
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: left;
        }
        .message.success {
            background-color: rgba(70, 211, 105, 0.1);
            border-left: 5px solid #46d369;
            color: #46d369;
        }
        .message.error {
            background-color: rgba(244, 6, 18, 0.1);
            border-left: 5px solid #f40612;
            color: #f40612;
        }
        .results {
            margin-top: 20px;
            background-color: #222;
            padding: 15px;
            border-radius: 8px;
            max-height: 300px;
            overflow-y: auto;
        }
        .results h3 {
            margin-top: 0;
        }
        .results ul {
            padding-left: 20px;
            margin: 0;
            list-style-type: '✓ ';
        }
        .results ul.errors {
            list-style-type: '✗ ';
            color: #f40612;
        }
        code {
            background-color: #333;
            padding: 2px 5px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>CineCraze Database Setup</h1>
        <p>
            Welcome to the CineCraze setup utility. Before you can use the admin panel,
            you need to set up the database.
        </p>
        <p>
            Please ensure you have correctly filled in your database credentials in the
            <code>config/db.php</code> file.
        </p>

        <?php if ($message): ?>
            <div class="message <?php echo $error ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($setup_results): ?>
            <div class="results">
                <?php if (!empty($setup_results['success'])): ?>
                    <h3>Successful Operations:</h3>
                    <ul>
                        <?php foreach ($setup_results['success'] as $success_msg): ?>
                            <li><?php echo htmlspecialchars($success_msg, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if (!empty($setup_results['errors'])): ?>
                    <h3 style="color: #f40612;">Errors:</h3>
                    <ul class="errors">
                        <?php foreach ($setup_results['errors'] as $error_msg): ?>
                            <li><?php echo htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="button-container">
            <form method="POST" action="setup.php">
                <input type="hidden" name="action" value="setup_database">
                <button type="submit" class="setup-button">Create Database Tables</button>
            </form>
        </div>
    </div>
</body>
</html>
