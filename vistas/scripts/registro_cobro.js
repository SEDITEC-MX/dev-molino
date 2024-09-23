var tabla;

// Inicio Función de inicio
function init()
{
	//Ocultamos el formulario
	//mostrarform(false);
	
	//Llamamos a la función listar
	//listar();
	
	//Al dar clic en el botón guardar llamamos a guardaryeditar con la información en e
	$("#formulario").on("submit",function(e)
	{
		registrar_cobro(e);	
	});

	$( document ).ready(function() {
	  listarNombreEventos();
	});

	//Mostrar sus respectivos historiales de cobro
	$("#id_evento").on("change", function(){
		var id_evento = $(this).val();
		$("#listadoregistros tbody").empty();
		listarCobros(id_evento);
	});

	$('#listadoregistros > table tbody').on( 'click', '.eliminar', function () { //Eliminar el cobro
    	var boton = $(this)
	    var cobro = $(this).parent().parent().attr("id")
	    var fecha = $(this).parent().siblings(".fecha").text()
	    var monto = $(this).parent().siblings(".monto").text()
	    var metodo = $(this).parent().siblings(".metodo").text()
	    var correo = $(this).parent().siblings(".correo").text()
	    var nombre_evento = $("#id_evento option:selected").text();


    	bootbox.confirm("¿Está seguro de eliminar el cobro?", function(result){
			if(result){
				boton.text('Cargando..');
				boton.attr('disabled', 'true');
				{
	      	$.post("../ajax/registro_cobro.php?op=eliminar_cobro", 
	      		{
	      			id_cobro : cobro,
	      			fecha_cobro: fecha,
	      			monto_cobro: monto,
	      			metodo_cobro: metodo,
	      			email_cobro: correo,
	      			nombre_evento: nombre_evento
	      		}, function(e){
	      			boton.text('Borrar Cobro')
							boton.removeAttr('disabled')
	      			bootbox.alert(e);
	          	tabla.ajax.reload();
	      	});
      	}	
			}
		})
	});

	$('#listadoregistros > table tbody').on( 'click', '.reenviar', function () { //Reenviar comprobante
    	var boton = $(this)
	    var cobro = $(this).parent().parent().attr("id")
	    var fecha = $(this).parent().siblings(".fecha").text()
	    var monto = $(this).parent().siblings(".monto").text()
	    var metodo = $(this).parent().siblings(".metodo").text()
	    var correo = $(this).parent().siblings(".correo").text()
	    var notas = $(this).parent().siblings(".notas").text()
	    var nombre_evento = $("#id_evento option:selected").text();

    	bootbox.confirm("¿Está seguro de reenviar el comprobante?", function(result){
			if(result){
				boton.text('Cargando..');
				boton.attr('disabled', 'true');
				{
	      	$.post("../ajax/registro_cobro.php?op=reenviar_comprobante", 
	      		{
	      			id_cobro : cobro,
	      			fecha_cobro: fecha,
	      			monto_cobro: monto,
	      			metodo_cobro: metodo,
	      			email_cobro: correo,
	      			nombre_evento: nombre_evento
	      		}, function(e){
	      			boton.text('Reenviar Comprobante')
							boton.removeAttr('disabled')
	      			bootbox.alert(e);
	      	});
      	}	
			}
		})
	});



	//Cargamos los items al select proveedor
	//$.post("../ajax/evento.php?op=selectCategoria", function(r){
	//	$("#id_categoria_servicio").html(r);
	//	$('#id_categoria_servicio').selectpicker('refresh');
	//});
//
	//$('#mCompras').addClass("treeview active");
	//$('#lIngresos').addClass("active");	
}


/*
Columnas de la tabla 'cobros'
id_cobro int(11)
id_evento int(11)
fecha_cobro datetime(6)
monto_cobro decimal(15,2)
metodo_cobro varchar(45)
email_cobro varchar(200)

$id_cobro=isset($_POST["id_cobro"])? limpiarCadena($_POST["id_cobro"]):"";
$id_evento=isset($_POST["id_evento"])? limpiarCadena($_POST["id_evento"]):"";
$fecha_cobro=isset($_POST["fecha_cobro"])? limpiarCadena($_POST["fecha_cobro"]):"";
$monto_cobro=isset($_POST["monto_cobro"])? limpiarCadena($_POST["monto_cobro"]):"";
$metodo_cobro=isset($_POST["metodo_cobro"])? limpiarCadena($_POST["metodo_cobro"]):"";
$email_cobro=isset($_POST["email_cobro"])? limpiarCadena($_POST["email_cobro"]):"";
$notas_cobro=isset($_POST["notas_cobro"])? limpiarCadena($_POST["notas_cobro"]):"";



*/

function limpiar()
{
	// $("#id_evento").val("");
	// $('#id_evento').selectpicker('refresh');
	$("#fecha_cobro").val("");
	$("#monto_cobro").val("");
	$("#metodo_cobro").val("");
	$('#metodo_cobro').selectpicker('refresh');
	$("#email_cobro").val("");
	$("#notas_cobro").val("");
}

