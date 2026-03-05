<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $pass == $user['password']) { // Use password_verify in production
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        echo "<script>window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Invalid Credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | OLX Clone</title>
    <style>
        body { background: #f2f4f5; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 400px; text-align: center; }
        .login-card img { width: 60px; margin-bottom: 20px; }
        h2 { color: #002f34; margin-bottom: 25px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #002f34; border-radius: 4px; font-size: 16px; }
        .btn { background: #002f34; color: white; border: none; width: 100%; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn:hover { background: #004d56; }
        .footer-link { margin-top: 20px; font-size: 14px; color: #002f34; }
    </style>
</head>
<body>
    <div class="login-card">
        <div style="font-size: 40px; font-weight: bold; color: #002f34; margin-bottom: 10px;">OLX</div>
        <h2>Welcome Back</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="footer-link">Don't have an account? <a href="signup.php" style="color: #3a77ff;">Sign Up</a></div>
    </div>
</body>
</html>
