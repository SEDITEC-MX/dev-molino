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
	$.post("../ajax/servicio.php?op=selectCategoria", function(r){
	    $("#id_categoria_servicio").html(r);
	    $('#id_categoria_servicio').selectpicker('refresh');
	});
	
	//Ocultamos imagen muestra
	$("#imagenmuestra").hide();		
}

function limpiar()
{
	$("#nombre_proveedor").val("");
	$("#id_proveedor").val("");
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
					url: '../ajax/proveedores.php?op=listar',
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
		url: "../ajax/proveedores.php?op=guardaryeditar",
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

function mostrar(id_proveedor)
{
	$.post("../ajax/proveedores.php?op=mostrar",{id_proveedor : id_proveedor}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);
		$("#nombre_proveedor").val(data.nombre_proveedor);

		$("#id_proveedor").val(data.id_proveedor);

 	})
}

function desactivar(id_proveedor){
	bootbox.confirm("¿Está seguro de desactivar el Proveedor?", function(result){
		if(result)
        {
        	$.post("../ajax/proveedores.php?op=desactivar", {id_proveedor : id_proveedor}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

function activar(id_proveedor){
	bootbox.confirm("¿Está seguro de activar el Proveedor?", function(result){
		if(result)
        {
        	$.post("../ajax/proveedores.php?op=activar", {id_proveedor : id_proveedor}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}


init();