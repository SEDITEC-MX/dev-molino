var tabla;
var tablaCobros;
var tablaPagos;

$( document ).ready(function() {

	
	$("#formularioCobros").on("submit",function(e)
	{
		registrar_cobro(e);	
	});

	$("#formularioPagos").on("submit",function(e)
	{
		registrar_pago(e);	
	});

    $("#btnExport").on("click",  function(e) {
    	//variables para imprimir
      	var datos_tabla = document.getElementById("sheetjs");
      	var fecha_evento = [ ["Fecha del evento"], [$("#fecha_evento").val()] ];
      	var tipo_evento = [ ["Tipo de evento"], [$("#tipo_evento").val()] ];
      	var nombre_evento = [ ["Nombre(s) del cliente(s)"], [$("#nombre_evento").val()] ];
      	var invitados_evento = [ ["Número de invitados"], [$("#invitados_evento").val()] ];
      	var cotizacion_evento = [ ["Tipo de cotización"], [$("#cotizacion_evento").val()] ];
      	var notas_evento = [ ["Especificaciones adicionales"], [$("#notas_evento").val()] ];
		var nombre = $("#tipo_evento").val() + ": " + $("#fecha_evento").val();    

		var wb = XLSX.utils.book_new();
		wb.Props = {
        	Title: nombre,
        	CreatedDate: new Date()
      	};

      	wb.SheetNames.push("Evento");
      	var ws2 = XLSX.utils.table_to_sheet(datos_tabla, {origin: "B14"});
      	XLSX.utils.sheet_add_aoa(ws2, fecha_evento, {origin: "B2"});
      	XLSX.utils.sheet_add_aoa(ws2, tipo_evento, {origin: "D2"});
     	XLSX.utils.sheet_add_aoa(ws2, nombre_evento, {origin: "B8"});
     	XLSX.utils.sheet_add_aoa(ws2, invitados_evento, {origin: "B8"});
      	XLSX.utils.sheet_add_aoa(ws2, cotizacion_evento, {origin: "D8"});
      	XLSX.utils.sheet_add_aoa(ws2, notas_evento, {origin: "B11"});

      	// wb.Sheets["Evento"] = ws;

      	wb.Sheets["Evento"] = ws2;
      	var wscols = [
			{wch: 2},
			{wch: 25},
			{wch: 25},
			{wch: 15},
			{wch: 15}
		];

		wb.Sheets.Evento['!cols'] = wscols;
      	var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
      	saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), nombre + '.xlsx');
    });
});

function s2ab(s) { 
  	var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
  	var view = new Uint8Array(buf);  //create uint8array as viewer
  	for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
  	return buf;    
}

// Inicio Función de inicio
function init(){
	//Ocultamos el formulario
	mostrarform(true);
	cargarInfo();
	//Llamamos a la función listar
	listar();
	//Al dar clic en el botón guardar llamamos a guardaryeditar con la información en e
	$("#formulario").on("submit",function(e){
		guardaryeditar(e);	
	});

	//Cargamos los items al select proveedor
	$.post("../ajax/evento.php?op=selectCategoria", function(r){
		$("#id_categoria_servicio").html(r);
		$('#id_categoria_servicio').selectpicker('refresh');
		
		//El select proveedor del modal
		$("#modal_editar_pago_id_categoria_servicio").html(r);
		$('#modal_editar_pago_id_categoria_servicio').selectpicker('refresh');
	});

	//Cargamos los items al select categoria
	$.post("../ajax/empleados.php?op=selectCategoria", function(r){
	    $("#id_categoria_servicio").html(r);
	    $('#id_categoria_servicio').selectpicker('refresh');
	});

	$('#mCompras').addClass("treeview active");
	$('#lIngresos').addClass("active");	
}


function cargarInfo(){
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	let evento = urlParams.get('evento');
	let vista = urlParams.get('vista');
	esVistaCliente(vista);
	mostrar(evento);
	listarServicios();
	listarCategorias();
	listarCobros(evento);
	listarPagos(evento);
	listarNombreProveedores();
	//listarCobrosPorMetodo(); Es un método antiguo, por si se quiere volver a saber los cobros por tipo de cobro
	listarServiciosPorCategoria();

	listarPagosPorCategoria();
	
}

