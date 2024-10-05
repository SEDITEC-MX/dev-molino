$(document).on('click', '.ver', function() {
  var rutaArchivo = $(this).data('ruta-archivo');
  // window.open(rutaArchivo, '_blank');
  window.open(rutaArchivo, 'VentanaEmergente', 'width=800,height=600');
});


$(document).ready(function () {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  let evento = urlParams.get("evento");
  mostrarArchivos(evento);

  $(".documentacion-subir").on("click", function (e) {
    let maxFileSize = 2; //En MB
    let inputGroup = $(this).siblings(".input-group");
    let inputFile = inputGroup.children("input");
    let file = inputFile.prop("files");
    file = file[0];
    let categoria = $(this).data("category");
    if (file === undefined) {
      alert("Falta seleccionar un archivo");
      return;
    }

    if (!tipoArchivoEsValido(file)) {
      alert("Solo se pueden permitir archivos PDF");
      return;
    }
    if (!tamanioArchivoEsValido(file, maxFileSize)) {
      //El parámetro maxsize está en MB
      alert("No se pueden subir archivos mayores a " + maxFileSize + "MB");
      return;
    }

    let formData = new FormData();
    formData.append("idEvento", evento);
    formData.append("archivo", file);
    formData.append("categoriaArchivo", categoria);

    $.ajax({
      url: "../ajax/archivos.php?op=subirArchivo",
      type: "POST",
      contentType: false,
      processData: false,
      data: formData,
      success: function (data) {
        data = JSON.parse(data);
        if (data["codigo"] == 1) {
          switch (categoria) {
            case "ine":
              $("#existeINE").removeClass("hidden");
              $("#noExisteINE").addClass("hidden");
              break;

            case "documentacion":
              $("#existeDocumentacion").removeClass("hidden");
              $("#noExisteDocumentacion").addClass("hidden");
              break;

            case "cotizacion":
              $("#existeCotizacion").removeClass("hidden");
              $("#noExisteCotizacion").addClass("hidden");
              break;
            case "comprobante":
              $("#existeComprobante").removeClass("hidden");
              $("#noExisteComprobante").addClass("hidden");
              break;
          }
        }
        alert(data["mensaje"]);
      },
    });
  });

  $(".ver-archivo").on("click", function (e) {
    let categoria = $(this).data("category");
    window.open(
      "../ajax/archivos.php?op=descargarArchivo&id_evento=" +
        evento +
        "&categoria=" +
        categoria
    );
  });

  $(".borrar-archivo").on("click", function (e) {
    if (confirm("Seguro que quieres borrar este archivo?")) {
      let categoria = $(this).data("category");
      let formData = new FormData();
      formData.append("id_evento", evento);
      formData.append("categoria", categoria);
      $.ajax({
        url: "../ajax/archivos.php?op=borrarArchivo",
        type: "POST",
        contentType: false,
        processData: false,
        data: formData,
        success: function (data) {
          data = JSON.parse(data);
          if (data["codigo"] == 1) {
            switch (categoria) {
              case "ine":
                $("#noExisteINE").removeClass("hidden");
                $("#existeINE").addClass("hidden");
                break;

              case "documentacion":
                $("#noExisteDocumentacion").removeClass("hidden");
                $("#existeDocumentacion").addClass("hidden");
                break;

              case "cotizacion":
                $("#noExisteCotizacion").removeClass("hidden");
                $("#existeCotizacion").addClass("hidden");
                break;
            }
          }
          alert(data["mensaje"]);
        },
      });
    }
  });
});

function mostrarArchivos(id_evento) {
  $.post(
    "../ajax/archivos.php?op=mostrarArchivos",
    { id_evento: id_evento },
    function (data, status) {
      data = JSON.parse(data);
      for (let i = 0; i < data.length; i++) {
        if (data[i]["categoriaArchivo"] == "ine") {
          $("#existeINE").removeClass("hidden");
          $("#noExisteINE").addClass("hidden");
        }

        if (data[i]["categoriaArchivo"] == "cotizacion") {
          $("#existeCotizacion").removeClass("hidden");
          $("#noExisteCotizacion").addClass("hidden");
        }

        if (data[i]["categoriaArchivo"] == "documentacion") {
          $("#existeDocumentacion").removeClass("hidden");
          $("#noExisteDocumentacion").addClass("hidden");
        }
      }
    }
  );
}

function tipoArchivoEsValido(file) {
  if (file["type"] != "application/pdf") {
    return false;
  }
  return true;
}

function tamanioArchivoEsValido(file, MaxSize) {
  let fileSize = file["size"];
  let fileMB = fileSize / 1024 ** 2;
  if (fileMB > MaxSize) {
    return false;
  }
  return true;
}
