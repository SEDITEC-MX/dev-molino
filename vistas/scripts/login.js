$("#frmAcceso").on('submit',function(e)
{
	e.preventDefault();
	usr_usuario=$("#usr_usuario").val();
	pass_usuario=$("#pass_usuario").val();
	
	
	$.post("../ajax/usuario.php?op=verificar",
	{"usr_usuario":usr_usuario,"pass_usuario":pass_usuario},
	function(data)
		{
		if(data!="null")
		{
			$(location).attr("href","index2.php");
			
		}
		else
		{
			bootbox.alert("Usuario y/o contraseña inválida");
		}
	});
	
})