<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="description" content="">
<meta name="author" content="">
<title><?php echo $titulo?></title>
<script src="<?php echo base_url();?>assets/back_end/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/back_end/js/bootstrap.min.js"></script>
<link href="<?php echo base_url();?>assets/back_end/css/normalize.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/back_end/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/back_end/css/estilos.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/back_end/css/fontawesome-all.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/back_end/css/form_style.css" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style type="text/css" media="screen">
  body{
  background-image: url("./assets/imagenes/fondolog.jpg");
  background-size: cover;
  }

  .validacion{
    font-size:12px!important;

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
  .validacion-correo{
    margin-top:10px;
    margin-bottom: 10px;
  }

*{
  margin:0;
  padding:0;
}

</style>
<script type="text/javascript">
  $(function(){
  
    function detectBrowser(){
      if (/MSIE 10/i.test(navigator.userAgent)) {
         // This is internet explorer 10
         alert('Favor usar navegador Google Chrome, Firefox o Safari, internet explorer puede provocar comportamientos inesperados en la aplicación.');
         return false;
      }

      if (/MSIE 9/i.test(navigator.userAgent) || /rv:11.0/i.test(navigator.userAgent)) {
          // This is internet explorer 9 or 11
         alert('Favor usar navegador Google Chrome, Firefox o Safari, internet explorer puede provocar comportamientos inesperados en la aplicación.');
         return false;
      }

      if (/Edge\/\d./i.test(navigator.userAgent)){
         alert('Favor usar navegador Google Chrome, Firefox o Safari, EDGE puede provocar comportamientos inesperados en la aplicación.');
         return false;
      }
      return true;
    }

    
    $(document).on('submit', '#formlog', function(event) {
       if(detectBrowser()){
        var formElement = document.querySelector("#formlog");
        var formData = new FormData(formElement);
        data: formData;

        $.ajax({
            url: $('#formlog').attr('action')+"?"+$.now(),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend: function(){
            	$('.btn_submit_b').prop("disabled",true);
            },
            success:function(data){
              if(data.res == "ok"){    
                $(".validacion").hide();       
                $(".validacion").html('<div class="alert alert-primary alert-dismissible fade show" role="alert"><strong>'+data.msg+'</strong></div>');
                $(".btn_submit").html('<button type="submit" class="btn_submit_b btn btn-primary"> Ingresando <i class="fa fa-cog fa-spin"></i></button>');
                $(".validacion").fadeIn(1);
                $("#btn_submit").html('<i class="fa fa-cog fa-spin fa-3x"></i>');  
                $('.btn_submit_b').prop("disabled",true);
                setTimeout( function () {
		         window.location.replace("<?php echo base_url(); ?>inicio");
		        }, 1500);   
              }else if(data.res == "error"){
              	$('.btn_submit_b').prop("disabled",false);
                $(".validacion").hide();
                $(".validacion").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.msg+'</div>');
                $(".validacion").fadeIn(1000);
              }

            },
            error:function(data){
            	$('.btn_submit_b').prop("disabled",false);
                $(".validacion").hide();
                $(".validacion").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Problemas accediendo a la base de datos, intente nuevamente.</div>');
                $(".validacion").fadeIn(1000);          
            }
        });
        return false;
        }else{
            return false;
        }
      });

      $('#recuperarpass').submit(function(){
        $('#modal1').modal("toggle"); 
        var formElement = document.querySelector("#recuperarpass");
        var formData = new FormData(formElement);
        $.ajax({
          url: $('.recuperarpass').attr('action')+"?"+$.now(),  
          type: 'POST',
          data: formData,
          cache: false,
          processData: false,
          dataType: "json",
          contentType : false,
          success: function (data) {
       
          if(data.res == 1){    
              $(".validacion-rut").hide();       
              $(".validacion-rut").html("<div class='row'><div class='card-panel white-text teal lighten-2'><center>Nueva contrase&ntilde;a enviada a su correo.</center></div></div>");
              $(".validacion-rut").fadeIn(1000);
                //setTimeout(function(){window.location='<?php echo base_url()?>inicio'} , 4000); 
          }else if(data.res == 2){
              $(".validacion-rut").hide();
              $(".validacion-rut").html("<div class='row'><div class='card-panel white-text red darken-3'><center><blockquote>El rut ingresado no esta registrado en la base de datos.</blockquote></center></div></div>");
              $(".validacion-rut").fadeIn(1000);
          }else if(data.res == 3){
              $(".validacion-rut").hide();
              $(".validacion-rut").html("<div class='row'><div class='card-panel white-text red darken-3'><center><blockquote>Error, Intente nuevamente.</blockquote></center></div></div>");
              $(".validacion-rut").fadeIn(1000);
          }
        }
       
    });
      return false;     
});

$(".usuario").keyup(function(event) {
    $(".rut").attr("value",$(this).val());
  });

  });
