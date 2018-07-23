<script type="text/javascript">
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
</script>

<section class="">
<div class="container">
<div class="section">
<div class="row">
  <div class="col s12 m12 l10 offset-l1"> 
    <div class="changepass z-depth-2">
       <div class="row">
            <div class="col s12">
              <div class="changepass_title">
                 <h3>Cambiar Contrase&ntilde;a</h3>
              </div>
            </div>
      </div>
       <div class="row">
          <div class="col s12 validation_changepass">
            <div class="alert alert-success" align="center">
            <strong>Debes rellenar los datos.</strong>
            </div>
          </div> 
          <div class="col s12">
          <div id="progress"></div>
          </div>
       </div>

        <div class="row">
          <?php echo form_open(base_url()."changepassproc",array("class"=>"col s12 changepass_form","id"=>"changepass_form"));?>
          <div class="input-field col s12 l6">
            <i class="material-icons prefix">account_circle</i>
            <input id="" placeholder="Contrase&ntilde;a Actual" type="password" class="validate" id="pass_actual" name="pass_actual">
            <!-- <label for="">Contrase&ntilde;a Actual</label> -->
          </div>
          <div class="input-field col s12 l6">
            <i class="material-icons prefix">lock</i>
            <input id="" placeholder="Nueva Contrase&ntilde;a" type="password" class="validate" id="pass_nueva" name="pass_nueva">
            <!-- <label for="">Nueva Contrase&ntilde;a</label> -->
          </div>
          <div class="input-field col s12 l6 offset-l3">
            <i class="material-icons prefix">lock</i>
            <input placeholder="Repita Contrase&ntilde;a" type="password" class="validate" id="pass_nueva2" name="pass_nueva2">
            <!-- <label for="">Repita Contrase&ntilde;a</label> -->
          </div>
          <div class="row">
          <div class="input-field col s12">               
             <center><button class="btn waves-effect waves-light" type="submit">Aceptar
              <i class="material-icons right">send</i></button>
              <button class="btn waves-effect waves-light unlogin" type="button">Salir
            </button>
          </div>
          </div>
          </form>
        </div>
  







</div>  
</div>
</div><!-- FIN ROW -->
</div><!-- FIN DIV SECTION -->
</div><!-- FIN CONTAINER -->
</section><br><br>
