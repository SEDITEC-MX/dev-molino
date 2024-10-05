var tabla;

// Inicio Función de inicio
function init() {
  // Guardar formulario
  $("#formulario").on("submit", function (e) {
    registrar_caja(e);
  });

  // Carga inicial
  $(document).ready(function () {
    listarNombreEventos();
    listarNombreProveedores();
    const id_usuario = document.getElementById("id_usuario").dataset.idUsuario;
    listarPagos(id_usuario);
  });

  //Cargamos los items al select categoria
  $.post("../ajax/empleados.php?op=selectCategoria", function (r) {
    $("#id_categoria_servicio").html(r);
    $("#id_categoria_servicio").selectpicker("refresh");

    //El select proveedor del modal
    $("#modal_editar_pago_id_categoria_servicio").html(r);
    $("#modal_editar_pago_id_categoria_servicio").selectpicker("refresh");
  });

  $("#listadoregistros > table tbody").on("click", ".eliminar", function () {
    //Eliminar el pago
    var boton = $(this);
    var pago = $(this).parent().parent().attr("id");
    var fecha = $(this).parent().siblings(".fecha").text();
    var nombre_proveedor = $(this)
      .parent()
      .siblings(".nombre_proveedor")
      .text();
    var monto = $(this).parent().siblings(".monto").text();
    var metodo = $(this).parent().siblings(".metodo").text();
    var correo = $(this).parent().siblings(".correo").text();
    var nombre_evento = $("#id_evento option:selected").text();

    bootbox.confirm("¿Está seguro de eliminar el pago?", function (result) {
      if (result) {
        boton.text("Cargando..");
        boton.attr("disabled", "true");
        {
          $.post(
            "../ajax/registro_pago.php?op=eliminar_pago",
            {
              id_pago: pago,
              fecha_pago: fecha,
              nombre_proveedor: nombre_proveedor,
              monto_pago: monto,
              metodo_pago: metodo,
              email_pago: correo,
              nombre_evento: nombre_evento,
            },
            function (e) {
              boton.text("Borrar Pago");
              boton.removeAttr("disabled");
              bootbox.alert(e);
              tabla.ajax.reload();
            }
          );
        }
      }
    });
  });

  $("#listadoregistros > table tbody").on("click", ".reenviar", function () {
    //Reenviar comprobante
    var boton = $(this);
    var pago = $(this).parent().parent().attr("id");
    var fecha = $(this).parent().siblings(".fecha").text();
    var nombre_proveedor = $(this)
      .parent()
      .siblings(".nombre_proveedor")
      .text();
    var monto = $(this).parent().siblings(".monto").text();
    var metodo = $(this).parent().siblings(".metodo").text();
    var correo = $(this).parent().siblings(".correo").text();
    var notas = $(this).parent().siblings(".notas").text();
    var nombre_evento = $("#id_evento option:selected").text();

    bootbox.confirm(
      "¿Está seguro de reenviar el comprobante?",
      function (result) {
        if (result) {
          boton.text("Cargando..");
          boton.attr("disabled", "true");
          {
            $.post(
              "../ajax/registro_pago.php?op=reenviar_comprobante",
              {
                id_pago: pago,
                fecha_pago: fecha,
                nombre_proveedor: nombre_proveedor,
                monto_pago: monto,
                metodo_pago: metodo,
                email_pago: correo,
                notas_pago: notas,
                nombre_evento: nombre_evento,
              },
              function (e) {
                boton.text("Reenviar Comprobante");
                boton.removeAttr("disabled");
                bootbox.alert(e);
              }
            );
          }
        }
      }
    );
  });

  $("#listadoregistros > table tbody").on("click", ".editar-pago", function () {
    let pago = $(this).parent().parent().attr("id");
    let fecha = $(this).parent().siblings(".fecha").text();
    let nombre_proveedor = $(this)
      .parent()
      .siblings(".nombre_proveedor")
      .text();
    let monto = $(this).parent().siblings(".monto").text();
    let id_categoria_servicio = $(this)
      .parent()
      .parent()
      .data("id_categoria_servicio");
    //Si está como nulo porque no tiene categoría, tomará el valor de la primera opción
    if (id_categoria_servicio == null) {
      id_categoria_servicio = $(
        "#modal_editar_pago_id_categoria_servicio"
      )[0][0].value;
    }
    $("#modal-pago").modal("show");

    $("#modal_editar_pago_id_pago").val(pago);
    $("#modal_editar_pago_fecha_pago").val(fecha);
    $("#modal_editar_pago_proveedor_pago").val(nombre_proveedor);
    $("#modal_editar_pago_monto_pago").val(monto);
    $("#modal_editar_pago_id_categoria_servicio").val(id_categoria_servicio);
  });

  $("#btnGuardarCategoriaServicio").on("click", function (e) {
    let formData = new FormData();
    let id_pago = $("#modal_editar_pago_id_pago").val();
    let id_categoria_servicio = $(
      "#modal_editar_pago_id_categoria_servicio"
    ).val();
    formData.append("id_pago", id_pago);
    formData.append("id_categoria_servicio", id_categoria_servicio);
    $.ajax({
      url: "../ajax/registro_pago.php?op=actualizarCategoriaServicio",
      type: "POST",
      contentType: false,
      processData: false,
      data: formData,
      success: function (resp) {
        alert(resp);
        tabla.ajax.reload();
      },
      error: function (err) {
        $("#id_evento").empty(datos);
        $("#id_evento").append("No se pudieron cargar los eventos");
      },
    });
  });

  function limpiar() {
    $("#fecha_pago").val("");
    $("#monto_pago").val("");
    $("#metodo_pago").val("");
    $("#metodo_pago").selectpicker("refresh");
    $("#email_pago").val("");
    $("#notas_pago").val("");
  }

  function listarNombreEventos() {
    $.ajax({
      url: "../ajax/registro_pago.php?op=listarEventos",
      type: "POST",
      contentType: false,
      processData: false,

      success: function (datos) {
        $("#id_evento").empty(datos);
        $("#id_evento").append(datos);
        $("#id_evento").selectpicker("refresh");
      },
      error: function (err) {
        $("#id_evento").empty(datos);
        $("#id_evento").append("No se pudieron cargar los eventos");
      },
    });
  }

  function listarNombreProveedores() {
    $.ajax({
      url: "../ajax/registro_pago.php?op=listarProveedores",
      type: "POST",
      contentType: false,
      processData: false,

      success: function (datos) {
        $("#id_proveedor").empty(datos);
        $("#id_proveedor").append(datos);
        $("#id_proveedor").selectpicker("refresh");
      },
      error: function (err) {
        $("#id_proveedor").empty(datos);
        $("#id_proveedor").append("No se pudieron cargar los eventos");
      },
    });
  }

  function listarPagos(id_usuario) {
    var formData = new FormData();
    formData.append("id_usuario", id_usuario);

    tabla = $("#detalles")
      .dataTable({
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "Bfrtip", //Definimos los elementos del control de tabla
        buttons: [],
        columnDefs: [
          { className: "opciones", targets: [0] },
          { className: "fecha", targets: [1] },
          { className: "monto", targets: [2] },
          { className: "evento", evento: [3] },
          { className: "nombre_proveedor", targets: [4] },
          { className: "notas", targets: [5] },
          { className: "status", targets: [6] },
        ],
        createdRow: function (row, data, dataIndex) {
          let id = data[8];
          let id_categoria_servicio = data[10];
          $(row).prop("id", id).data("id", id);
          $(row)
            .prop("id_categoria_servicio", id_categoria_servicio)
            .data("id_categoria_servicio", id_categoria_servicio);
        },
        ajax: {
          url: "../ajax/registro_pago.php?op=listarPagosCaja",
          type: "POST",
          // dataType : "json",
          data: {
            id_usuario: id_usuario,
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        autoWidth: false,
        language: {
          loadingRecords: "Cargando...",
        },
        bDestroy: true,
        iDisplayLength: 10, //Paginación
        order: [[0, "desc"]], //Ordenar (columna,orden)
      })
      .DataTable();
  }

  $(document).on('click', '.eliminar_caja', function() {
    var id_pago = $(this).attr('id');
    console.log(id_pago);
    var confirmacion = confirm('¿Estás seguro de eliminar este pago?');
    if (confirmacion) {
        $.ajax({
            type: 'POST',
            url: '../ajax/registro_pago.php?op=eliminar_pago',
            data: {id_pago: id_pago},
            success: function(data) {
                if (data == 'Pago eliminado con éxito') {
                    alert('Pago eliminado con éxito');
                    location.reload();
                } else {
                    alert('Error al eliminar el pago');
                }
            }
        });
    }
});

  function registrar_caja(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#formularioregistros .loading-gif").removeClass("hidden");
    var formData = new FormData($("#formulario")[0]);
    var id_usuario = document.getElementById("id_usuario").dataset.idUsuario;
    formData.append(
      "nombre_proveedor",
      $("#id_proveedor option:selected").text()
    );
    if (id_evento === null || id_evento === undefined || id_evento === "") {
      formData.append("id_evento", 0); // Asignar un valor predeterminado si no se ha seleccionado nada o si el valor no es un número
    } else {
      var nombre_evento = $("#id_evento option:selected").text();
      formData.append("nombre_evento", nombre_evento);
    }
    formData.append("id_usuario", id_usuario);

    $.ajax({
      url: "../ajax/registro_pago.php?op=registrar_caja",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function (datos) {
        bootbox.alert(datos);
        tabla.ajax.reload();
        $("#formularioregistros .loading-gif").addClass("hidden");
      },
    });
    limpiar();
  }
}

init();
