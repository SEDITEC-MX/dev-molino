var tablaPagoEmpleados;
var tablaEmpleados;
$( document ).ready(function() { 

	

	// Inicio Función de inicio
	function init()
	{
		//Ocultamos el formulario
		mostrarForm(false);

		
		//Llamamos a la función listar
		listar();
		listarEmpleados();
		// $("#detallesPago").hide();
		
		// Codigo de prueba
		

		//Al dar clic en el botón guardar llamamos a guardaryeditar con la información en e
		$("#formulario").on("submit",function(e)
		{
			guardaryeditar(e);	
		})
		
		//Cargamos los items al select categoria
		$.post("../ajax/empleados.php?op=selectCategoria", function(r){
			$("#id_categoria_servicio").html(r);
			$('#id_categoria_servicio').selectpicker('refresh');
		});
		
	}

	function limpiar()
	{
		$("#nombre_empleado").val("");
		$("#id_empleado").val("");
		$("#notas_empleado").val("");

	}

	

	


	function guardaryeditar(e)
	{
		e.preventDefault(); //No se activará la acción predeterminada del evento
		$("#btnGuardar").prop("disabled",true);
		var formData = new FormData($("#formulario")[0]);
		$.ajax({
			url: "../ajax/empleados.php?op=guardaryeditar",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function(datos)
			{                    
				bootbox.alert(datos);	          
				mostrarform(false);
				tablaEmpleados.ajax.reload();
			}

		});
		limpiar();
	}

	function listarEmpleados(){
		tablaEmpleados=$('#tblempleados').dataTable({
			"aProcessing": true,//Activamos el procesamiento del datatables
			"aServerSide": true,//Paginación y filtrado realizados por el servidor
			dom: 'Bfrtip',//Definimos los elementos del control de tabla
			buttons: [],
			"ajax":{
				url: '../ajax/empleados.php?op=listarEmpleados',
				type : "get",
				dataType : "json",						
				error: function(e){
					console.log(e.responseText);	
					}
				},
			"bDestroy": true,
			"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
		}).DataTable();
	}

	

	function listar()
	{
		tablaPagoEmpleados=$('#tbllistado_empleados_pagos').dataTable(
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
						url: '../ajax/pagos_empleados.php?op=listarPagosEmpleados',
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

	$("#id_evento").on("change",function(e)
	{	
		let idEvento = $(this).val(); 
		$(".pago_id_evento").val(idEvento);
	});

	

	// $("#fecha_pago_empleado").on("change",function(e)
	// {	
	// 	let fecha = $(this).val();
	// 	$.ajax({
	// 		url: "../ajax/fecha_evento.php?op=verEventosEnFecha&fecha="+fecha,
	// 		type: "GET",
	// 		contentType: false,
	// 		processData: false,
	// 		success: function(datos){
	// 			datos  = JSON.parse(datos);
	// 			actualizarListaEventos(datos);
	// 		}
	// 	});
	// });

	// $("#tipo_evento").on("change",function(e)
	// {	
	// 	let idEvento = $(this).val(); 
	// 	$(".pago_id_evento").val(idEvento);
	// });

	// function actualizarListaEventos(){
	// 	let selectEventos = $("#tipo_evento");
	// 	selectEventos.empty(); // remove old options
		
	// 	selectEventos.append($("<option></option>")
	// 	.attr("value", 0).text("Ninguno"));
		
	// 	arregloFechas.forEach(evento => {
	// 		let idEvento = evento['id_evento'];
	// 		let nombreEvento = evento['nombre_evento'];
	// 		selectEventos.append($("<option></option>")
	// 		.attr("value", idEvento).text(nombreEvento));
	// 	});

	// }

	$("#btnGuardar").on("click",function(e)
	{	
		let arreglo = [];
		let fecha = $("#fecha_pago_empleado").val();
		if (fecha.length == 0){
			alert("La fecha es obligatoria");
			return;
		}
		let idEvento = $("#id_evento").val();
		let formData = new FormData();
		let cont = 0;
		let bandera = true;
		$("#detalles > tbody > tr").each(function () {

			
			let nombre = $(this).find('td').eq(0).text(); 
			let idEmpleado = $(this).find('input[name="id_empleado[]"]').val(); 
			//let idEvento = $(this).find('input[name="id_evento[]"]').val();
			let concepto = $(this).find('input[name="concepto[]"]').val();
			let monto = $(this).find('input[name="monto[]"]').val();
			if (monto.length == 0 || concepto.length == 0){
				bandera = false;
			}
			let descuento = $(this).find('input[name="descuento[]"]').val(); 
			let total = $(this).find('input[name="total[]"]').val();
			let notas = $(this).find('textarea[name="notas[]"]').val();
			let obj = {"nombre": nombre, "idEmpleado": idEmpleado, "idEvento": idEvento, "concepto": concepto,
						"monto": monto, "descuento": descuento, "total": total, "fecha": fecha, "notas": notas}
			arreglo.push(obj)
			formData.append(cont, JSON.stringify(obj));
			cont++;
		});
	
		if (!bandera){
			alert("El concepto y monto son valores obligatorios para cada uno");
			return;
		}
		
		$.ajax({
			url: "../ajax/pagos_empleados.php?op=guardar",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function(datos)
			{                    
				//mostrarform(false);
				$("#listadoregistros").show();
				$("#detallesPago").hide();
				tablaEmpleados.ajax.reload();
				listar();
				bootbox.alert(datos);
				$("#detalles tbody").empty();
				//location.reload();	          
			}

		});



	});

	listarNombreEventos();

	init();

});


function cancelarform(){
	limpiar();
	mostrarform(false);
}

function agregarDetalle(id_empleado,nombre_empleado, notas_empleado){
	$("#detallesPago").show();
	let cantidad_detalle_evento="";
	let precio_detalle_evento="";
	let concepto = "";
	cont = document.getElementsByName("id_empleado[]").length;
	if (id_empleado!=""){
		console.log("cuenta tabla" + cont)
		let fila='<tr class="filas" id="fila'+cont+'">'+
		'<td><input type="hidden" name="id_empleado[]" value="'+id_empleado+'">'+nombre_empleado+'<input type="hidden" name="id_evento[]" class="pago_id_evento" value="0" readonly></td>'+
		'<td><input type="text" name="concepto[]"  class="form-control" value="'+concepto+'"></td>'+
		'<td><input type="number" name="monto[]"  class="form-control" value="'+'monto'+'"></td>'+
		'<td><input type="number" name="descuento[]"  class="form-control" value="'+'descuento'+'"></td>'+
		'<td><input type="number" name="total[]"  class="form-control" value="'+'total'+'"></td>'+
		'<td><textarea rows="4" name="notas[]"  class="form-control">'+notas_empleado+'</textarea></td>'+
		'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'
		'</tr>';
		cont++;
		detalles=detalles+1;
		$('#detalles').append(fila);
	}else{
		alert("Error al ingresar el detalle, revisar los datos del empleado");
	}
}

function eliminarDetalle(indice){
	$("#fila" + indice).remove();
	//calcularTotales();
	detalles=detalles-1;
}

function eliminar(id_pago_empleado){
	bootbox.confirm("¿Está seguro de eliminar el Pago al Empleado?", function(result){
		if(result)
		{
			$.post("../ajax/pagos_empleados.php?op=eliminar", {id_pago_empleado : id_pago_empleado}, function(e){
				bootbox.alert(e);
				tablaPagoEmpleados.ajax.reload();
			});	
		}
	})
}


function mostrar(id_empleado){
	$.post("../ajax/empleados.php?op=mostrar",{id_empleado : id_empleado}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);
		$("#nombre_empleado").val(data.nombre_empleado);
		$("#id_categoria_servicio").val(data.id_categoria_servicio);
		$('#id_categoria_servicio').selectpicker('refresh');
		$("#id_empleado").val(data.id_empleado);
		$("#notas_empleado").val(data.notas_empleado);

	})
}

