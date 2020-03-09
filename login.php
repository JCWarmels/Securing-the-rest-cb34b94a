<?php
function loggingIn() 
{
    $dsn = "mysql:host=localhost;dbname=netland";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($_POST['username'])) {
        $check_attempt = $pdo->prepare("SELECT username FROM gebruikers WHERE EXISTS (SELECT wachtwoord FROM gebruikers WHERE wachtwoord=?)");
        $check_attempt->execute([$_POST['password']]);
        $check_attempt = $check_attempt->fetch();
        if (!$check_attempt) {
            throw new Exception("This username and password combination is not registered.");
        } else if ($_POST['username'] != $check_attempt['username']) {
            throw new Exception("This username and password combination is not registered.");
        } else {
            setcookie('loggedInUser', $_POST['username'], time() + (86400));
            header('Location: /PHP/index.php');
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login pagina</title>
</head>
<body>
    <header>
        <h1>Login Pagina Portaal Netland</h1>
    </header>
    <main>
        <form method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="text" name="password" placeholder="Password">
            <input type="submit" name="submit" value="Login">
        </form>
    </main>
</body>
</html>
<?php
try {
    loggingIn();
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>