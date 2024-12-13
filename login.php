<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Undertale Login</title>
    <link rel="stylesheet" href="style/login.css" />
  </head>
  <body>
    <div class="container">
      <img src="assets/logo.jpg" alt="" />
      <form class="form-box" action="admin/login.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required />

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required />

        <input type="submit" value="LOGIN" class="submit-button" />
      </form>
      <?php
      session_start();
      if (isset($_SESSION['error'])) {
        echo '<p class="error">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
      }
      ?>
    </div>
  </body>
</html>

