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
  
  .modal_recuperar{
    width:60%!important;
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
  .recuperarPass{
    display: block;
    text-align: center;
    color: #1E748D;
  }
  .validacion_rec{
    margin-top: 5px;
    margin-bottom: 10px;
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


    $(document).on('click', '.recuperarPass', function(event) {
      event.preventDefault();
      $("#rut_rec").val($("#usuario").val());
    });
    

    $(document).on('submit', '#formRecuperarPass', function(event) {
      var formElement = document.querySelector("#formRecuperarPass");
      var formData = new FormData(formElement);
      data: formData;
      $.ajax({
          url: $('#formRecuperarPass').attr('action')+"?"+$.now(),
          type: 'POST',
          data: formData,
          cache: false,
          processData: false,
          contentType : false,
          dataType : "json",
          beforeSend: function(){
            $('.btn_submit_rec').prop("disabled",true);
          },
          success:function(data){
            if(data.res == "ok"){    
              $(".validacion_rec").hide().html('<div class="alert alert-info" style="color:#fff;font-size:12px;background-color: #1E748D;"><strong><span class="glyphicon glyphicon-ok"></span> '+data.msg+'</strong></div>').show();
              $("#btn_submit_rec").html('<i class="fa fa-cog fa-spin fa-3x"></i>');  
             
              setTimeout( function () {
                $('.btn_submit_rec').prop("disabled",false);
                /*   $('#modal_recuperar').modal("toggle");*/
                $("#btn_submit_rec").html('Registrarse');  
                $('#formRecuperarPass')[0].reset();
                /* $(".validacion_rec").html("");*/
              }, 2000);   

            }else if(data.res == "error"){
              $('.btn_submit_rec').prop("disabled",false);
              $(".validacion_rec").hide();
              $(".validacion_rec").html('<div class="alert alert-info" style="color:#fff;background-color: #CD2D00;font-size: 14px;"> '+data.msg+'</div>');
              $(".validacion_rec").fadeIn(1000);
            }

          },
          error:function(data){
            $('.btn_submit_rec').prop("disabled",false);
            $(".validacion_rec").hide();
            $(".validacion_rec").html('<div class="alert alert-info" style="color:#CD2D00;background-color: #EEEEEE;font-size: 14px;"><span class="glyphicon glyphicon-exclamation-sign"></span> Problemas accediendo a la base de datos, intente nuevamente.</div>');
            $(".validacion_rec").fadeIn(1000);          
          }
      });
      return false;
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
                   <?php echo form_close();?>

                   <center><a href="#modal_recuperar" data-toggle="modal" class="recuperarPass">¿Olvido su contrase&ntilde;a?</a></center>
                
                  </div>

              </div>
          </div>
        </div>
    </div>
  </div>

<!-- RECUPERAR MODAL -->
  <div id="modal_recuperar" class="modal fade"  tabindex="-1"  aria-labelledby="myModalLabel" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal_recuperar">
      <div class="modal-content">
        <div class="modal-body" style="padding: 0px;">

        <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute;right: 20px;top: 15px;">X</button>
          
          <div class="col-lg-12">
             <h3 style="margin: 20px;">Resetear contrase&ntilde;a</h3>
          </div>

          <div class="form-bottom">
           <?php echo form_open_multipart("formRecuperarPass",array("id"=>"formRecuperarPass","class"=>"formRecuperarPass"))?>
            <div class="form-group">
              <label class="sr-only" for="form-username">Rut</label>
                <input type="text" name="rut_rec" id="rut_rec" placeholder="Rut" class="form-control" >
              </div>
              
              <div class="validacion_rec"></div>
              <div class="btn_submit">
                <button type="submit" class="btn_submit_rec btn btn-primary" style="background-color: #1E748D">Resetear</button>
              </div>
             <?php echo form_close();?>
          </div>

        </div>
     </div>
    </div>
  </div>

</body>
</html>