function esVistaCliente(vista){
	if (vista == "cliente" || checkboxes == 'b'){
		$("#pagos").hide();
		$("#utilidad").hide();
		$("#regresar").attr("href", "vista-clientes.php");
	}
}

function limpiar(){
	$("#id_evento").val("");
	$("#fecha_evento").val("");
	$("#nombre_evento").val("");
	$("#tipo_evento").val("");
	$("#cotizacion_evento").val("");
	$("#notas_evento").val("");
	$("#estado_evento").val("");
	$("#id_servicio").val("");
	$("#invitados_evento").val("");
	$("#cantidad_detalle_evento").val("");
	$("#precio_detalle_evento").val("");
	$("#total_evento").val("");
	$(".filas").remove();
	$("#total").html("0");
	$('#tipo_evento').selectpicker('refresh');
	$('#cotizacion_evento').selectpicker('refresh');
}


function mostrarform(flag){
	//limpiar();
	// if(flag){
	// 	$("#listadoregistros").hide();
	// 	$("#formularioregistros").show();
	// 	$("#btnagregar").hide();
	// 	$("#boton_verde_agregar").hide();
	// 	listarServicios();
	// 	$("#guardar").hide();
	// 	$("#btnGuardar").show();
	// 	$("#btnCancelar").show();
	// 	detalles=0;
	// 	$("#btnAgregarArt").show();
	// 	$("#abrirModal").show();
	// }else{
	// 	$("#listadoregistros").show();
	// 	$("#formularioregistros").hide();
	// 	$("#btnagregar").show();
	// 	$("#boton_verde_agregar").show();
	// }
}

function cancelarform(){
	limpiar();
	mostrarform(false);
}

function listar(){
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdf',
			'pageLength'
		],
		'pageLength': 30,
		language: {
			buttons: {
				pageLength: {
					_: "Mostrar %d filas",
					'-1': "Todos"
				}
			},
      	},
		'aLengthMenu': [[ 10, 20, 50, 100 ,-1],[10,20,50,100,"Todos"]],
		"ajax":
		{
			url: '../ajax/evento.php?op=listar',
			type : "get",
			dataType : "json",						
			error: function(e){
				console.log(e.responseText);	
			}
		},
		"bDestroy": true,
		// "iDisplayLength": 100,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

function listarServicios(){
	tabla=$('#tblarticulos').dataTable({
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [],
		"ajax":{
			url: '../ajax/evento.php?op=listarServicios',
			type : "get",
			dataType : "json",						
			error: function(e){
				console.log(e.responseText);	
				}
			},
		"initComplete": function(settings, json){
			calcularSaldoPendiente();
		},
		"bDestroy": true,
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


function listarCobros(evento){

	var formData = new FormData();
	formData.append('id_evento', id_evento);

	tablaCobros = $('#detallesCobros').dataTable(
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
		"ajax":{
			url: '../ajax/registro_cobro.php?op=listarCobros',
			type : "POST",
			// dataType : "json",
			data: {
				id_evento: evento
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
	    "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
		"initComplete": function( settings, json ) {
			if (json['iTotalDisplayRecords'] > 0){
				let tamanio = json['aaData'].length - 1;
				let datos = json['aaData'][tamanio];
				let total = datos[7];
				$("#totalCobros").text(total)
				calcularSaldoPendiente();
			}
		 }
	}).DataTable();
}

function listarPagos(evento){
	tablaPagos = $('#detallesPagos').dataTable({
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		           
		        ],
		"columnDefs": [
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
    	$(row).prop('id', id).data('id', id); 
	  },
		"ajax":{
			url: '../ajax/registro_pago.php?op=listarPagos&variacion=0',
			type : "POST",
			// dataType : "json",
			data: {
				id_evento: evento
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
	    "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
		"initComplete": function( settings, json ) {
			if (json['iTotalDisplayRecords'] > 0){
				let tamanio = json['aaData'].length - 1;
				let datos = json['aaData'][tamanio];
				let total = datos[9];
				$("#totalPagos").text(total)
				calcularSaldoPendiente();
			}
		 }
	}).DataTable();
}

function guardaryeditar(e){
	modificarSubototales();
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/evento.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos){                    
	        bootbox.alert(datos);
			actualizarTablaaExportar();
	        // mostrarform(false);
          	listar();
			calcularSaldoPendiente();
			//listarCobrosPorMetodo();
			listarServiciosPorCategoria();

	    }
	});
	// limpiar();
}


function mostrar(id_evento){
	$.post("../ajax/evento.php?op=mostrar",{id_evento : id_evento}, function(data, status){
		data = JSON.parse(data);
		if (data === null) {
			$("#not-found").removeClass('hidden');
			$("#contenido-evento").addClass('hidden');
		} else{
			mostrarform(true);
			$("#id_evento").val(data.id_evento);
			$("#estado_evento").val(data.estado_evento);
			$("#fecha_evento").val(data.fecha_evento);
			$("#tipo_evento").val(data.tipo_evento);
			$('#tipo_evento').selectpicker('refresh');
			$("#nombre_evento").val(data.nombre_evento);
			$("#invitados_evento").val(data.invitados_evento);
			$("#cotizacion_evento").val(data.cotizacion_evento);
			$('#cotizacion_evento').selectpicker('refresh');
			$("#notas_evento").val(data.notas_evento);
			$("#total_evento").val(data.total_evento);
			$("#total").text(data.totalFormato)
			$("#btnGuardar").show();
			$("#abrirModal").show();
			$(".nombre_evento_modal").val(data.nombre_evento);
		}
 	});

	$.post("../ajax/evento.php?op=listarDetalleEvento&id="+id_evento,function(r){
		$("#detalles").html(r);
	});

 	$.post("../ajax/evento.php?op=listarTablaAExportar&id="+id_evento,function(r){
		$("#sheetjs").html(r);
	});
}

function activar(id_evento){
	bootbox.confirm("¿Está seguro de regresar a programado el evento?", function(result){
		if(result){
			$.post("../ajax/evento.php?op=activar", {id_evento : id_evento}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
				location.reload();
			});	
		}
	});
}

function anular(id_evento){
	bootbox.confirm("¿Está seguro de finalizar el evento?", function(result){
		if(result){
			$.post("../ajax/evento.php?op=anular", {id_evento : id_evento}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
				location.reload();
			});	
		}
	});
}



