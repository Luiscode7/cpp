<style type="text/css">
  .btn_edita2{
    margin:0;
    color:#1E748D;
    font-size: 13px;
    text-decoration: none!important;
    display: flex;
    justify-content: center;
  }
</style>
<script type="text/javascript" charset="utf-8">
    $(function(){

/*****DEFINICIONES*****/
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    var session="<?php echo $this->session->userdata("id"); ?>";
    var perfil="<?php echo $this->session->userdata("perfil"); ?>";
    var base="<?php echo base_url();?>";
  
    $.extend(true,$.fn.dataTable.defaults,{
      info:false,
      paging:false,
      ordering:true,
      searching:true,
      lengthChange: false,
      bSort: true,
      bFilter: true,
      bProcessing: true,
      pagingType: "simple" , 
      bAutoWidth: true,
      sAjaxDataProp: "result",        
      bDeferRender: true,
      language : {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
      },
      
    });

  /*****DATATABLE*****/  
    var table_act = $('#tabla_act').DataTable({
      "iDisplayLength":-1, 
      "aaSorting" : [[3,'asc']],
      "scrollY": 420,
      "scrollX": true,
       select: true,
       
      columnDefs:[
       /* { "name": "acciones",targets: [0],searcheable : false,orderable:false}, 
        {targets: [8], orderData: [5,6]}, //al ordenar por fecha, se ordenera por especialidad,comuna
        {targets: [16],orderable:false,searcheable : false}, */
      ],

      "ajax": {
          "url":"<?php echo base_url();?>listaActividad",
          "dataSrc": function (json) {
            $(".btn_filtra_act").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtra_act").prop("disabled" , false);
              return json;
          },    
          data: function(param){
              param.empresa = $("#empresa").val();
          }
       },    

       "columns": [

          {
           "class":"","data": function(row,type,val,meta){
            btn='<a href="#!" title="Editar" data-hash="'+row.hash_id+'" class="btn_edita2"><i class="fa fa-edit" style="font-size:14px;"></i> </a>';
              return btn;
            }
          },
          
          { "data": "proyecto" ,"class":"margen-td "},
          { "data": "tipo_proyecto" ,"class":"margen-td "},
          { "data": "actividad" ,"class":"margen-td "},
          { "data": "unidad" ,"class":"margen-td "},
          { "data": "valor" ,"class":"margen-td "},    
          { "data": "porcentaje" ,"class":"margen-td "}
       ]
      }); 

      setTimeout( function () {
        var table_act = $.fn.dataTable.fnTables(true);
        if ( table_act.length > 0 ) {
            $(table_act).dataTable().fnAdjustColumnSizing();
      }}, 2000 );  

      $(document).on('keyup paste', '#buscador_act', function() {
        table_act.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var table_act = $.fn.dataTable.fnTables(true);
        if ( table_act.length > 0 ) {
            $(table_act).dataTable().fnAdjustColumnSizing();
      }}, 100 ); 

       $(document).off('click', '.btn_filtra_act').on('click', '.btn_filtra_act',function(event) {
          event.preventDefault();
           $(this).prop("disabled" , true);
           $(".btn_filtra_act").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
           table_act.ajax.reload();
        });



    /*******NUEVOINFORME**********/

      $(document).off('click', '.btn_act').on('click', '.btn_act',function(event) {
          $('#modal_act').modal('toggle'); 
          $(".btn_ingresa_act").html('<i class="fa fa-save"></i> Guardar');
          $(".btn_ingresa_act").attr("disabled", false);
          $(".cierra_mod_act").attr("disabled", false);
          $('#formActividad')[0].reset();
          $("#id_actividad").val("");
          $("#formActividad input,#formActividad select,#formActividad button,#formActividad").prop("disabled", false);
          /*$('#proyecto_tipo_act').val("").trigger('change');*/
          $('#actividad').val("").trigger('change');

      });     

      $(document).off('submit', '#formActividad').on('submit', '#formActividad',function(event) {
          var url="<?php echo base_url()?>";
          var formElement = document.querySelector("#formActividad");
          var formData = new FormData(formElement);
            $.ajax({
                url: $('#formActividad').attr('action')+"?"+$.now(),  
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                dataType: "json",
                contentType : false,
                beforeSend:function(){
                  $(".btn_ingresa_act").attr("disabled", true);
                  $(".cierra_mod_act").attr("disabled", true);
                  $("#formActividad input,#formActividad select,#formActividad button,#formActividad").prop("disabled", true);
                },
                success: function (data) {
                  if(data.res == "error"){
                     $(".btn_ingresa_act").attr("disabled", false);
                     $(".cierra_mod_act").attr("disabled", false);
                      $.notify(data.msg, {
                        className:'error',
                        globalPosition: 'top right',
                        autoHideDelay:5000,
                      });
                      $("#formActividad input,#formActividad select,#formActividad button,#formActividad").prop("disabled", false);
                  }else if(data.res == "ok"){
                    $(".btn_ingresa_act").attr("disabled", false);
                    $(".cierra_mod_act").attr("disabled", false);
                    $("#formActividad input,#formActividad select,#formActividad button,#formActividad").prop("disabled", false);
                    $.notify(data.msg, {
                      className:'success',
                      globalPosition: 'top right',
                      autoHideDelay:5000,
                    });
                    $('#modal_act').modal("toggle");
                    table_act.ajax.reload();
                  }
                }
          });
          return false; 
      });

       $.getJSON('getTiposPorPe', {pe: ""}, 
          function(response) {
              $("#proyecto_tipo_act").select2({
                 allowClear: true,
                 placeholder: 'Buscar...',
                 data: response
              });
        });

    
   /*******MODINFORME**********/
      $(document).off('click', '.btn_edita2').on('click', '.btn_edita2',function(event) {
         hash=$(this).attr("data-hash");
         $(".btn_ingresa_act").html('<i class="fa fa-edit" ></i> Modificar');
         $('#formActividad')[0].reset();
         $("#id_actividad").val("");
         $('#modal_act').modal("toggle");
         $(".cierra_mod_act").prop("disabled", false);
         $("#formActividad input,#formActividad select,#formActividad button,#formActividad").prop("disabled", false);

          $.ajax({
            url: "getDataActividad"+"?"+$.now(),  
            type: 'POST',
            cache: false,
            tryCount : 0,
            retryLimit : 3,
            data:{hash:hash},
            dataType:"json",
            beforeSend:function(){
             $(".btn_ingresa_act").prop("disabled",true); 
             $(".cierra_mod_act").prop("disabled",true); 
             $("#formActividad input,#formActividad select,#formActividad button,#formActividad").prop("disabled", true);
            },
            success: function (data) {
              if(data.res=="ok"){

                for(dato in data.datos){
                  actividad=data.datos[dato].actividad;
                  id_proyecto_empresa=data.datos[dato].id_proyecto_empresa;
                  id_proyecto_tipo=data.datos[dato].id_proyecto_tipo;

                    $("#id_actividad").val(data.datos[dato].hash_id);
                    $("#actividad").val(data.datos[dato].actividad);
                    $("#unidad").val(data.datos[dato].unidad);
                    $("#valor").val(data.datos[dato].valor);
                    $("#porcentaje").val(data.datos[dato].porcentaje);
                    $("#proyecto_empresa option[value='"+data.datos[dato].id_proyecto_empresa+"'").prop("selected", true);
                    $("#proyecto_tipo_act option[value='"+data.datos[dato].id_proyecto_tipo+"'").prop("selected", true);
                    
                    setTimeout( function () {
                       $.getJSON('getTiposPorPe', {pe: id_proyecto_empresa}, 
                        function(response) {
                            $("#proyecto_tipo_act").select2({
                               allowClear: true,
                               placeholder: 'Buscar...',
                               data: response
                            });
                      });
                    
                    $('#proyecto_tipo_act').val(id_proyecto_tipo).trigger('change');

                    },1000);
                }
                $("#formActividad input,#formActividad select,#formActividad button,#formActividad").prop("disabled", false);
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
                      $('#modal_act').modal("toggle");
                  }    
                  return;
              }

              if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
                  $('#modal_act').modal("toggle");
              }
          },timeout:5000
        }); 

      });

      /*$(document).off('click', '.btn_elimina').on('click', '.btn_elimina',function(event) {
        hash=$(this).attr("data-hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('deleteActividad'+"?"+$.now(),{"hash": hash}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
                table_act.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
          }
      });*/


       $(document).off('change', '#proyecto_empresa').on('change', '#proyecto_empresa', function(event) {
        event.preventDefault();
        pe=$("#proyecto_empresa").val();
        $('#proyecto_tipo_act').html('').select2({data: [{id: '', text: ''}]});
        $.getJSON('getTiposPorPe', {pe: pe}, 
         function(response) {
            $("#proyecto_tipo_act").select2({
             allowClear: true,
             placeholder: 'Buscar...',
             data: response
            });
       });
      });

      /*$(document).on('keyup', '#porcentaje', function(event) {
          event.preventDefault();
           var num = $("#porcentaje").val();
          
            if(num <= 0 || num > 100){
              $("#porcentaje").css("border-color", "red");
            }
            else{
              $("#porcentaje").css("border-color", "");
            }
        });*/

      /*$(".validRange").onclick(function(event){
        event.preventDefault();
           var num = $("#porcentaje").val();
          
            if(num <= 0 || num > 100){
              alert("porcentaje debe ser entre 1 y 100");
            }
            
      });*/

      
   /********OTROS**********/
    
    $(".floattext").keydown(function (event) {
      if (event.shiftKey == true) {
          event.preventDefault();
      }
      if ((event.keyCode >= 48 && event.keyCode <= 57) || 
          (event.keyCode >= 96 && event.keyCode <= 105) || 
          event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
          event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

      } else {
          event.preventDefault();
      }

      if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
          event.preventDefault(); 
    });

    $(".inttext").keydown(function (event) {
      if (event.shiftKey == true) {
          event.preventDefault();
      }
      if ((event.keyCode >= 48 && event.keyCode <= 57) || 
          (event.keyCode >= 96 && event.keyCode <= 105) || 
          event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
          event.keyCode == 39 || event.keyCode == 46) {
      } else {
          event.preventDefault();
      }
    });


       $(document).on('click', '.btn_excel_inf', function(event) {
           event.preventDefault();
           var empresa = $("#empresa").val();
          
            if(empresa==""){
              empresa=0;
            }

           window.location="excelInforme/"+empresa;
        });


})    
                    
