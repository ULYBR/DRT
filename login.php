<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
  header('Location: tarefas.php');
  exit;
}

if (!empty($_POST)) {
  $usuario = $_POST['usuario'];
  $senha = md5($_POST['senha']);

  $conn = mysqli_connect('localhost', 'root', '', 'tarefas');
  $query = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) == 1) {
    $usuario = mysqli_fetch_assoc($result);
    $_SESSION['usuario_id'] = $usuario['id'];
    header('Location: tarefas.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <form action="login.php" method="post">
    Usuário: <input type="text" name="usuario"><br>
    Senha: <input type="password" name="senha"><br>
    <input type="submit" value="Entrar">
  </form>
</body>
</html>
