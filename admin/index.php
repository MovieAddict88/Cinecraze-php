<?php include 'parts/header.php'; ?>

<div class="card" style="max-width: 500px; margin: 40px auto;">
    <h2>Admin Login</h2>
    <form action="login_handler.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<?php include 'parts/footer.php'; ?>
