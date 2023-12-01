<?php
require_once("src/pageName.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <form method="POST" action=<?php echo "index.php?page=" . PAGE_AUTHENTICATE; ?>>
        <label for="username">Username :</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">Password :</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" value="Login">
    </form>
</body>

</html>