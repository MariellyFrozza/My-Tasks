<?php
ini_set('error_reporting', E_ALL); // teste erro
ini_set('display_errors', 1);
require 'db_credentials.php';


error_reporting(E_ALL);
//cria conexão
$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) {
  die("Problemas ao conectar com o BD, tente novamente!<br>". mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["nova-tarefa"])) {

    $titulo = ($_POST["nova-tarefa"]);
    $titulo = mysqli_real_escape_string($conn,$titulo); //evitar injeção de codigo
    $data_criado = date("Y-m-d H:i:s");
    $sql = "INSERT INTO $table (titulo,data_criado)
    VALUES ('$titulo', '$data_criado')";
    if (!mysqli_query($conn, $sql)) {
      die("Problema para inserir nova tarefa no Banco de Dados</br>" . mysqli_error($conn));
    }
  }elseif (isset($_POST["novo-titulo-tarefa"])) {
    $titulo = $_POST["novo-titulo-tarefa"];
    $id = $_POST["id"];

    $sql = "UPDATE $table SET titulo = '$titulo' WHERE id=$id";
    if (!mysqli_query($conn, $sql)) {
      die("Problema para inserir nova tarefa no Banco de Dados</br>" . mysqli_error($conn));
    }
    
  }
}



  elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["acao"]) && isset($_GET["id"])) {
      
      $sql = "";

      $id = ($_GET['id']);
      $id = mysqli_real_escape_string($conn,$id);
      
      if($_GET["acao"] == "feito"){
        $sql = "UPDATE $table
                SET feito=true
                WHERE id=" . $id;
      }

      if($_GET["acao"] == "desfeito"){
        $sql = "UPDATE $table
                SET feito=false
                WHERE id=" . $id;
      }

      if($_GET["acao"] == "remover"){
        $sql = "DELETE FROM $table
              WHERE id=" . $id;

      }

      if ($sql != "") {
        if(!mysqli_query($conn,$sql)){
          die("Problemas para executar ação no BD, tenta mais uma vez!<br>". mysqli_error($conn));
        }
      }
    }
  }
  
$sql = "SELECT id,titulo FROM $table WHERE feito = false";
if(!($tarefas_pendentes = mysqli_query($conn,$sql))){
  die("Problemas para carregar tarefas do BD, tente novamente!<br>". mysqli_error($conn));
}

$sql = "SELECT id,titulo FROM $table WHERE feito = true";
if(!($tarefas_concluidas = mysqli_query($conn,$sql))){
  die("Problemas para carregar tarefas do BD, tente novamente!<br>". mysqli_error($conn));
}



mysqli_close($conn); //fechar a conexão
?>


<!DOCTYPE html>
<html>
<head>
  <title>Lista de tarefas WEB1</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/bootstrap.css">
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <style media="screen">
    .alert a:hover{
      text-decoration: none;
    }
    .alert .tarefa {
      font-size: 1.3em;
    }

    h3.panel-title{
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-xs-offset-3 col-xs-6">
      <h1 class="page-header">Lista de tarefas WEB1</h1>


      <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
        <div class="form-group">
          <label class="sr-only" for="inputTarefa">Inserir nova tarefa</label>
          <input required type="text" name="nova-tarefa" class="form-control" id="inputTarefa" placeholder="Inserir nova tarefa">
        </div>
      </form>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            <span class="glyphicon glyphicon-list"></span>
            Tarefas pendentes
          </h3>
        </div>
        <div class="panel-body">

        <?php if(mysqli_num_rows($tarefas_pendentes) == 0): ?>
          Não tem nada aqui não, eba!
        <?php else: ?>
        <?php while($tarefa = mysqli_fetch_assoc($tarefas_pendentes)): ?>
        <!-- INICIO TAREFA PENDENTE  -->
        <div class="alert alert-info" role="alert">
          <span class="tarefa">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <?php echo $tarefa["titulo"] ?>
          </span>
          <div class="pull-right">
            <a href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $tarefa["id"] . "&" . "acao=feito" ?>">
              <button aria-label="Feito" class="btn btn-sm btn-success" type="button">
                <span class="glyphicon glyphicon-ok"></span> Feito!
              </button>
            </a>

            <a href="<?php echo ('edita_tarefa.php' . '?id=' . $tarefa['id']); ?>">
              <button aria-label="Editar" class="btn btn-sm btn-info" type="button">
                <span class="glyphicon glyphicon-edit"></span>
              </button>
            </a>

            <a href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $tarefa["id"] . "&" . "acao=remover" ?>">
              <button aria-label="Remover" class="btn btn-sm btn-danger" type="button">
                <span class="glyphicon glyphicon-trash"></span>
              </button>
            </a>
          </div>
        </div>
        <!-- FIM TAREFA PENDENTE -->
        <?php endwhile;?>
        <?php endif;?>
          
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            <span class="glyphicon glyphicon-ok"></span>
            Tarefas concluídas
          </h3>
        </div>
        <div class="panel-body">
        <?php if(mysqli_num_rows($tarefas_concluidas) == 0): ?>
          Não tem nada aqui não, poxa :C!
        <?php else: ?>
        <?php while($tarefa = mysqli_fetch_assoc($tarefas_concluidas)): ?>
          <!-- INICIO TAREFA CONCLUIDA -->
          <div class="alert alert-success" role="alert">
            <span class="tarefa">
              <span class="glyphicon glyphicon-chevron-right"></span>
              <?php echo $tarefa["titulo"] ?>
            </span>
            <div class="pull-right">
              <a href="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $tarefa["id"] . "&" . "acao=desfeito" ?>">
                <button aria-label="Desfazer" class="btn btn-sm btn-warning" type="button">
                  <span class="glyphicon glyphicon-remove"></span> Desfazer
                </button>
              </a>
            </div>
          </div>
          <!-- FIM TAREFA CONCLUIDA -->
          <?php endwhile;?>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>