function actualizarTablaaExportar(){ //Función para actualizar la tabla que exporta a excel. La invoca el botón de guardar
	// $("#detalles").
	$("#sheetjs > tbody").empty();
	$("#detalles > tbody > tr").each(function () {
		let servicio = $(this).find('td').eq(1).text();
		let categoria = $(this).find('td').eq(2).text();
		let cantidad = parseFloat($(this).find('td').eq(3).find("input").val());
		let precio = parseFloat($(this).find('td').eq(4).find("input").val());
		let subtotal = $(this).find('td').eq(5).text();
		precio = precio.toFixed(2)
		cantidad = cantidad.toFixed(2)
    	$("#sheetjs > tbody").append("<tr><td>"+servicio+"</td><td>"+categoria+"</td><td>"+cantidad+"</td><td>"+precio+"<td>"+subtotal+"</td></tr>");
	});

	let total = $("#detalles #total").text();
	$("#sheetjs #total").text(total);
}



//Declaración de variables necesarias para trabajar con las compras y
//sus detalles

var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").show();

function agregarDetalle(id_servicio,nombre_servicio, categoria_servicio){
  	var cantidad_detalle_evento=1;
    var precio_detalle_evento=1;
    cont = document.getElementsByName("subtotal[]").length;
    if (id_servicio!=""){
    	var subtotal=cantidad_detalle_evento*precio_detalle_evento;
    	var fila='<tr class="filas" id="fila'+cont+'">'+
    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    	'<td><input type="hidden" name="id_servicio[]" value="'+id_servicio+'">'+nombre_servicio+'</td>'+
    	'<td>'+categoria_servicio+'</td>'+
    	'<td><input type="number" class="form-control" name="cantidad_detalle_evento[]" id="cantidad_detalle_evento[]" value="'+cantidad_detalle_evento+'"></td>'+
    	'<td><input type="number" class="form-control" name="precio_detalle_evento[]" id="precio_detalle_evento[]" value="'+precio_detalle_evento+'"></td>'+
    	'<td><span name="subtotal[]" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
    	'<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
    	'</tr>';
    	cont++;
    	detalles=detalles+1;
    	$('#detalles').append(fila);
    	modificarSubototales();
    }else{
    	alert("Error al ingresar el detalle, revisar los datos del servicio");
    }
}