</script>

<!--FILTROS-->

  <div class="form-row">
    <div class="col-6 col-lg-2">
         <div class="form-group"> 
         <button type="button" class="btn-block btn btn-sm btn-sm btn-outline-primary btn_act">
         <i class="fa fa-plus-circle"></i> Nuevo 
         </button>
         </div>
    </div>

    <div class="col-lg-3">  
      <div class="form-group">
          <select id="empresa" name="empresa" class="custom-select custom-select-sm">
            <option selected value="">Proyecto empresa | Todos</option>
            <?php  
              foreach($proyecto_empresa as $p){
            ?>
            <option value="<?php echo $p["id"]; ?>"><?php echo $p["nombre"]; ?></option>
            <?php
            }
            ?>
          </select>
        </div>
    </div>

    <div class="col-lg-5">
       <div class="form-group">
        <input type="text" placeholder="Ingrese su busqueda..." id="buscador_act" class="buscador_act form-control form-control-sm">
       </div>
    </div>

    <div class="col-6 col-lg-1">
       <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_filtra_act">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
    </div>

    <div class="col-6 col-lg-1">  
         <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_excel_inf">
         <i class="fa fa-save"></i> Excel 
         </button>
         </div>
    </div>
  </div>

<!-- LISTADO -->

<div class="row">
    <div class="col">
        <table id="tabla_act" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th style="width:80px;">Acciones</th>
                    <th>Proyecto Empresa</th>
                    <th>Proyecto Tipo</th>
                    <th>Actividad</th>  
                    <th>Unidad</th>  
                    <th>Valor</th>  
                    <th>Porcentaje</th>
                </tr>
             </thead>
        </table>
    </div>
