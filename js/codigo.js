//Animacion del NavBar
/*$(window).on("scroll", function() {
  if ($(window).scrollTop()) {
    $(".barra").addClass("fixed");
    $(".barra").removeClass("transparente");
  } else {
    $(".barra").removeClass("fixed");
    $(".barra").addClass("transparente");
  }
});*/
var banderaActualizado = false;

var prevScrollpos = window.pageYOffset;
$(window).on("scroll", function() {
  var currentScrollPos = window.pageYOffset;
  if (prevScrollpos > currentScrollPos) {
    $("#barra").css("top", "0px");
  } else {
    $("#barra").css("top", "-60px");
  }
  prevScrollpos = currentScrollPos;
});

//
eventListeners();

function eventListeners() {
  //Evento al mandar el formulario
  document
    .querySelector("#formulario")
    .addEventListener("submit", validarRegistro);

  //Eventos para las acciones de las tareas
  document.querySelector(".tareas").addEventListener("click", accionesTareas);

  //Agregar el metodo editar
}

function validarRegistro(e) {
  e.preventDefault();

  var nombre_tarea = document.querySelector("#nombre-tarea");
  var descripcion = document.querySelector("#desc-tarea");
  var urgencia = document.querySelector("#urgencia");

  if (
    nombre_tarea.value === "" ||
    descripcion.value === "" ||
    urgencia.value === ""
  ) {
    //La validación falló
    Swal.fire({
      icon: "warning",
      title: "Alto ahí",
      text: "Todos los campos deben ser llenados"
    });
  } else {
    //Ambos campos son correctos, mandar a ejecutar Ajax
    //Datos que se envian al servidor
    var datos = new FormData();
    datos.append("tarea", nombre_tarea.value);
    datos.append("descripcion", descripcion.value);
    datos.append("urgencia", urgencia.value);

    //Crear el llamado a Ajax
    var xhr = new XMLHttpRequest();
    //Abrir la conexion
    xhr.open("POST", "funciones/funciones.php", true);
    //Retorno de datos
    xhr.onload = function() {
      if (this.readyState === 4 && this.status === 200) {
        var response = JSON.parse(xhr.responseText);
        console.log(response);
        if (response.respuesta === "correcto") {
          Swal.fire({
            icon: "success",
            title: "¡Listo!",
            text: "Tu tarea ha sido agregada exitosamente"
          });
          //Insertar HTML
          var listaVacia = document.querySelectorAll(".lista-vacia");
          if (listaVacia.length > 0) {
            document.querySelector(".lista-vacia").remove();
          }
          var nuevaTarea = document.createElement("li");
          nuevaTarea.classList = "list-group-item d-flex animated fadeIn tarea";
          nuevaTarea.innerHTML = `
            <span class="flex-grow-1">${response.tarea}</span>
            <span><i class="fas fa-check"></i></span>
            <span><i class="fas fa-pen" data-toggle="modal" data-target="#exampleModalCenter" onClick="agregarEdit('${nombre_tarea.value}','${descripcion.value}','${urgencia.value}')"></i></span>
            <span><i class="fas fa-trash"></i></span>
          `;
          var listaTareas = document.querySelector("#listado-tareas");
          listaTareas.appendChild(nuevaTarea);
          nombre_tarea.value = "";
          descripcion.value = "";
          urgencia.value = "";
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Hubo un error con tu solicitud"
          });
        }
      }
    };
    //Enviar la petición
    xhr.send(datos);
  }
}

//Cambia el estado de las tareas, edita tareas, elimina tareas
function accionesTareas(e) {
  e.preventDefault();
  if (e.target.classList.contains("fa-check")) {
    if (e.target.classList.contains("completada")) {
      e.target.classList.remove("completada");
      cambiarEstadoTarea(e.target, 0);
    } else {
      e.target.classList.add("completada");
      cambiarEstadoTarea(e.target, 1);
    }
  } else if (e.target.classList.contains("fa-pen")) {
    console.log(e.target);
    document
      .querySelector("#actualizar")
      .addEventListener("click", () => editarTarea(e.target));
  } else if (e.target.classList.contains("fa-trash")) {
    Swal.fire({
      title: "¿Estás seguro(a)?",
      text: "Esta acción no se puede deshacer",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Eliminar tarea",
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (result.value) {
        var tareaEliminar = e.target.parentElement.parentElement;
        //Borrar de la base de datos
        eliminarTarea(tareaEliminar);
        //Borrar del HTML
        tareaEliminar.remove();
        Swal.fire("¡Eliminado!", "Tu tarea ha sido eliminada", "success");
      }
    });
  }
}