function modificarSubototales(){
  	var cant = document.getElementsByName("cantidad_detalle_evento[]");
    var prec = document.getElementsByName("precio_detalle_evento[]");
    var sub = $("#detalles td span[name='subtotal[]']").map(function(){
    	return $(this);
    }).get();

    for (var i = 0; i <cant.length; i++) {
    	var inpC=cant[i];
    	var inpP=prec[i];
    	var inpS=sub[i];
    	var subtotal = inpC.value * inpP.value;
    	inpS.value=inpC.value * inpP.value;
    	$(sub)[i].text(new Intl.NumberFormat('es-MX', 
   		{style: "currency", currency: "MXN"}).format(inpS.value));
    }

    calcularTotales();
}


function calcularTotales(){
  	var cant = document.getElementsByName("cantidad_detalle_evento[]");
    var prec = document.getElementsByName("precio_detalle_evento[]");
  	var sub = $("#detalles td span[name='subtotal[]']").map(function(){
    	return $(this);
    }).get();
  	var total = 0.0;

  	for (var i = 0; i <sub.length; i++) {
  		var inpC=cant[i];
    	var inpP=prec[i];
  		var subtotal = inpC.value * inpP.value
		total += subtotal
	}

	total_con_formato = new Intl.NumberFormat('es-MX', 
	{style: "currency", currency: "MXN"}).format(total);
	
	$("#total").text(total_con_formato);
    $("#total_evento").val(total);
    evaluar();
}

function evaluar(){
  	if (detalles>0){
    	$("#btnGuardar").show();
    }else{
      	$("#btnGuardar").show();
      	cont=0;
    }
}

function registrar_cobro(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	let id_evento = urlParams.get('evento');
	let nombre_evento = $("#nombreEventoCobro").val();

	$("#formularioCobros .loading-gif").removeClass("hidden");
	let formData = new FormData($("#formularioCobros")[0]);	
	formData.append('id_evento', id_evento);
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
	          tablaCobros.ajax.reload(function(json){
				if (json['iTotalDisplayRecords'] > 0){
					let tamanio = json['aaData'].length - 1;
					let datos = json['aaData'][tamanio];
					let total = datos[7];
					$("#totalCobros").text(total);
					calcularSaldoPendiente();
				}
			  });
			$("#formularioCobros .loading-gif").addClass("hidden");
	          //mostrarform(false);
	         // listar();
			$("#monto_cobro").val("");
			$("#metodo_cobro").val("");
			$("#notas_cobro").val("");
	    }

	});
}

function registrar_pago(e)
{
	
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#formularioPagos .loading-gif").removeClass("hidden");
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	let id_evento = urlParams.get('evento');
	let nombre_evento = $("#nombreEventoPago").val();

	let formData = new FormData($("#formularioPagos")[0]);	
	formData.append('id_evento', id_evento);
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
	        tablaPagos.ajax.reload(function(json){
				if (json['iTotalDisplayRecords'] > 0){
					let tamanio = json['aaData'].length - 1;
					let datos = json['aaData'][tamanio];
					let total = datos[9];
					$("#totalPagos").text(total);
					calcularSaldoPendiente();
					listarPagosPorCategoria();
				}
			});
			$("#formularioPagos .loading-gif").addClass("hidden");
	          //mostrarform(false);
	         // listar();
			$("#monto_pago").val("");
			$("#metodo_pago").val("");
			$("#notas_pago").val("");
	    }
	});

}

function eliminarDetalle(indice){
  	$("#fila" + indice).remove();
  	//calcularTotales();
	modificarSubototales();
  	detalles=detalles-1;
  	evaluar();
}

$('#detallesCobros tbody').on( 'click', '.reenviar', function () { //Reenviar comprobante
	var boton = $(this)
	var cobro = $(this).parent().parent().attr("id")
	var fecha = $(this).parent().siblings(".fecha").text()
	var monto = $(this).parent().siblings(".monto").text()
	var metodo = $(this).parent().siblings(".metodo").text()
	var correo = $(this).parent().siblings(".correo").text()
	var notas = $(this).parent().siblings(".notas").text()
	var nombre_evento = $("#nombreEventoCobro").val();

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
				  nombre_evento: nombre_evento,
				  notas_cobro: notas
				  
			  }, function(e){
				  boton.text('Reenviar Comprobante')
						boton.removeAttr('disabled')
				  bootbox.alert(e);
		  });
	  }	
		}
	})
});


