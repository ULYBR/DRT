<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Conectar ao banco de dados
$db = mysqli_connect('host', 'username', 'password', 'database');

// Obter informações do usuário da sessão
$user_id = $_SESSION['user_id'];

// Processar ações de adicionar, editar e excluir tarefas
if (isset($_POST['action'])) {
  switch ($_POST['action']) {
    case 'add':
      $title = $_POST['title'];
      $description = $_POST['description'];
      mysqli_query($db, "INSERT INTO tasks (title, description, user_id) VALUES ('$title', '$description', $user_id)");
      break;
    case 'edit':
      $id = $_POST['id'];
      $title = $_POST['title'];
      $description = $_POST['description'];
      mysqli_query($db, "UPDATE tasks SET title = '$title', description = '$description' WHERE id = $id AND user_id = $user_id");
      break;
    case 'delete':
      $id = $_POST['id'];
      mysqli_query($db, "DELETE FROM tasks WHERE id = $id AND user_id = $user_id");
      break;
    case 'complete':
      $id = $_POST['id'];
      mysqli_query($db, "UPDATE tasks SET completion_date = CURDATE() WHERE id = $id AND user_id = $user_id");
      break;
  }
}

// Obter lista de tarefas do usuário
$result = mysqli_query($db, "SELECT * FROM tasks WHERE user_id = $user_id");
$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Exibir lista de tarefas
echo "<table>";
echo "<tr><th>Título</th><th>Descrição</th><th>Data de Conclusão</th><th>Ações</th></tr>";
foreach ($tasks as $task) {
  echo "<tr>";
  echo "<td>" . $task['title'] . "</td>";
  echo "<td>" . $task['description'] . "</td>";
  echo "<td>" . $task['completion_date'] . "</td>";
  echo "<td>";
  echo "<a href='#' onclick='editTask(" . $task['id'] . ")'>Editar</a> ";
  echo "<a href='#' onclick='deleteTask(" . $task['id'] . ")'>Excluir</a> ";
    if (!$task['completion_date']) {
        echo "<a href='#' onclick='completeTask(" . $task['id'] . ")'>Concluir</a>";

        }
echo "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($db);
?>



<form action="tasks.php" method="post">
  <input type="hidden" name="action" id="action">
  <input type="hidden" name="id" id="id">
  <div>
    <label for="title">Título:</label>
    <input type="text" name="title" id="title">
  </div>
  <div>
    <label for="description">Descrição:</label>
    <textarea name="description" id="description"></textarea>
  </div>
  <div>
    <button type="submit">Enviar</button>
  </div>
</form>
<script>
function editTask(id) {
  document.getElementById('action').value = 'edit';
  document.getElementById('id').value = id;
  // Obter informações da tarefa aqui
  // e preencher os campos do formulário
}

function deleteTask(id) {
  if (confirm('Tem certeza de que deseja excluir esta tarefa?')) {
    document.getElementById('action').value = 'delete';
    document.getElementById('id').value = id;
    document.forms[0].submit();
  }
}

function completeTask(id) {
  document.getElementById('action').value = 'complete';
  document.getElementById('id').value = id;
  document.forms[0].submit();
}
</script>