</script>
</head>
<body>

<!-- LOGIN -->
  <div class="top-content col-xs-12 col-sm-12">
    <div class="inner-bg">
        <div class="container">
          <div class="form-row">
              <div class="col-lg-6 offset-lg-3  form-box">

                  <div class="form-top">
                    <div class="form-top-left">
                      <h3>Inicio de Sesi&oacute;n CPP</h3>
                       <div class="validacion">
                       <!-- <div class="alert alert-info" style="background-color: #1E748D">
                        Ingrese su rut y contrase&ntilde;a.
                       </div> -->

                       <div class="alert alert-primary alert-dismissible fade show" role="alert">
                         Ingrese su rut y contrase&ntilde;a.
                      </div>

                       </div>
                    </div>
                    <div class="form-top-right">
                      <i class="fa fa-key"></i>
                    </div>
                  </div>

                  <div class="form-bottom">
                  <?php echo form_open(base_url()."loginProcess",array("id"=>"formlog","class" =>"formlog"));?>
                  <div class="form-group">
                    <label class="sr-only" for="form-username">Rut</label>
                      <input type="text" name="usuario" id="usuario" placeholder="Rut..." class="form-username form-control" >
                  </div>
                    <div class="form-group">
                      <label class="sr-only" for="form-password">Contrase&ntilde;a</label>
                      <input type="password" name="pass" id="pass" placeholder="Contrase&ntilde;a..." class="form-password form-control">
                    </div>
                    <div class="btn_submit">
                      <button type="submit" class="btn_submit_b btn btn-primary" style="background-color: #1E748D">Ingresar</button>
                    </div>
                    <center>
                      <div class="input-field col s10 offset-s1">
                        <a href="#modal1" id="recupera_pass" style="font-size:12px;" data-toggle="modal" data-target="#modal1" class="modal-trigger">
                          ¿ Olvid&oacute; su contrase&ntilde;a ?
                        </a>  
                      </div>
                    </center>
                   <?php echo form_close();?>
                  </div>

              </div>
          </div>
    </div>

  <div class="container">
    <div id="modal1"  class="modal fade" data-backdrop="false" aria-labelledby="myModalLabel" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          <?php echo form_open('recuperarpass', array('id'=>'recuperarpass','class'=>'recuperarpass')); ?>
           
               <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
               <fieldset class="form-ing-cont">
                <legend class="form-ing-border">Recuperaci&oacute;n de Contrase&ntilde;a</legend>
                     
                    <div class="form-row">
                      <div class="col-lg-12">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm"></label>
                            <input type="text" autocomplete="off" placeholder="" class="form-control form-control-sm"  name="rut" id="rut">
                        </div>
                      </div>  
                    </div>
                   
                </fieldset>

                <br>
              <div class="row justify-content-center">
                <div class="col-lg-6">
                  <div class="form-row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingresa_cpp">
                        <i class="fas fa-chevron-circle-right"></i> Enviar
                        </button>
                      </div>
                  </div>

                    <div class="col-lg-6">
                      <button class="btn-block btn btn-sm btn-dark cierra_mod_inf" data-dismiss="modal" aria-hidden="true">
                       <i class="fa fa-window-close"></i> Cerrar
                      </button>
                    </div>
                  </div>
                </div>
                </div>

               </div>
             <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>


</body>
</html>