function desactivar(id_empleado){
	bootbox.confirm("¿Está seguro de desactivar el Empleado?", function(result){
		if(result)
		{
			$.post("../ajax/empleados.php?op=desactivar", {id_empleado : id_empleado}, function(e){
				bootbox.alert(e);
				tablaEmpleados.ajax.reload();
			});	
		}
	})
}

function activar(id_empleado){
	bootbox.confirm("¿Está seguro de activar el Empleado?", function(result){
		if(result)
		{
			$.post("../ajax/empleados.php?op=activar", {id_empleado : id_empleado}, function(e){
				bootbox.alert(e);
				tablaEmpleados.ajax.reload();
			});	
		}
	})
}


function mostrarForm(flag)
{
	//limpiar();
	if(flag)
	{
		$("#detallesPago").show();
		$("#listadoregistros").hide();
		//$("#btnAgregarArt").hide();
	}
	else
	{
		$("#detallesPago").hide();
	}
}

function cancelarform(){
	location.reload();
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


$(document).on("change", "input[name='monto[]']", function(){
	let monto = $(this).val();
	let descuento = $(this).parent().next().children();
	descuento = descuento[0].value;
	

	let total = $(this).parent().next().next().children();
	total = total[0];
	total.value = calcularTotal(monto, descuento);

});


$(document).on("change", "input[name='descuento[]']", function(){
	let descuento = $(this).val();
	let monto = $(this).parent().prev().children();
	monto = monto[0].value;
	
	let total = $(this).parent().next().children();
	total = total[0];
	
	total.value = calcularTotal(monto, descuento);

});

function calcularTotal(valor1, valor2){
	return valor1 - valor2;
}