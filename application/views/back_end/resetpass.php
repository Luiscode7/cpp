<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="description" content="">
<meta name="author" content="">
<title></title>
<script src="<?php echo base_url();?>assets/back_end/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/back_end/js/bootstrap.min.js"></script>
<link href="<?php echo base_url();?>assets/back_end/css/normalize.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/back_end/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/back_end/css/estilos_km.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/back_end/css/fontawesome-all.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/back_end/css/form_style.css" rel="stylesheet">

<style type="text/css" media="screen">
  body{
  background-image: url("./assets/imagenes/fondolog.jpg");
  background-size: cover;
  overflow-y:hidden;
  }

  .alert {
  padding: 6px;
  margin-bottom: 0px;
  border: 1px solid transparent;
  border-radius: 4px;
  text-align:left;
  }

  .top-content{
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  -webkit-transform: translate(-50%, -50%);
  }

  .contenedor-pass{
    width:40%;
  }

  .titulo-pass{
    color:#f8f9fa;
    padding-bottom:20px;
  }


</style>

<!--<script type="text/javascript">
  $(function(){

      $(document).on('click', '.unlogin', function(event) {
        event.preventDefault();
        window.location="unlogin";
      });

      var c="<?php echo $this->session->userdata('contrasena_actualizada');?>";
      if(c==""){
        $("header,footer").hide();
      }

      $('#changepass_form').submit(function(){
      var formElement = document.querySelector("#changepass_form");
      var formData = new FormData(formElement);

      $.ajax({
        url: $('.changepass_form').attr('action')+"?"+$.now(),  
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        dataType: "json",
        contentType : false,
        success: function (data) {
          //alert(data);
          
           if(data.res == 0){
           $('.validation_changepass').hide();
           $('.validation_changepass').fadeIn();
           $(".validation_changepass").html('<div class="alert alert-danger" align="center"><strong>Debe rellenar los campos.</strong></div>');
          
           }else 
           if(data.res == 1){
           $('.validation_changepass').hide();
           $('.validation_changepass').fadeIn();
           $(".validation_changepass").html('<div class="alert alert-danger" align="center"><strong>Las contrase&ntilde;as deben coincidir.</strong></div>');
           }else 
           if(data.res == 2){
           $('.validation_changepass').hide();
           $('.validation_changepass').fadeIn();
           $(".validation_changepass").html('<div class="alert alert-danger" align="center"><strong>Contrase&ntilde;a incorrecta.</strong></div>');
           }else 
           if(data.res == 3){
           $('.validation_changepass').hide();
           $('.validation_changepass').fadeIn();
           $(".validation_changepass").html('<div class="alert alert-success" align="center"><strong>Contrase&ntilde;a modificada correctamente.</strong></div>');
           $("#progress").html('<div class="progress"><div class="indeterminate"></div></div>');
           $("#progress").show();
           setTimeout(function(){window.location="unlogin"} ,1000);  
           }else 
           if(data.res == 4){
           $('.validation_changepass').hide();
           $('.validation_changepass').fadeIn();
           $(".validation_changepass").html('<div class="alert alert-danger" align="center"><strong>Problemas accediento a la base de datos, intente nuevamente.</strong></div>');
           }else 
           if(data.res == 5){
           $('.validation_changepass').hide();
           $('.validation_changepass').fadeIn();
           $(".validation_changepass").html('<div class="alert alert-danger" align="center"><strong>La contraseña no puede la misma o el rut.</strong></div>');
           }  
        },
        error:function(){
          alert("Problemas accediento a la base de datos, intente nuevamente.");
        }
     });
        return false; 
  });
  });
</script>-->

<div class="container top-content row justify-content-center align-items-center" style="height:400px;">
  <div class="contenedor-pass">
    <div><h3 class="titulo-pass">Crear Contrase&ntilde;a Nueva</h3></div>
    <div class="form-group">
      <input type="password" class="form-control separador" placeholder="Ingrese Contrase&ntilde;a Actual" name="passActual" id="passActual">
    </div>
    <div class="form-group">
      <input type="password" class="form-control" placeholder="Ingrese Contrase&ntilde;a Nueva" name="passNueva" id="passNueva">
    </div>
    <div class="form-group">
      <input type="password" class="form-control" placeholder="Repita Contrase&ntilde;a" name="passRepetir" id="passRepetir">
    </div>
    <div class="form-group">
      <button type="submit" class="btn-block btn btn-sm btn-primary btn_nuevapass_cpp" style="background-color: #1E748D">Cambiar Contrase&ntilde;a</button>
    </div>
  </div>
</div>

</html>
</body>