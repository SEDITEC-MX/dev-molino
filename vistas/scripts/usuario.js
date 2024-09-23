// Variable global para la tabla
var tabla;

// Función de inicialización
function init() {
  // Oculta el formulario y muestra la lista de registros
  mostrarform(false);
  // Carga la lista de registros en la tabla
  listar();

  // Configura el evento submit del formulario para que llame a guardaryeditar
  $("#formulario").on("submit", function (e) {
    guardaryeditar(e);
  });

  // Carga los permisos desde el servidor y los inserta en el elemento con id "permisos"
  $.post("../ajax/usuario.php?op=permisos", function (r) {
    $("#permisos").html(r);
  });
}

// Función para limpiar los campos del formulario
function limpiar() {
  $("#id_usuario").val("");
  $("#nombre_usuario").val("");
  $("#telefono_usuario").val("");
  $("#email_usuario").val("");
  $("#usr_usuario").val("");
  $("#pass_usuario").val("");
  const checkboxes = document.querySelectorAll('#formulario input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.checked = false;
  });
}

// Función para mostrar u ocultar el formulario y los botones
function mostrarform(flag) {
  // Limpia los campos del formulario
  limpiar();

  if (flag) {
    // Si flag es verdadero, muestra el formulario y oculta la lista de registros
    $("#listadoregistros").hide();
    $("#formularioregistros").show();
    $("#btnGuardar").prop("disabled", false);
    $("#btnagregar").hide();
  } else {
    // Si flag es falso, muestra la lista de registros y oculta el formulario
    $("#listadoregistros").show();
    $("#formularioregistros").hide();
    $("#btnagregar").show();
  }
}

// Función para cancelar el formulario y volver a la vista de la lista
function cancelarform() {
  // Limpia los campos del formulario
  limpiar();
  // Muestra la lista de registros y oculta el formulario
  mostrarform(false);
}

// Función para listar los registros en una tabla
function listar() {
  tabla = $("#tbllistado")
    .dataTable({
      aProcessing: true, // Activa el procesamiento del DataTables
      aServerSide: true, // Activa la paginación y filtrado realizados por el servidor
      dom: "Bfrtip", // Define los elementos de control de la tabla
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdf"], // Botones para exportar datos
      ajax: {
        url: "../ajax/usuario.php?op=listar",
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText); // Muestra en la consola cualquier error en la solicitud AJAX
        },
      },
      bDestroy: true, // Permite destruir y recrear la tabla si es necesario
      iDisplayLength: 10, // Número de registros por página
      order: [[0, "desc"]], // Ordena por la primera columna en orden descendente
    })
    .DataTable();
}

// Función para guardar o editar un registro
function guardaryeditar(e) {
  e.preventDefault(); // Previene la acción predeterminada del evento submit
  $("#btnGuardar").prop("disabled", true); // Desactiva el botón de guardar mientras se procesa la solicitud

  const checkboxesSeleccionados = document.querySelectorAll(
    '#formulario input[type="checkbox"]:checked'
  );
  const valoresCheckbox = Array.from(checkboxesSeleccionados).map(
    (checkbox) => checkbox.value
  );
  const checkboxes = valoresCheckbox.join(",");

  // Obtiene los datos del formulario como un objeto FormData
  var formData = new FormData($("#formulario")[0]);

  formData.append("checkboxes", checkboxes);

  $.ajax({
    url: "../ajax/usuario.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false, // No establece el tipo de contenido para el cuerpo de la solicitud
    processData: false, // No procesa los datos del formulario

    success: function (datos) {
      bootbox.alert(datos); // Muestra un mensaje de alerta con la respuesta del servidor
      mostrarform(false); // Oculta el formulario y muestra la lista de registros
      tabla.ajax.reload(); // Recarga los datos en la tabla
    },
  });
  // Limpia los campos del formulario
  limpiar();
}

// Función para mostrar un registro en el formulario para edición
function mostrar(id_usuario) {
  $.post(
    "../ajax/usuario.php?op=mostrar",
    { id_usuario: id_usuario },
    function (data, status) {
      data = JSON.parse(data); // Convierte la respuesta JSON en un objeto
      mostrarform(true); // Muestra el formulario para edición
      $("#nombre_usuario").val(data.nombre_usuario);
      $("#telefono_usuario").val(data.telefono_usuario);
      $("#email_usuario").val(data.email_usuario);
      $("#usr_usuario").val(data.usr_usuario);
      // $("#pass_usuario").val(data.pass_usuario); // Se prueba la propuesta de IA
      $("#pass_usuario").val("");
      $("#id_usuario").val(data.id_usuario);

      // Divide el string en un arreglo
  const valoresCheckboxArray = data.checkboxes.split(',');
  // Selecciona los checkbox
  const checkboxes = document.querySelectorAll('#formulario input[type="checkbox"]');

  // Recorre el arreglo de valores y marca los checkbox correspondientes
  valoresCheckboxArray.forEach(valor => {
    const checkbox = Array.from(checkboxes).find(checkbox => checkbox.value === valor);
    if (checkbox) {
      checkbox.checked = true;
    }
  });

    }
  );
}

// Función para desactivar un usuario
function desactivar(id_usuario) {
  bootbox.confirm("¿Está seguro de desactivar el usuario?", function (result) {
    if (result) {
      $.post(
        "../ajax/usuario.php?op=desactivar",
        { id_usuario: id_usuario },
        function (e) {
          bootbox.alert(e); // Muestra un mensaje de alerta con la respuesta del servidor
          tabla.ajax.reload(); // Recarga los datos en la tabla
        }
      );
    }
  });
}

// Función para activar un usuario
function activar(id_usuario) {
  bootbox.confirm("¿Está seguro de activar el usuario?", function (result) {
    if (result) {
      $.post(
        "../ajax/usuario.php?op=activar",
        { id_usuario: id_usuario },
        function (e) {
          bootbox.alert(e); // Muestra un mensaje de alerta con la respuesta del servidor
          tabla.ajax.reload(); // Recarga los datos en la tabla
        }
      );
    }
  });
}

// Llama a la función init para inicializar la página
init();
