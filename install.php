<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineCraze Installation</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a1a;
            color: #f5f5f5;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 700px;
            margin: auto;
        }
        .card {
            background-color: #2a2a2a;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            text-align: center;
        }
        h1 {
            color: #e50914;
            margin-bottom: 20px;
        }
        p {
            color: #ccc;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: 600;
            background-color: #e50914;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #b20710;
        }
        .status {
            padding: 15px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: left;
            border: 1px solid;
        }
        .status.success {
            background-color: #1a4d2e;
            color: #d1e7dd;
            border-color: #2f855a;
        }
        .status.error {
            background-color: #5c1a1a;
            color: #f5c6cb;
            border-color: #e50914;
        }
        .status ul {
            padding-left: 20px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Welcome to CineCraze</h1>
            <p>
                This script will set up the necessary database and tables for your CineCraze application.
                Before you begin, please make sure you have updated your database credentials in <strong>/includes/config.php</strong>.
            </p>

            <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
                <form action="install.php" method="post">
                    <button type="submit" class="btn">🚀 Start Installation</button>
                </form>
            <?php endif; ?>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once 'includes/config.php';
                $messages = [];
                $errors = false;

                // Step 1: Connect to MySQL Server
                $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD);
                if ($conn->connect_error) {
                    $messages[] = ['type' => 'error', 'text' => "Connection to MySQL server failed: " . htmlspecialchars($conn->connect_error)];
                    $errors = true;
                } else {
                    $messages[] = ['type' => 'success', 'text' => "Successfully connected to MySQL server."];
                }

                // Step 2: Create Database if it doesn't exist
                if (!$errors) {
                    $sql_create_db = "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
                    if ($conn->query($sql_create_db) === TRUE) {
                        $messages[] = ['type' => 'success', 'text' => "Database '" . DB_NAME . "' created or already exists."];
                    } else {
                        $messages[] = ['type' => 'error', 'text' => "Error creating database: " . htmlspecialchars($conn->error)];
                        $errors = true;
                    }
                }

                // Step 3: Select the database
                if (!$errors) {
                    $conn->select_db(DB_NAME);
                }

                // Step 4: Read and execute SQL file
                if (!$errors) {
                    $sql_file_path = 'database.sql';
                    if (file_exists($sql_file_path)) {
                        $sql_commands = file_get_contents($sql_file_path);
                        if ($conn->multi_query($sql_commands)) {
                            // Clear out the results from the buffer
                            while ($conn->more_results() && $conn->next_result()) {
                                if ($result = $conn->store_result()) {
                                    $result->free();
                                }
                            }
                            $messages[] = ['type' => 'success', 'text' => "Successfully executed SQL commands to create tables and seed data."];
                        } else {
                            $messages[] = ['type' => 'error', 'text' => "Error executing SQL commands: " . htmlspecialchars($conn->error)];
                            $errors = true;
                        }
                    } else {
                        $messages[] = ['type' => 'error', 'text' => "Could not find database.sql file."];
                        $errors = true;
                    }
                }

                // Display results
                $status_class = $errors ? 'error' : 'success';
                echo "<div class='status {$status_class}'><ul>";
                foreach ($messages as $message) {
                    $icon = $message['type'] === 'success' ? '✅' : '❌';
                    echo "<li>{$icon} {$message['text']}</li>";
                }
                echo "</ul></div>";

                if (!$errors) {
                    echo "<p style='margin-top: 30px;'><strong>Installation complete!</strong> For security, please delete this <strong>install.php</strong> file now.</p>";
                    echo "<a href='admin/login.php' class='btn'>Go to Admin Login</a>";
                } else {
                    echo "<p style='margin-top: 30px;'>Installation failed. Please review the errors above, correct them, and try again.</p>";
                }

                $conn->close();
            }
            ?>
        </div>
    </div>
</body>
</html>
