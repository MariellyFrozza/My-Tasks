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
      <h1 class="page-header">Editar tarefa</h1>

      <form action="index.php" method="POST">
        <div class="form-group">
          <input type="hidden" name="id" value="<?php echo($_GET["id"]);?>">
          <label class="sr-only" for="inputTarefa">Editar tarefa</label>
          <input required type="text" name="novo-titulo-tarefa" class="form-control" id="inputTarefa" value="">
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>