$('#detallesCobros tbody').on( 'click', '.eliminar', function () { //Eliminar el cobro
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
				  tablaCobros.ajax.reload(function(json){
					if (json['iTotalDisplayRecords'] > 0){
						let tamanio = json['aaData'].length - 1;
						let datos = json['aaData'][tamanio];
						let total = datos[7];
						$("#totalCobros").text(total);
						calcularSaldoPendiente();
					}
				  });
		  });
	  }	
		}
	})
});

$('#detallesPagos tbody').on( 'click', '.reenviar', function () { //Reenviar comprobante
	let boton = $(this)
	let pago = $(this).parent().parent().attr("id")
	let fecha = $(this).parent().siblings(".fecha").text()
	let monto = $(this).parent().siblings(".monto").text()
	let metodo = $(this).parent().siblings(".metodo").text()
	let correo = $(this).parent().siblings(".correo").text()
	let notas = $(this).parent().siblings(".notas").text()
	let nombre_proveedor = $(this).parent().siblings(".nombre_proveedor").text()
	let nombre_evento = $("#nombreEventoPago").val();


	bootbox.confirm("¿Está seguro de reenviar el comprobante?", function(result){
		if(result){
			boton.text('Cargando..');
			boton.attr('disabled', 'true');
			{
		  $.post("../ajax/registro_pago.php?op=reenviar_comprobante", 
			  {
				  id_pago: pago,
				  fecha_pago: fecha,
				  monto_pago: monto,
				  nombre_proveedor: nombre_proveedor,
				  metodo_pago: metodo,
				  email_pago: correo,
				  nombre_evento: nombre_evento,
				  notas_pago: notas
			  }, function(e){
				  boton.text('Reenviar Comprobante')
						boton.removeAttr('disabled')
				  bootbox.alert(e);
		  });
	  }	
		}
	})
});


$('#detallesPagos tbody').on( 'click', '.eliminar', function () { //Eliminar el pago
	let boton = $(this)
	let pago = $(this).parent().parent().attr("id")
	let fecha = $(this).parent().siblings(".fecha").text()
	let monto = $(this).parent().siblings(".monto").text()
	let metodo = $(this).parent().siblings(".metodo").text()
	let correo = $(this).parent().siblings(".correo").text()
	let notas = $(this).parent().siblings(".notas").text()
	let nombre_proveedor = $(this).parent().siblings(".nombre_proveedor").text()
	let nombre_evento = $("#nombreEventoPago").val();

	bootbox.confirm("¿Está seguro de eliminar el pago?", function(result){
		if(result){
			boton.text('Cargando..');
			boton.attr('disabled', 'true');
			{
		  $.post("../ajax/registro_pago.php?op=eliminar_pago", 
			  {
				id_pago: pago,
				fecha_pago: fecha,
				monto_pago: monto,
				nombre_proveedor: nombre_proveedor,
				metodo_pago: metodo,
				email_pago: correo,
				nombre_evento: nombre_evento,
				notas_pago: notas
			  }, function(e){
				  	boton.text('Borrar Pago')
					boton.removeAttr('disabled')
				  	bootbox.alert(e);
				  	tablaPagos.ajax.reload(function(json){
					if (json['iTotalDisplayRecords'] > 0){
						let tamanio = json['aaData'].length - 1;
						let datos = json['aaData'][tamanio];
						let total = datos[9];
						$("#totalPagos").text(total);
						listarPagosPorCategoria();
					}
				  });
		  });
	  }	
		}
	})
});

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

function listarCobrosPorMetodo(){
	$("#cobrosPorMetodo tbody").empty();
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	let id_evento = urlParams.get('evento');
	$.get( "../ajax/detalle_evento.php?op=cobros&id=" + id_evento) 
	.done(function(data){
		data = JSON.parse(data);
		let size = Object.keys(data).length
		for(let i = 0; i < size; i++){
			let precio = data[i]['monto']; 
			let categoria = data[i]['metodo_cobro'];
			precio = new Intl.NumberFormat('es-MX', 
   			{style: "currency", currency: "MXN"}).format(precio);
			let concatenar = categoria + " - " + precio;
			$("#cobrosPorMetodo tbody").append("<tr><td></td><td>" + concatenar + "</td></tr>");
		}

		let total = 0;
		if (data[0] != undefined){
			total = data[0]['total']
		}
		
		$("#totalSumaCobrosSinFormato").val(total);

		total = new Intl.NumberFormat('es-MX', 
		{style: "currency", currency: "MXN"}).format(total);

		$("#totalSumaCobros").text(total);
		calcularUtilidad();
	})

}

