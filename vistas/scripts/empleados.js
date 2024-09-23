let tabla;

// Inicio Función de inicio
function init()
{
	//Ocultamos el formulario
	mostrarform(false);

	//Llamamos a la función listar
	listar();
	// listarEmpleados();

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
	
	//Ocultamos imagen muestra
	$("#imagenmuestra").hide();		
}

function limpiar()
{
	$("#nombre_empleado").val("");
	$("#id_empleado").val("");
	$("#notas_empleado").val("");

}

function mostrarform(flag)
{
	limpiar();
	if(flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
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
		            'pdf',
		            'pageLength'
		        ],
		language: {
	      buttons: {
	        pageLength: {
	   	    	_: "Mostrar %d filas",
	       		'-1': "Todos"
	      		}
	    	},
	   	},
	   'pageLength': 30,
		'aLengthMenu': [[ 10, 20, 50, 100 ,-1],[10,20,50,100,"Todos"]],
		"ajax":
				{
					url: '../ajax/empleados.php?op=listar',
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
	console.log(tabla)
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
	          tabla.ajax.reload();
			  
	    }

	});
	limpiar();
}

function mostrar(id_empleado)
{
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
	            tabla.ajax.reload();
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
	            tabla.ajax.reload();
        	});	
        }
	})
}


init();