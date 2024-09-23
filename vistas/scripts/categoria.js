var tabla;

function init()
{
	mostrarform(false);
	listar();
	
	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})
	
}

function limpiar()
{
	$("#id_categoria_servicio").val("");
	$("#nombre").val("");
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
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/categoria.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/categoria.php?op=guardaryeditar",
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

function mostrar(id_categoria_servicio)
{
	$.post("../ajax/categoria.php?op=mostrar",{id_categoria_servicio : id_categoria_servicio}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#nombre_categoria_servicio").val(data.nombre_categoria_servicio);
		$("#id_categoria_servicio").val(data.id_categoria_servicio);
		$("#condicio_categoria_servicio").val(data.condicio_categoria_servicio);

 	})
}

function desactivar(id_categoria_servicio)
{
	bootbox.confirm("¿Está seguro de desactivar la Categoría?", function(result){
		if(result)
        {
        	$.post("../ajax/categoria.php?op=desactivar", {id_categoria_servicio : id_categoria_servicio}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

function activar(id_categoria_servicio)
{
	bootbox.confirm("¿Está seguro de activar la Categoría?", function(result){
		if(result)
        {
        	$.post("../ajax/categoria.php?op=activar", {id_categoria_servicio : id_categoria_servicio}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();