function listarServiciosPorCategoria(){
	$("#serviciosPorCategoria tbody").empty();
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	let id_evento = urlParams.get('evento');
	$.get( "../ajax/detalle_evento.php?op=servicios&id=" + id_evento) 
	.done(function(data){
		data = JSON.parse(data);
		let size = Object.keys(data).length;
		console.log("El total es: " + size);
		// for(let i = 0; i < size; i++){
		// 	let precio = data[i]['costoServicio']; 
		// 	let categoria = data[i]['categoria'];
		// 	precio = new Intl.NumberFormat('es-MX', 
		// 	{style: "currency", currency: "MXN"}).format(precio);
		// 	let concatenar = categoria + " - " + precio;
		// 	$("#serviciosPorCategoria tbody").append("<tr><td></td><td>" + concatenar + "</td></tr>");
		// 	if (categoria == "No definido"){
		// 		$("#tablaUtilidad .no-definidos").next().text(precio);

		// 	}
		// 	$("#tablaUtilidad ." + categoria).next().text(precio);
		// }

		//   inicio de la prueba
		for (let i = 0; i < size; i++) {
			let precio = data[i]['costoServicio']; 
			let categoria = data[i]['categoria'];
		  
			// Formatear el precio como moneda
			precio = new Intl.NumberFormat('es-MX', 
			{ style: "currency", currency: "MXN" }).format(precio);
		  
			// Reemplazar espacios por guiones para la clase CSS
			let categoriaClass = categoria.replace(/\s+/g, '-');
		  
			// Concatenar categoría y precio
			let concatenar = categoria + " - " + precio;
		  
			// Agregar una nueva fila a la tabla #serviciosPorCategoria
			$("#serviciosPorCategoria tbody").append("<tr><td></td><td>" + concatenar + "</td></tr>");
		  
			// Si la categoría es "No definido"
			if (categoria === "No definido") {
			  $("#tablaUtilidad .no-definidos").next().text(precio);
			}
		  
			// Actualizar precio en #tablaUtilidad basado en la clase de la categoría
			// Asegúrate de que la clase se aplique correctamente en el HTML
			$("#tablaUtilidad").find(`.${categoriaClass}`).next().text(precio);
		  }
		  
		//   fin de la prueba

		let total = 0;
		if (data[0] != undefined){
			total = data[0]['total']
		}
		
		$("#totalSumaServiciosSinFormato").val(total);
		
		total = new Intl.NumberFormat('es-MX', 
		{style: "currency", currency: "MXN"}).format(total);
		console.log("El total es: " + total)
		$("#totalSumaServicios").text(total);
		$("#tablaUtilidad .categoria-total").next().text(total);
		calcularUtilidad();
	})

}

function listarPagosPorCategoria(){
	if (vista == "negocio" && checkboxes == 'h'){
	$("#pagosPorMetodo tbody").empty();

	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	let id_evento = urlParams.get('evento');
	$.get( "../ajax/detalle_evento.php?op=pagos&id=" + id_evento) 
	.done(function(data){
		data = JSON.parse(data);
		let size = Object.keys(data).length;
		for(let i = 0; i < size; i++){
			let precio = data[i]['monto']; 
			let categoria = data[i]['categoria'];
			precio = new Intl.NumberFormat('es-MX', 
			{style: "currency", currency: "MXN"}).format(precio);
			let concatenar = categoria + " - " + precio;
			$("#pagosPorMetodo tbody").append("<tr><td></td><td>" + concatenar + "</td></tr>");
			if (categoria == "No definido"){
				$("#tablaUtilidad .no-definidos").next().next().text(precio);

			}
			$("#tablaUtilidad ." + categoria).next().next().text(precio);
			console.log($("#tablaUtilidad ." + categoria))
		}
		let total = 0;
		if (data[0] != undefined){
			total = data[0]['total']
		}
		$("#totalSumaPagosSinFormato").val(total);
		
		total = new Intl.NumberFormat('es-MX', 
		{style: "currency", currency: "MXN"}).format(total);
		
		$("#totalSumaPagos").text(total);
		$("#tablaUtilidad .categoria-total").next().next().text(total);
		calcularUtilidad();
	})

}
}