function listarServicios1()
{
	tabla=$('#tblpagos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		           
		        ],
		"ajax":
				{
					url: '../ajax/evento.php?op=listarServicios',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();


}


function listarNombreEventos(){

	$.ajax({
		url: "../ajax/registro_cobro.php?op=listarEventos",
	    type: "POST",
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    { 
	    	$("#id_evento").empty(datos);               
	      $("#id_evento").append(datos);
	  		$("#id_evento").selectpicker("refresh");
	    },
	    error: function(err){
	    	$("#id_evento").empty(datos);               
	    	$("#id_evento").append("No se pudieron cargar los eventos");
	    }

	});
}

function listarCobros(id_evento){
	var formData = new FormData();
	formData.append('id_evento', id_evento);

	tabla=$('#detalles').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		           
		        ],
		"columnDefs": [
    	{ className: "fecha", "targets": [ 1 ] },
    	{ className: "monto", "targets": [ 2 ] },
    	{ className: "metodo", "targets": [ 3 ] },
    	{ className: "correo", "targets": [ 4 ] },
    	{ className: "notas", "targets": [ 5 ] }
  	],
  	"createdRow": function( row, data, dataIndex ) {
  		let id = data[6]; 
    	$(row).prop('id', id).data('id', id); 
	  },
		"ajax":
				{
					url: '../ajax/registro_cobro.php?op=listarCobros',
					type : "POST",
					// dataType : "json",
					data: {
						id_evento: id_evento
					},				
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"autoWidth": false,
		"language": {
	     "loadingRecords": "Cargando..."
	  },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


function mostrarform1(flag)
{
	//limpiar();
	if(flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnagregar").hide();
		$("#boton_verde_agregar").hide();
		listarServicios();
		$("#guardar").hide();
		$("#btnGuardar").show();
		$("#btnCancelar").show();
		detalles=0;
		$("#btnAgregarArt").show();
		$("#abrirModal").show();
		

	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#boton_verde_agregar").show();
	}
}

function cancelarform()
{
	limpiar();
	mostrarform(false);
}

function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
		
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/registro_cobro.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 100,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}



function registrar_cobro(e)
{
	//modificarSubototales();
	
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	$("#formularioregistros .loading-gif").removeClass("hidden");
	var formData = new FormData($("#formulario")[0]);
	var nombre_evento = $("#id_evento option:selected").text();

	formData.append('nombre_evento', nombre_evento);

	$.ajax({
		url: "../ajax/registro_cobro.php?op=registrar_cobro",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	   
	          tabla.ajax.reload();
						$("#formularioregistros .loading-gif").addClass("hidden");
	          //mostrarform(false);
	         // listar();
	    }

	});
	limpiar();
}

function mostrar(id_evento)
{
	 $.post("../ajax/registro_cobro.php?op=listarDetalle&id="+id_evento,function(r)
	 {
			$("#detalles").html(r);
	 });
}

function activar(id_evento)
{
	bootbox.confirm("¿Está seguro de regresar a programado el evento?", function(result){
		if(result)
        {
        	$.post("../ajax/evento.php?op=activar", {id_evento : id_evento}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
				location.reload();
        	});	
        }
	})
}

function anular(id_evento)
{
	bootbox.confirm("¿Está seguro de finalizar el evento?", function(result){
		if(result)
        {
        	$.post("../ajax/evento.php?op=anular", {id_evento : id_evento}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
				location.reload();
        	});	
        }
	})
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").show();

function agregarDetalle(id_servicio,nombre_servicio)
  {
  	var cantidad_detalle_evento=1;
    var precio_detalle_evento=1;
    
    if (id_servicio!="")
    {
    	var subtotal=cantidad_detalle_evento*precio_detalle_evento;
    	var fila='<tr class="filas" id="fila'+cont+'">'+
    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    	'<td><input type="hidden" name="id_cobro[]" value="'+id_cobro+'">'+fecha_cobro+'</td>'+
    	'<td><input type="number" name="metodo_cobro[]" id="metodo_cobro[]" value="'+metodo_cobro+'"></td>'+
    	'<td><input type="email" name="monto_cobro[]" id="monto_cobro[]" value="'+monto_cobro+'"></td>'+
    	'<td><input type="email" name="email_cobro[]" id="email_cobro[]" value="'+email_cobro+'"></td>'+
    	'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
    	'<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
    	'</tr>';
    	cont++;
    	detalles=detalles+1;
    	$('#detalles').append(fila);
    	modificarSubototales();
    }
    else
    {
    	alert("Error al ingresar el detalle, revisar los datos del servicio");
    }
  }

  function modificarSubototales()
  {
  	var cant = document.getElementsByName("cantidad_detalle_evento[]");
    var prec = document.getElementsByName("precio_detalle_evento[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i <cant.length; i++) {
    	var inpC=cant[i];
    	var inpP=prec[i];
    	var inpS=sub[i];

    	inpS.value=inpC.value * inpP.value;
    	document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
    }
    calcularTotales();

  }


  function calcularTotales(){
  	var sub = document.getElementsByName("subtotal");
  	var total = 0.0;
	
  	for (var i = 0; i <sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}
	$("#total").html("$ " + total);
    $("#total_evento").val(total);
    evaluar();
  }

  function evaluar(){
  	if (detalles>0)
    {
      $("#btnGuardar").show();
    }
    else
    {
      $("#btnGuardar").show();
      cont=0;
    }
  }

  function eliminarDetalle(indice){
  	$("#fila" + indice).remove();
  	//calcularTotales();
	modificarSubototales();
  	detalles=detalles-1;
  	evaluar();
  }

  $(function () {
    $(document).on('click', '.borrar', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
		modificarSubototales();
    });
	});

init();