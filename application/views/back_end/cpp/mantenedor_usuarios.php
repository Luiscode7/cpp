<style type="text/css">
	.btn_agregar_us,.btn_reset_us,.btn_excel_us{
		margin-top: 30px;
	}
</style>
<script type="text/javascript">
	$(function(){
		pf="<?php echo $id_perfil_CPP ?>";
	  	$.getJSON('getUsuariosSel2', {}, 
	        function(response) {
	            $("#select_usuario").select2({
	               allowClear: true,
	               placeholder: 'Seleccione usuario',
	               data: response
	        });
	    });

		var tabla_mant_us = $('#tabla_mant_us').DataTable({
	        "iDisplayLength":50, 
	        "aaSorting" : [[1,'asc']],
	        "scrollY": 420,
	        "scrollX": true,
           info:true,
           paging:false,
	         select: true,
	        columnDefs:[
	         /* { "name": "acciones",targets: [0],searcheable : false,orderable:false}, 
	          {targets: [8], orderData: [5,6]}, //al ordenar por fecha, se ordenera por especialidad,comuna
	          {targets: [16],orderable:false,searcheable : false}, */
	        ],

	        "ajax": {
	            "url":"<?php echo base_url();?>listaMantUsuarios",
	            "dataSrc": function (json) {
	                $(".btn_filtra_cpp").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
	                $(".btn_filtra_cpp").prop("disabled" , false);
	                return json;
	            },       
	            data: function(param){
	            }
	         },    
	         "columns": [
	            {
	             "class":"","data": function(row,type,val,meta){
	             	btn="<center>";
	             	if(pf==1){
		                btn+='<a href="#!" title="Editar" data-hash="'+row.hash_id+'" class="btn_edita"><i class="fa fa-edit" style="font-size:14px;"></i> </a>';
		                btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash_id+'" class="btn_elimina"><i class="fa fa-trash" style="font-size:14px;margin-left:10px;"></i> </a></center>';
	                }else{
	                	btn+="-</center>"
	                }
	                return btn;
	              }
	            },
              { "data": "usuario" ,"class":"margen-td"},
              { "data": "rut" ,"class":"margen-td"},
              { "data": "cargo" ,"class":"margen-td"},
	            { "data": "empresa" ,"class":"margen-td"},
              { "data": "perfil" ,"class":"margen-td"},
              { "data": "supervisor" ,"class":"margen-td"},
              { "data": "supervisor2" ,"class":"margen-td"},
	            { "data": "ultima_actualizacion" ,"class":"margen-td"}
	         ]
        }); 

        $(document).on('keyup paste', '#buscador_us', function() {
          tabla_mant_us.search($(this).val().trim()).draw();
        });

        String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

        $(document).off('submit', '#formMantUs').on('submit', '#formMantUs',function(event) {
            var url="<?php echo base_url()?>";
            var formElement = document.querySelector("#formMantUs");
            var formData = new FormData(formElement);
              $.ajax({
                  url: $('#formMantUs').attr('action')+"?"+$.now(),  
                  type: 'POST',
                  data: formData,
                  cache: false,
                  processData: false,
                  dataType: "json",
                  contentType : false,
                  beforeSend:function(){
                    /*$(".btn_agregar_us").attr("disabled", true);
                    $("#formMantUs input,#formMantUs select,#formMantUs button,#formMantUs").prop("disabled", true);*/
                  },
                  success: function (data) {
                    if(data.res == "error"){
                       $(".btn_agregar_us").attr("disabled", false);
                        $.notify(data.msg, {
                          className:'error',
                          globalPosition: 'top right',
                          autoHideDelay:5000,
                        });
                        $("#formMantUs input,#formMantUs select,#formMantUs button,#formMantUs").prop("disabled", false);
                    }else if(data.res == "ok"){
                      $(".btn_agregar_us").attr("disabled", false);
                      $("#formMantUs input,#formMantUs select,#formMantUs button,#formMantUs").prop("disabled", false);
                      $.notify(data.msg, {
                        className:'success',
                        globalPosition: 'top right',
                        autoHideDelay:5000,
                      });

                       $("#id_mant_us").val("");
                       $("#select_perfil option[value=''").prop("selected", true);
                       $("#supervisor option[value=''").prop("selected", true);
                       $("#supervisor2 option[value=''").prop("selected", true);
                       $('#select_usuario').val("").trigger('change');
                       $(".btn_agregar_us").html(' <i class="fa fa-plus"></i> Agregar');
                       tabla_mant_us.ajax.reload();
                    }else if(data.res == "sess"){
                      window.location="unlogin";
                    }
                  }
            });
            return false; 
        });

        $(document).off('click', '.btn_edita').on('click', '.btn_edita',function(event) {
           hash=$(this).attr("data-hash");
           $(".btn_agregar_us").html('<i class="fa fa-edit" ></i> Modificar');
           $('#formMantUs')[0].reset();
           $("#id_mant_us").val("");
           $("#formMantUs input,#formMantUs select,#formMantUs button,#formMantUs").prop("disabled", true);

            $.ajax({
              url: "getDataMantUs"+"?"+$.now(),  
              type: 'POST',
              cache: false,
              tryCount : 0,
              retryLimit : 3,
              data:{hash:hash},
              dataType:"json",
              beforeSend:function(){
                /*$(".btn_agregar_us").prop("disabled",true); 
                */
              },
              success: function (data) {
                if(data.res=="ok"){
                  for(dato in data.datos){
                      $("#id_mant_us").val(data.datos[dato].hash_id);
                      $("#select_perfil option[value='"+data.datos[dato].id_perfil+"'").prop("selected", true);
                      $("#supervisor option[value='"+data.datos[dato].id_supervisor+"'").prop("selected", true);
                      $("#supervisor2 option[value='"+data.datos[dato].id_supervisor2+"'").prop("selected", true);
                      $('#select_usuario').val(data.datos[dato].id_usuario).trigger('change');
                  }
                  $(".btn_agregar_us").html('<i class="fa fa-edit" ></i> Modificar');
                  $("#formMantUs input,#formMantUs select,#formMantUs button,#formMantUs").prop("disabled", false);
                }else if(data.res=="ok"){
                    $.notify("Problemas en el servidor, intente más tarde.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                }else if(data.res == "sess"){
                  window.location="unlogin";
                }
              },
              error : function(xhr, textStatus, errorThrown ) {
                if (textStatus == 'timeout') {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.notify("Reintentando...", {
                          className:'info',
                          globalPosition: 'top right'
                        });
                        $.ajax(this);
                        return;
                    } else{
                       $.notify("Problemas en el servidor, intente nuevamente.", {
                          className:'warn',
                          globalPosition: 'top right'
                        });     
                    }    
                    return;
                }

                if (xhr.status == 500) {
                    $.notify("Problemas en el servidor, intente más tarde.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                }

            },timeout:5000
          }); 

        });

        $(document).off('click', '.btn_elimina').on('click', '.btn_elimina',function(event) {
          hash=$(this).attr("data-hash");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
              $.post('eliminaUsuario'+"?"+$.now(),{"hash": hash}, function(data) {
                if(data.res=="ok"){
                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right'
                  });
                 tabla_mant_us.ajax.reload();
                }else if(data.res=="error"){
                  $.notify(data.msg, {
                    className:'danger',
                    globalPosition: 'top right'
                  });
                }else if(data.res == "sess"){
                  window.location="unlogin";
                }
              },"json");
            }
        });

        $(document).off('click', '.btn_reset_us').on('click', '.btn_reset_us',function(event) {
           $("#id_mant_us").val("");
           $("#select_perfil option[value=''").prop("selected", true);
           $("#supervisor option[value=''").prop("selected", true);
           $("#supervisor2 option[value=''").prop("selected", true);
           $('#select_usuario').val("").trigger('change');
           $(".btn_agregar_us").html(' <i class="fa fa-plus"></i> Agregar');
        });

        $(document).off('click', '.btn_excel_us').on('click', '.btn_excel_us',function(event) {
           event.preventDefault();
           window.location="excelusuarios";

        });

        


	})	
