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
		registrar_pago(e);	
	});

	$( document ).ready(function() {
	  listarNombreEventos();
	  listarNombreProveedores();
	});

	//Mostrar sus respectivos historiales de pago
	$("#id_evento").on("change", function(){
		var id_evento = $(this).val();
		$("#listadoregistros tbody").empty();
		listarPagos(id_evento);
	});


	//Cargamos los items al select categoria
	$.post("../ajax/empleados.php?op=selectCategoria", function(r){
	    $("#id_categoria_servicio").html(r);
	    $('#id_categoria_servicio').selectpicker('refresh');

		//El select proveedor del modal
		$("#modal_editar_pago_id_categoria_servicio").html(r);
		$('#modal_editar_pago_id_categoria_servicio').selectpicker('refresh');
	});

	$('#listadoregistros > table tbody').on( 'click', '.eliminar', function () { //Eliminar el pago
    	var boton = $(this)
	    var pago = $(this).parent().parent().attr("id")
	    var fecha = $(this).parent().siblings(".fecha").text()
	    var nombre_proveedor = $(this).parent().siblings(".nombre_proveedor").text()
	    var monto = $(this).parent().siblings(".monto").text()
	    var metodo = $(this).parent().siblings(".metodo").text()
	    var correo = $(this).parent().siblings(".correo").text()
			var nombre_evento = $("#id_evento option:selected").text();

    	bootbox.confirm("¿Está seguro de eliminar el pago?", function(result){
			if(result){
				boton.text('Cargando..');
				boton.attr('disabled', 'true');
				{
	      	$.post("../ajax/registro_pago.php?op=eliminar_pago", 
	      		{
	      			id_pago : pago,
	      			fecha_pago: fecha,
	      			nombre_proveedor: nombre_proveedor,
	      			monto_pago: monto,
	      			metodo_pago: metodo,
	      			email_pago: correo,
	      			nombre_evento: nombre_evento
	      			
	      		}, function(e){
	      			boton.text('Borrar Pago')
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
	    var pago = $(this).parent().parent().attr("id")
	    var fecha = $(this).parent().siblings(".fecha").text()
	    var nombre_proveedor = $(this).parent().siblings(".nombre_proveedor").text()
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
	      	$.post("../ajax/registro_pago.php?op=reenviar_comprobante", 
	      		{
	      			id_pago : pago,
	      			fecha_pago: fecha,
	      			nombre_proveedor: nombre_proveedor,
	      			monto_pago: monto,
	      			metodo_pago: metodo,
	      			email_pago: correo,
					notas_pago: notas,
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

	$('#listadoregistros > table tbody').on( 'click', '.editar-pago', function () { 
	    let pago = $(this).parent().parent().attr("id");
	    let fecha = $(this).parent().siblings(".fecha").text();
		let nombre_proveedor = $(this).parent().siblings(".nombre_proveedor").text()
	    let monto = $(this).parent().siblings(".monto").text();
	    let id_categoria_servicio = $(this).parent().parent().data('id_categoria_servicio');
		//Si está como nulo porque no tiene categoría, tomará el valor de la primera opción
		if (id_categoria_servicio == null){
			id_categoria_servicio = $('#modal_editar_pago_id_categoria_servicio')[0][0].value; 
		}
		$('#modal-pago').modal('show');
		
		$("#modal_editar_pago_id_pago").val(pago);
		$('#modal_editar_pago_fecha_pago').val(fecha);
		$('#modal_editar_pago_proveedor_pago').val(nombre_proveedor);
		$('#modal_editar_pago_monto_pago').val(monto);
		$('#modal_editar_pago_id_categoria_servicio').val(id_categoria_servicio);

	});

	$("#btnGuardarCategoriaServicio").on("click", function (e) {
		let formData = new FormData();
		let id_pago = 	$("#modal_editar_pago_id_pago").val();
		let id_categoria_servicio = $("#modal_editar_pago_id_categoria_servicio").val();
		formData.append('id_pago', id_pago);
		formData.append('id_categoria_servicio', id_categoria_servicio);
		$.ajax({
			url: "../ajax/registro_pago.php?op=actualizarCategoriaServicio",
			type: "POST",
			contentType: false,
			processData: false,
			data: formData,
			success: function(resp)
			{ 
				alert(resp);
				tabla.ajax.reload();
			},
			error: function(err){
				$("#id_evento").empty(datos);               
				$("#id_evento").append("No se pudieron cargar los eventos");
			}
	
		});
	
	});

function limpiar()
{
	// $("#id_evento").val("");
	// $('#id_evento').selectpicker('refresh');
	$("#fecha_pago").val("");
	$("#monto_pago").val("");
	$("#metodo_pago").val("");
	$('#metodo_pago').selectpicker('refresh');
	$("#email_pago").val("");
	$("#notas_pago").val("");
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
		url: "../ajax/registro_pago.php?op=listarEventos",
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


function listarNombreProveedores(){

	$.ajax({
		url: "../ajax/registro_pago.php?op=listarProveedores",
	    type: "POST",
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    { 
	    	$("#id_proveedor").empty(datos);               
	      	$("#id_proveedor").append(datos);
	  		$("#id_proveedor").selectpicker("refresh");
	    },
	    error: function(err){
	    	$("#id_proveedor").empty(datos);               
	    	$("#id_proveedor").append("No se pudieron cargar los eventos");
	    }

	});
}

function listarPagos(id_evento){
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
		{ className: "opciones", "targets": [ 0 ] },
    	{ className: "fecha", "targets": [ 1 ] },
    	{ className: "nombre_proveedor", "targets": [ 2 ] },
    	{ className: "metodo", "targets": [ 3 ] },
    	{ className: "monto", "targets": [ 4 ] },
    	{ className: "correo", "targets": [ 5 ] },
    	{ className: "nombre_servicio", "targets": [ 6 ] },
    	{ className: "notas", "targets": [ 7 ] }
  	],
  	"createdRow": function( row, data, dataIndex ) {
  		let id = data[8]; 
  		let id_categoria_servicio = data[10]; 
    	$(row).prop('id', id).data('id', id); 
    	$(row).prop('id_categoria_servicio', id_categoria_servicio)
		.data('id_categoria_servicio', id_categoria_servicio); 
	  },
		"ajax":
				{
					url: '../ajax/registro_pago.php?op=listarPagos',
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
					url: '../ajax/registro_pago.php?op=listar',
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



function registrar_pago(e)
{
	//modificarSubototales();
	
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	$("#formularioregistros .loading-gif").removeClass("hidden");
	var formData = new FormData($("#formulario")[0]);
	var nombre_evento = $("#id_evento option:selected").text();
	formData.append('nombre_proveedor', $('#id_proveedor option:selected').text());
	formData.append('nombre_evento', nombre_evento);
	
	$.ajax({
		url: "../ajax/registro_pago.php?op=registrar_pago",
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
	 $.post("../ajax/registro_pago.php?op=listarDetalle&id="+id_evento,function(r)
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

function agregarDetalle(id_servicio,nombre_servicio){
  	var cantidad_detalle_evento=1;
    var precio_detalle_evento=1;
    
    if (id_servicio!="")
    {
    	var subtotal=cantidad_detalle_evento*precio_detalle_evento;
    	var fila='<tr class="filas" id="fila'+cont+'">'+
    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    	'<td><input type="hidden" name="id_pago[]" value="'+id_pago+'">'+fecha_pago+'</td>'+
    	'<td><input type="number" name="metodo_pago[]" id="metodo_pago[]" value="'+metodo_pago+'"></td>'+
    	'<td><input type="email" name="monto_pago[]" id="monto_pago[]" value="'+monto_pago+'"></td>'+
    	'<td><input type="email" name="email_pago[]" id="email_pago[]" value="'+email_pago+'"></td>'+
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
}

init();	