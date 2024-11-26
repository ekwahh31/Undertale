<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Undertale Register</title>
    <link rel="stylesheet" href="style/register.css" />
  </head>
  <body>
    <div class="container">
      <img src="assets/logo.jpg" alt="" />
      <form class="form-box" action="" method="post" onsubmit="register(event)">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required />

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required />

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required />

        <div class="radio-group">
          <input type="radio" name="role" id="frisk" value="Frisk" />
          <label for="frisk">Frisk</label>
          <input type="radio" name="role" id="monster" value="Monster" />
          <label for="monster">Monster</label>
        </div>

        <input type="submit" value="REGISTER" class="submit-button" />
      </form>
    </div>

    <script>
      function register(event) {
        event.preventDefault();
        alert("Register berhasil!");
        window.location.href = "login.php";
      }
    </script>
  </body>
</html>