</script>

	<!--FILTROS-->
	  <?php echo form_open_multipart("formMantUs",array("id"=>"formMantUs","class"=>"formMantUs"))?>

	  <div class="form-row">
	      <input type="hidden" name="id_mant_us" id="id_mant_us">

		    <div class="col-lg-3">  
			    <div class="form-group">
			     <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Usuario</label>
			        <select id="select_usuario" name="usuario" class="custom-select custom-select-sm"  style="width:100%!important;">
			        <option selected value="">Seleccione usuario </option>
			        </select>
			    </div>
		    </div>  

		    <div class="col-lg-2">  
			    <div class="form-group">
			     <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Perfil</label>
			        <select id="select_perfil" name="perfil" class="custom-select custom-select-sm">
			        <option value="">Seleccione perfil</option>
			        <?php 
			        foreach($perfiles as $p){
			        	?>	
						<option value="<?php echo $p["id"] ?>"><?php echo $p["perfil"] ?></option>
			        	<?php
			        }
			        ?>
			        </select>
			    </div>
		    </div>  

        <div class="col-lg-2">  
          <div class="form-group">
           <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Supervisor</label>
              <select id="supervisor" name="supervisor" class="custom-select custom-select-sm">
              <option value="">Seleccione supervisor</option>
              <?php 
              foreach($supervisores as $s){
                 ?>  
                <option value="<?php echo $s["id"] ?>"><?php echo $s["nombre"] ?></option>
                <?php
              }
              ?>
              </select>
          </div>
        </div>  

        <div class="col-lg-2">  
          <div class="form-group">
           <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Supervisor 2</label>
              <select id="supervisor2" name="supervisor2" class="custom-select custom-select-sm">
              <option value="">Seleccione supervisor 2</option>
              <?php 
              foreach($supervisores as $s){
                 ?>  
                <option value="<?php echo $s["id"] ?>"><?php echo $s["nombre"] ?></option>
                <?php
              }
              ?>
              </select>
          </div>
        </div>  

			  <div class="col-lg-1">
		      <div class="form-group">
		        <button type="submit" class="btn-block btn btn-sm btn-primary btn_agregar_us">
		         <i class="fa fa-plus"></i> Agregar
		        </button>
		      </div>
		    </div>
			
		  	<div class="col-lg-1">
		       <div class="form-group">
		        <button type="button" class="btn-block btn btn-sm btn-primary btn_reset_us">
		         <!-- <i class="fa fa-plus"></i> --> Reset
		        </button>
		      </div>
		    </div>

        <div class="col-lg-1">
           <div class="form-group">
            <button type="button" class="btn-block btn btn-sm btn-primary btn_excel_us">
             <i class="fa fa-save"></i> Excel
            </button>
          </div>
        </div>

	  </div>
	<?php echo form_close(); ?>	

<!-- LISTADO -->
<hr>


  <div class="col-lg-6 offset-3" style="margin-top: 10px;">
     <div class="form-group">
      <input type="text" placeholder="Ingrese su busqueda..." id="buscador_us" class="buscador_us form-control form-control-sm">
     </div>
  </div>

  <div class="form-row">
    <div class="col">
    <table id="tabla_mant_us" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
      <thead>
        <tr>
          <th>Acciones</th>
          <th>Usuario</th>
          <th>RUT</th>
          <th>Cargo</th>
          <th>Empresa</th>
          <th>Perfil</th>
          <th>Supervisor</th>
          <th>Supervisor2</th>
          <th>&Uacute;ltima actualizaci&oacute;n</th>
        </tr>
      </thead>
    </table>
    </div>
  </div>