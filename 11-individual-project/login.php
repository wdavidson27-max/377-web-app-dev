<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/library.php';

start_app_session();

if (isset($_SESSION['user_id'])) {
    header('Location: ./index.php');
    exit;
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    

    if ($email === '' || $password === '') {
        $errorMessage = 'Email and password are required.';
    } else {
        $connection = get_connection();
        $statement = $connection->prepare('SELECT id, email, password_hash FROM users WHERE email = ?');
        $statement->bind_param('s', $email);
        $statement->execute();
        $result = $statement->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                header('Location: ./index.php');
                exit;
            }

            $errorMessage = 'Incorrect password for that email.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $username = $email;
            $insert = $connection->prepare(
                'INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)'
            );
            $insert->bind_param('sss', $username, $email, $passwordHash);

            if ($insert->execute()) {
                $_SESSION['user_id'] = $insert->insert_id;
                $_SESSION['user_email'] = $email;
                header('Location: ./index.php');
                exit;
            }

            $errorMessage = 'Account could not be created. Please try again with a different email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login to Poker Game</title>
    <style>
      :root {
        --bg-top: #102331;
        --bg-bottom: #092d43ff;
        --panel: rgba(10, 24, 35, 0.84);
        --accent: #8f6505ff;
        --text: #e7b93f;
        --muted: rgba(249, 251, 253, 0.72);
      }

      * {
        box-sizing: border-box;
      }

      body {
        margin: 0;
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 20px;
        font-family: "Manrope", sans-serif;
        color: var(--text);
        background:
          radial-gradient(circle at top, rgba(255, 209, 102, 0.08), transparent 28%),
          linear-gradient(180deg, var(--bg-top), var(--bg-bottom));
      }

      .login-card {
        width: min(420px, 100%);
        padding: 28px;
        border-radius: 24px;
        background: var(--panel);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 24px 50px rgba(0, 0, 0, 0.28);
      }

      h1 {
        margin: 0 0 10px;
        font-size: 2rem;
      }

      p {
        margin: 0 0 18px;
        color: var(--muted);
        line-height: 1.5;
      }

      form {
        display: grid;
        gap: 14px;
      }

      label {
        display: grid;
        gap: 6px;
        font-size: 0.9rem;
      }

      input {
        height: 46px;
        padding: 0 12px;
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.08);
        color: var(--text);
        font: inherit;
      }

      button {
        height: 48px;
        border: 0;
        border-radius: 999px;
        background: linear-gradient(180deg, #ffd978, #e7b93f);
        color: #2d2206;
        font: inherit;
        font-weight: 800;
        cursor: pointer;
      }

      .error {
        margin-bottom: 14px;
        padding: 10px 12px;
        border-radius: 12px;
        background: rgba(180, 35, 24, 0.18);
        color: #ffb4aa;
        font-size: 0.92rem;
      }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <section class="login-card">
      <h1>Login</h1>
      <p>Enter your email and password</p>
     

      <?php if ($errorMessage !== ''): ?>
        <div class="error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="post">
        <label>
          <span>Email</span>
          <input type="email" name="email" required />
        </label>

        <label>
          <span>Password</span> 
          <input type="password" name="password" required />
        </label>
        
        <button type="submit">Log In</button>
      </form>
    </section>
  </body>
</html>