function calcularSaldoPendiente(){
	let cobros = $("#totalCobros").text();
	let servicios = $("#total").text();
	if (servicios.length == "0"){
		servicios = 0;
		$("#total").text("$ 0.00")

	}
	if (cobros.length == 0){
		cobros = "0";
		$("#totalCobros").text("$ 0.00")
	}

	let pattern  = /[^0-9.-]+/g;
	let saldo = parseFloat(servicios.replace(pattern, '')) -
	parseFloat(cobros.replace(pattern, ''));
	mostrarSaldoPendiente(saldo);
	return saldo;
}

function mostrarSaldoPendiente(saldo){	
	$("#saldoPendiente").text("$ " + saldo.toLocaleString('es-MX', { minimumFractionDigits: 2 }));
}


function calcularUtilidad(){
	if (vista == "negocio" && checkboxes == 'h'){
	let totalServicios = $("#totalSumaServiciosSinFormato").val();
	let totalPagos = $("#totalSumaPagosSinFormato").val();
	let total = totalServicios - totalPagos;

	let pattern         = /[^0-9.-]+/g;

	total = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(total);
	// let filasTabla
	// for(let i = 0; i < )
	$("#tablaUtilidad > tbody > tr").each(function () {
		let servicio = $(this).find('td').eq(1).text();
		let pago = $(this).find('td').eq(2).text();
		let utilidadFila = parseFloat(servicio.replace(pattern, '')) - parseFloat(pago.replace(pattern, ''));
		console.log(utilidadFila)
		utilidadFila = new Intl.NumberFormat('es-MX', 
		{style: "currency", currency: "MXN"}).format(utilidadFila);
		$(this).find('td').eq(3).text(utilidadFila);
	});
	$("#totalUtilidad").text(total);
}
}


$(function () {
    $(document).on('click', '.borrar', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
		modificarSubototales();
    });
});


function listarCategorias(){
	// $("#pagosPorMetodo tbody").empty();

	// const queryString = window.location.search;
	// const urlParams = new URLSearchParams(queryString);
	// let id_evento = urlParams.get('evento');
	$.get( "../ajax/detalle_evento.php?op=listarCategorias") 
	.done(function(data){
		data = JSON.parse(data);
		let size = Object.keys(data).length;
	
		for (let i = 0; i < size; i++){
			let id_categoria_servicio = data[i]['id_categoria_servicio'];
			let nombre_categoria_servicio = data[i]['nombre_categoria_servicio'];
			$("#tablaUtilidad > tbody").append("<tr id='" + id_categoria_servicio + "'><td class='categoria " +
			nombre_categoria_servicio + "'>" + nombre_categoria_servicio + " </td><td>$0.00</td><td>$0.00</td><td>$0.00</td></tr>");
			
		}
		// $("#tablaUtilidad > tbody").append('<tr ><td class="categoria no-definidos">Otros</td><td>$0.00</td><td>$0.00</td><td></td></tr>')

		$("#tablaUtilidad > tbody").append('<tr class="totales"><td class="categoria-total">Total</td><td></td><td></td><td></td></tr>')
		// let size = Object.keys(data).length
		// for(let i = 0; i < size; i++){
		// 	let precio = data[i]['monto']; 
		// 	let categoria = data[i]['categoria'];
		// 	precio = new Intl.NumberFormat('es-MX', 
   		// 	{style: "currency", currency: "MXN"}).format(precio);
		// 	let concatenar = categoria + " - " + precio;
		// 	$("#pagosPorMetodo tbody").append("<tr><td></td><td>" + concatenar + "</td></tr>");
		// }
		// let total = 0;
		// if (data[0] != undefined){
		// 	total = data[0]['total']
		// }
		// $("#totalSumaPagosSinFormato").val(total);

		// total = new Intl.NumberFormat('es-MX', 
		// {style: "currency", currency: "MXN"}).format(total);

		// $("#totalSumaPagos").text(total);
		// calcularUtilidad();
	})

}

init();