</div>

<!-- FORMULARIOS-->
  
  <!--  NUEVO INFORME-->
<div id="modal_act"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <?php echo form_open_multipart("formActividad",array("id"=>"formActividad","class"=>"formActividad"))?>
               <input type="hidden" name="id_actividad" id="id_actividad">
           
               <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
               <fieldset class="form-ing-cont">
                <legend class="form-ing-border">Creaci&oacute;n De Actividades</legend>
                    
                    <div class="form-row">
                      <div class="col-lg-3">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto Empresa</label>
                            <select id="proyecto_empresa" name="proyecto_empresa" class="custom-select custom-select-sm">
                            <option selected value="">Seleccione Proyecto Empresa</option>
                            <?php  
                            foreach($proyecto_empresa as $p){
                              ?>
                              <option value="<?php echo $p["id"]; ?>"><?php echo $p["nombre"]; ?></option>
                              <?php
                            }
                            ?>
                            </select>
                        </div>
                      </div> 
                            
                      <div class="col-lg-3">  
                        <div class="form-group">
                         <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto Tipo</label>
                            <select id="proyecto_tipo_act" name="proyecto_tipo" class="custom-select custom-select-sm"  style="width:100%!important;">
                            <option selected value="">Seleccione Tipo Proyecto </option>
                            </select>
                        </div>
                      </div>  

                      <div class="col-lg-4">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Actividad</label>
                            <input type="text" autocomplete="off" placeholder="Ingrese Nombre De Actividad" class="form-control form-control-sm"  name="actividad" id="actividad">
                        </div>
                      </div>  

                      <div class="col-lg-2">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Unidad</label>
                            <input type="text" autocomplete="off" placeholder="Ingrese Unidad" class="form-control form-control-sm"  name="unidad" id="unidad">
                        </div>
                      </div>    

                      <div class="col-lg-3">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Valor</label>
                            <input type="text" id="valor" autocomplete="off" placeholder="Ingrese Valor" class="inttext form-control form-control-sm"  name="valor">
                        </div>
                      </div>   

                      <div class="col-lg-3">  
                      <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Porcentaje</label>
                          <input type="text" minlength="1" maxlength="3" autocomplete="off" placeholder="Ingrese Porcentaje" class="inttext form-control form-control-sm"  name="porcentaje" id="porcentaje" onsubmit="return validarRango(this);">
                      </div>
                      </div> 
                    </div>
                </fieldset>

                <br>

                <div class="col-lg-4 offset-lg-4">
                  <div class="form-row">
                    <div class="col-6 col-lg-6">
                      <div class="form-group">
                        <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingresa_act">
                         <i class="fa fa-save"></i> Guardar
                        </button>
                      </div>
                    </div>

                    <div class="col-6 col-lg-6">
                      <button class="btn-block btn btn-sm btn-dark cierra_mod_inf" data-dismiss="modal" aria-hidden="true">
                       <i class="fa fa-window-close"></i> Cerrar
                      </button>
                    </div>
                  </div>
                </div>

               </div>
             <?php echo form_close(); ?>
          </div>
        </div>
      </div>