//Cambiar el estado de la tarea
function cambiarEstadoTarea(tarea, estado) {
  var id = tarea.parentElement.parentElement.id;

  //Crear el llamado Ajax
  var xhr = new XMLHttpRequest();

  //Datos a enviar
  var datos = new FormData();
  datos.append("id", id);
  datos.append("accion", "cambiarEstado");
  datos.append("estado", estado);

  //Abrir la conexion;
  xhr.open("POST", "funciones/funciones-tareas.php", true);

  //Onload
  xhr.onload = function() {
    if (this.readyState === 4 && this.status === 200) {
      var response = JSON.parse(xhr.responseText);
      console.log(response);
    }
  };

  //Enviar la peticion
  xhr.send(datos);
}

function agregarEdit(nombre, desc, urgencia, id) {
  var i = document.querySelector(`#i${id}`);
  if (!i.classList.contains("actualizada")) {
    document.querySelector("#nombre-tarea-edit").value = nombre;
    document.querySelector("#desc-tarea-edit").value = desc;
    document.querySelector("#urgencia-edit").value = urgencia;
  } else {
    console.log("Ya se modificó");
  }
}

//Editar tarea
function editarTarea(tarea) {
  var id = tarea.parentElement.parentElement.id;
  console.log(id);

  var nombre = document.querySelector("#nombre-tarea-edit").value;
  var desc = document.querySelector("#desc-tarea-edit").value;
  var urgen = document.querySelector("#urgencia-edit").value;

  console.log(nombre, desc, urgen);
  //Crear el llamado Ajax
  var xhr = new XMLHttpRequest();

  //Datos a enviar
  var datos = new FormData();
  datos.append("id", id);
  datos.append("nombre", nombre);
  datos.append("descripcion", desc);
  datos.append("urgencia", urgen);

  //Abrir la conexion;
  xhr.open("POST", "funciones/editarTarea.php", true);

  //Onload
  xhr.onload = function() {
    if (this.readyState === 4 && this.status === 200) {
      var response = xhr.responseText;
      console.log(response);
    }
  };

  //Enviar la peticion
  xhr.send(datos);

  //Mostrar el cambio de nombre
  var nombreLista = document.querySelector(`#span${id}`);
  nombreLista.innerText = nombre;

  //Actualizar el modal
  document.querySelector("#nombre-tarea-edit").value = nombre;
  document.querySelector("#desc-tarea-edit").value = desc;
  document.querySelector("#urgencia-edit").value = urgen;
  var i = document.querySelector(`#i${id}`);
  i.classList.add("actualizada");
}

//Eliminar tarea de la BD
function eliminarTarea(tarea) {
  var id = tarea.id;
  //Crear el llamado Ajax
  var xhr = new XMLHttpRequest();

  //Datos a enviar
  var datos = new FormData();
  datos.append("id", id);
  datos.append("accion", "eliminarTarea");
  datos.append("estado", 0);

  //Abrir la conexion;
  xhr.open("POST", "funciones/funciones-tareas.php", true);

  //Onload
  xhr.onload = function() {
    if (this.readyState === 4 && this.status === 200) {
      var response = xhr.responseText;
      console.log(response);

      //Comprobar que haya tareas restantes
      var listaTareasRestantes = document.querySelectorAll("li.tarea");
      if (listaTareasRestantes.length === 0) {
        document.querySelector(".list-group").innerHTML = `
        <h3 class="text-center lista-vacia" style="color: #5148a5;">No hay tareas</h3>`;
      }
    }
  };

  //Enviar la peticion
  xhr.send(datos);
}
