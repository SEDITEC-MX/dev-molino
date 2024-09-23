var tabla;

// Inicio Función de inicio
function init()
{
	//Ocultamos el formulario
	mostrarform(false);

	//Llamamos a la función listar
	listar();
	
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
	$("#nombre_servicio").val("");
	$("#descripcion_servicio").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#id_servicio").val("");
	$("#imagen_servicio").val("");
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
					url: '../ajax/servicio.php?op=listar',
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

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/servicio.php?op=guardaryeditar",
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

function mostrar(id_servicio)
{
	$.post("../ajax/servicio.php?op=mostrar",{id_servicio : id_servicio}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#id_categoria_servicio").val(data.id_categoria_servicio);
		$('#id_categoria_servicio').selectpicker('refresh');
		$("#nombre_servicio").val(data.nombre_servicio);
		$("#descripcion_servicio").val(data.descripcion_servicio);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/servicios/"+data.imagen_servicio);
		$("#imagenactual").val(data.imagen_servicio);
		$("#id_servicio").val(data.id_servicio);
		$("#condicion_servicio").val(data.condicion_servicio);

 	})
}






function desactivar(id_servicio)
{
	bootbox.confirm("¿Está seguro de desactivar el Servicio?", function(result){
		if(result)
        {
        	$.post("../ajax/servicio.php?op=desactivar", {id_servicio : id_servicio}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

function activar(id_servicio)
{
	bootbox.confirm("¿Está seguro de activar el Servicio?", function(result){
		if(result)
        {
        	$.post("../ajax/servicio.php?op=activar", {id_servicio : id_servicio}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();