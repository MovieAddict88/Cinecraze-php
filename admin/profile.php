<?php
// Include the header, which also starts the session and checks for login
require_once 'header.php';
require_once '../config/db.php';

$current_password_err = $new_password_err = $confirm_password_err = "";
$success_message = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate current password
    if (empty(trim($_POST["current_password"]))) {
        $current_password_err = "Please enter your current password.";
    } else {
        $current_password = trim($_POST["current_password"]);
    }

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before interacting with the database
    if (empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare a select statement to get the current hashed password
        $sql = "SELECT password FROM users WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $_SESSION["id"]);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($current_password, $hashed_password)) {
                            // Current password is correct, proceed to update the password
                            $sql_update = "UPDATE users SET password = ? WHERE id = ?";

                            if ($stmt_update = $conn->prepare($sql_update)) {
                                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                                $stmt_update->bind_param("si", $param_password, $_SESSION["id"]);

                                if ($stmt_update->execute()) {
                                    $success_message = "Your password has been updated successfully.";
                                } else {
                                    $new_password_err = "Oops! Something went wrong. Please try again later.";
                                }
                                $stmt_update->close();
                            }
                        } else {
                            $current_password_err = "The current password you entered is not correct.";
                        }
                    }
                }
            } else {
                $new_password_err = "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<main>
    <section class="change-password-section">
        <h2>Change Password</h2>
        <p>Use the form below to change your password.</p>

        <?php if(!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($current_password_err)) ? 'has-error' : ''; ?>">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control">
                <span class="help-block"><?php echo $current_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update Password">
            </div>
        </form>
    </section>
</main>
<style>
    .change-password-section { max-width: 600px; margin: auto; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 5px; color: #ccc; }
    .form-control { width: 100%; padding: 10px; background-color: #333; border: 1px solid #444; color: #fff; border-radius: 4px; }
    .has-error .form-control { border-color: #e50914; }
    .help-block { color: #e50914; font-size: 14px; margin-top: 5px; display: block; }
    .alert-success { background-color: #28a745; color: #fff; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    .btn-primary { background-color: #e50914; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
    .btn-primary:hover { background-color: #f40612; }
</style>

<?php
require_once 'footer.php';
?>
