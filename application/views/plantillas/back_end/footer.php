<style type="text/css">
	.modal-modpass{
		width:40%!important;
	}
</style>
<script type="text/javascript">
	$(function(){

		$(window).on('load', function(){
		    window.setTimeout(function(){
		      $('#body').addClass('loaded');
		    } , 0);
		});

	  	$(document).off('submit', '#actualizarPassForm').on('submit', '#actualizarPassForm', function(event) {
	      var url="<?php echo base_url()?>";
	      var formElement = document.querySelector("#actualizarPassForm");
	      var formData = new FormData(formElement);
	        $.ajax({
	            url: $('.actualizarPassForm').attr('action')+"?"+$.now(),  
	            type: 'POST',
	            data: formData,
	            cache: false,
	            processData: false,
	            dataType: "json",
	            contentType : false,
	            beforeSend:function(){
	             $("#actualizarPassForm input,#actualizarPassForm select,#actualizarPassForm button,#actualizarPassForm").prop("disabled", true);
	            },
	            success: function (data) {
	              if(data.res == "error"){
	                $.notify(data.msg, {
	                  className:'error',
	                  globalPosition: 'top right'
	                });
	               $("#actualizarPassForm input,#actualizarPassForm select,#actualizarPassForm button,#actualizarPassForm").prop("disabled", false);
	              }else if(data.res == "ok"){
	                $.notify(data.msg, {
	                  className:'success',
	                  globalPosition: 'top right'
	                });
	                $("#pass_us").val("");
	               $("#actualizarPassForm input,#actualizarPassForm select,#actualizarPassForm button,#actualizarPassForm").prop("disabled", false);    
	              }else if(data.res=="sess"){
                    window.location="unlogin";
                  }
	            }
	        });
	        return false; 
	    });

    });
</script>

 <!-- INGRESO modal fade-->
  <div id="actualizarPassModal" class="modal fade"  data-backdrop="static" tabindex="-1"  aria-labelledby="myModalLabel" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-modpass">
        <div class="modal-content">
        <?php echo form_open_multipart("actualizarPassForm",array("id"=>"actualizarPassForm","class"=>"actualizarPassForm"))?>

           <div class="row">
           <div class="modal-body" style="padding:10px 20px; margin-left: 10px;">     
           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
              
            <fieldset class="form-ing-cont" style="border: 1px solid #ccc!important;">
            <legend class="form-ing-border">Actualizar contrase&ntilde;a</legend>

              <div class="col-lg-12">  
                <div class="form-group">   
                    <input placeholder="Ingrese nueva Contrase&ntilde;a" size="15" maxlength="15" type="text" name="pass_us"  id="pass_us" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-12">  
                <div class="form-group">
                <label>&nbsp;&nbsp;&nbsp;</label>
                    <center>
                    <button title="Actualizar" type="submit" class="btn btn-sm btn-outline-primary btn-primary btn_mod_user">
                    <i class="fa fa-edit"></i> Actualizar
                    </button>

		            <button class="btn_cerrar_user btn btn-sm btn-outline-primary btn-primary" data-dismiss="modal" aria-hidden="true">
		            <i class="fa fa-window-close"></i> Cerrar
		            </button> 
		            </center>
		         </div>
              </div>
          
             </fieldset>
            
             <?php echo form_close(); ?>
            
             </div>
            </div>  
       </div>
    </div>
  </div>


<footer class="footer text-center">
	<?php 
    if($this->session->userdata('empresaCPP')=="km"){
	    ?>
				<p> &copy; KM Telecomunicaciones <?php echo date("Y");?></p>
	    <?php
    }elseif ($this->session->userdata('empresaCPP')=="splice") {
	    ?>
				<p> &copy; Splice Chile <?php echo date("Y");?></p>

	    <?php
    }
  ?>

</footer>


<script src="<?php echo base_url();?>assets/back_end/js/popper.min.js" charset="UTF-8"></script>
<script src="<?php echo base_url();?>assets/back_end/js/bootstrap.min.js" charset="UTF-8"></script>
<script src="<?php echo base_url();?>assets/back_end/js/jquery.dataTables.min.js" charset="UTF-8"></script>
<script src="<?php echo base_url();?>assets/back_end/js/moment-with-locales.min.js" charset="UTF-8"></script>
<script src="<?php echo base_url();?>assets/back_end/js/notify.min.js" charset="UTF-8"></script>
<script src="<?php echo base_url();?>assets/back_end/js/select2.min.js" charset="UTF-8"></script>
<script src="<?php echo base_url();?>assets/back_end/js/clockpicker.js" charset="UTF-8"></script>
<script src="<?php echo base_url();?>assets/back_end/js/bootstrap-datetimepicker.min2.js" charset="UTF-8"></script>
<script src="<?php echo base_url();?>assets/back_end/js/rut.min.js" charset="UTF-8"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/select.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/clockpicker.css" >
<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/bootstrap.min.css" >
<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/bootstrap-datetimepicker.min2.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/normalize.min.css" rel="stylesheet">

<?php 
    if($this->session->userdata('empresaCPP')=="km"){
	    ?>
			<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/estilos_km.css">
	    <?php
    }elseif ($this->session->userdata('empresaCPP')=="splice") {
	    ?>
			<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/estilos_splice.css">
	    <?php
    }
  ?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/back_end/css/fontawesome-all.min.css">
<script src="<?php echo base_url();?>assets/back_end/js/dataTables.select.min.js"></script>
</body>
</html>