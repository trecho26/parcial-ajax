<?php
include 'funciones/consultas.php';
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Parcial 03 - Lista de tareas</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css"
    />
    <script
      src="https://kit.fontawesome.com/a2e115a863.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="css/main.css" />
  </head>
  <body class="h-100">
    <section id="header">
      <!-- <div class="overlay"> -->
      <nav
        class="navbar navbar-expand-lg navbar-light" id="barra"
      >
        <div class="container">
          <a class="navbar-brand" href="#">
            Tareas
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="fas fa-bars"></span>
          </button>

          <div
            class="navbar-collapse collapse justify-content-end"
            id="navbarTogglerDemo02"
          >
            <ul class="navbar-nav justify-content-end">
              <li class="nav-item">
                <a class="nav-link" href="#">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#nosotros">Nosotros</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- </div> -->
    </section>

    <section id="tarea-form" class="mb-5 mt-5">
      <h1 class="text-center mb-4">Administra tus tareas diarias</h1>
      <form id="formulario" method="post">
        <div class="container">
          <div class="row">
            <div class="col-3">
              <input
                type="text"
                class="form-control"
                id="nombre-tarea"
                placeholder="Lavar el carro, sacar la basura, etc..."
                
              />
            </div>
            <div class="col-3">
              <input
                type="text"
                class="form-control"
                id="desc-tarea"
                placeholder="Descripción"
                
              />
            </div>
            <div class="col-3">
              <select name="urgencia" id="urgencia" class="form-control">
                <option value="">Urgencia</option>
                <option value="1">Baja</option>
                <option value="2">Media</option>
                <option value="3">Alta</option>
              </select>
            </div>
            <div class="col-3">
              <button type="submit" class="btn btn-block btn-tarea">
                Agregar tarea
              </button>
            </div>
          </div>
        </div>
      </form>
    </section>

    

    <section class="tareas container">
      <ul class="list-group" id="listado-tareas">
        <?php
          $tareas = obtenerTareas();
          if ($tareas->num_rows > 0) {
            //Si hay tareas
            foreach ($tareas as $tarea) {?>
              <li class="list-group-item d-flex tarea" id="<?php echo $tarea['id_tarea']; ?>">
                <span class="flex-grow-1" id="span<?php echo $tarea['id_tarea']; ?>"> <?php echo $tarea['nombre_tarea']; ?> </span>
                <span><i class="fas fa-check <?php echo ($tarea['estado'] === '1' ? 'completada' : ''); ?> "></i></span>
                <span><i id="i<?php echo $tarea['id_tarea']; ?>"class="fas fa-pen" data-toggle="modal" data-target="#exampleModalCenter" 
                onClick="agregarEdit('<?php echo $tarea['nombre_tarea']; ?>','<?php echo $tarea['descripcion']; ?>','<?php echo $tarea['urgencia']; ?>', '<?php echo $tarea['id_tarea']; ?>')"></i></span>
                <span><i class="fas fa-trash"></i></span>
              </li>
          <?php  }
          } else {
            //No hay tareas
             echo '<h3 class="text-center lista-vacia animated fadeInDown" style="color: #5148a5;">No hay tareas</h3>';
          }
        ?>
        
      </ul>
    </section>
    
    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Editar tarea</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formulario-editar" method="post">
              <div class="row">
                <div class="col-12 mb-2">
                  <input
                    type="text"
                    class="form-control"
                    id="nombre-tarea-edit"
                    value=""
                  />
                </div>
                <div class="col-12 mb-2">
                  <input
                    type="text"
                    class="form-control"
                    id="desc-tarea-edit"
                    value=""
                  />
                </div>
                <div class="col-12 mb-2">
                  <select name="urgencia-edit" id="urgencia-edit" class="form-control">
                    <option value="">Urgencia</option>
                    <option value="1">Baja</option>
                    <option value="2">Media</option>
                    <option value="3">Alta</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <input type="button" class="btn btn-primary" value="Guardar cambios" data-dismiss="modal" id="actualizar"></input>
              </div>
            </form>
          </div>
          
        </div>
      </div>
    </div>

    <div class="container">
      <div class="divider"></div>
    </div>

    <section class="nosotros text-center mb-5" id="nosotros">
      <h2 class="mb-4">Nosotros</h2>
      <div class="container d-flex justify-content-center">
        <div class="row">
          <div class="col-sm-12 col-md-4">
            <div class="card shadow-sm" style="width: 18rem; margin: 0 auto;">
              
              <i class="fas fa-address-card"></i>
              <div class="card-body">
                <p class="card-text" style="margin-bottom: 0px;">Lara Gárate Héctor Alfonso
                </p>
                <span class="text-muted">4-1 Informática</span>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-4">
            <div class="card shadow-sm" style="width: 18rem; margin: 0 auto;">
              
              <i class="fas fa-address-card"></i>
              <div class="card-body">
                <p class="card-text" style="margin-bottom: 0px;">Quintero Dorado Jean Karlo
                </p>
                <span class="text-muted">4-1 Informática</span>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-4">
            <div class="card shadow-sm" style="width: 18rem; margin: 0 auto;">
              
              <i class="fas fa-address-card"></i>
              <div class="card-body">
                <p class="card-text" style="margin-bottom: 0px;">Nuñez Velarde Luis Roberto
                </p>
                <span class="text-muted">4-1 Informática</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    
  
    

    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/codigo.js"></script>
  </body>
</html>
