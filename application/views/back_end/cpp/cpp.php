<script type="text/javascript">
  $(function(){

  /*****DEFINICIONES*****/
      $.fn.modal.Constructor.prototype.enforceFocus = function() {};
      var fecha_hoy="<?php echo $fecha_hoy; ?>";
      $("#desdef").val(fecha_hoy);
      $("#hastaf").val(fecha_hoy);   
      var session="<?php echo $this->session->userdata("id"); ?>";
      var perfil="<?php echo $this->session->userdata("perfil"); ?>";
      var base="<?php echo base_url();?>";
    
      $.extend(true,$.fn.dataTable.defaults,{
        info:false,
        paging:true,
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
      var table_cpp = $('#tabla_cpp').DataTable({
        "iDisplayLength":50, 
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
            "url":"<?php echo base_url();?>listaCPP",
            "dataSrc": function (json) {
                $(".btn_filtra_cpp").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
                $(".btn_filtra_cpp").prop("disabled" , false);
                desde = $("#desdef").val();
                hasta = $("#hastaf").val();
                return json;
            },       
            data: function(param){
                param.desde = $("#desdef").val();
                param.hasta = $("#hastaf").val();
            }
         },    

         "columns": [

            {
             "class":"","data": function(row,type,val,meta){
                btn='<a href="#!" title="Editar" data-hash="'+row.hash_id+'" class="btn_edita"><i class="fa fa-edit" style="font-size:14px;"></i> </a>';
                btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash_id+'" class="btn_elimina"><i class="fa fa-trash" style="font-size:14px;"></i> </a>';
                return btn;
              }
            },
            {
             "class":"","data": function(row,type,val,meta){
                if(row.estado==0){
                    b='<i class="fa fa-question-circle" style="color:red;font-size:13px;margin-top:2px;"></i> '+row.estado_str+'';
                }
                return b;
              }
            },
            { "data": "usuario" ,"class":"margen-td "},
            { "data": "proyecto_empresa" ,"class":"margen-td "},
            { "data": "proyecto_tipo" ,"class":"margen-td "},
            { "data": "actividad" ,"class":"margen-td "},
            { "data": "fecha" ,"class":"margen-td "},    
            { "data": "supervisor" ,"class":"margen-td "},
            { "data": "fecha_aprob" ,"class":"margen-td "},
            { "data": "digitador" ,"class":"margen-td "},
            { "data": "fecha_dig" ,"class":"margen-td "},
            { "data": "proyecto_desc" ,"class":"margen-td "},
            { "data": "cantidad" ,"class":"margen-td "},
            { "data": "comentarios" ,"class":"margen-td "},
            { "data": "ultima_actualizacion" ,"class":"margen-td "}
         ]
        }); 

        setTimeout( function () {
          var table_cpp = $.fn.dataTable.fnTables(true);
          if ( table_cpp.length > 0 ) {
              $(table_cpp).dataTable().fnAdjustColumnSizing();
        }}, 2000 );  

        $(document).on('keyup paste', '#buscador_inf', function() {
          table_cpp.search($(this).val().trim()).draw();
        });

        String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

        setTimeout( function () {
          var table_cpp = $.fn.dataTable.fnTables(true);
          if ( table_cpp.length > 0 ) {
              $(table_cpp).dataTable().fnAdjustColumnSizing();
        }}, 100 ); 

        $(document).off('click', '.btn_filtra_cpp').on('click', '.btn_filtra_cpp',function(event) {
          event.preventDefault();
           $(this).prop("disabled" , true);
           $(".btn_filtra_cpp").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
           table_cpp.ajax.reload();
        });


      /*******NUEVOINFORME**********/

        $(document).off('click', '.btn_cpp').on('click', '.btn_cpp',function(event) {
            $('#modal_cpp').modal('toggle'); 
            $(".btn_ingresa_cpp").html('<i class="fa fa-save"></i> Guardar');
            $(".btn_ingresa_cpp").attr("disabled", false);
            $(".cierra_mod_cpp").attr("disabled", false);
            $('#formCPP')[0].reset();
            $("#id_cpp").val("");
            $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", false);

            $('#proyecto_tipo').val("").trigger('change');
            $('#actividad').val("").trigger('change');

        });     

        $(document).off('submit', '#formCPP').on('submit', '#formCPP',function(event) {
            var url="<?php echo base_url()?>";
            var formElement = document.querySelector("#formCPP");
            var formData = new FormData(formElement);
              $.ajax({
                  url: $('#formCPP').attr('action')+"?"+$.now(),  
                  type: 'POST',
                  data: formData,
                  cache: false,
                  processData: false,
                  dataType: "json",
                  contentType : false,
                  beforeSend:function(){
                    /*$(".btn_ingresa_cpp").attr("disabled", true);
                    $(".cierra_mod_cpp").attr("disabled", true);
                    $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", true);*/
                  },
                  success: function (data) {
                    if(data.res == "error"){
                       $(".btn_ingresa_cpp").attr("disabled", false);
                       $(".cierra_mod_cpp").attr("disabled", false);
                        $.notify(data.msg, {
                          className:'error',
                          globalPosition: 'top right',
                          autoHideDelay:5000,
                        });
                        $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", false);
                    }else if(data.res == "ok"){
                      $(".btn_ingresa_cpp").attr("disabled", false);
                      $(".cierra_mod_cpp").attr("disabled", false);
                      $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", false);
                      $.notify(data.msg, {
                        className:'success',
                        globalPosition: 'top right',
                        autoHideDelay:5000,
                      });
                      /*$('#modal_cpp').modal("toggle");*/
                      table_cpp.ajax.reload();
                    }
                  }
            });
            return false; 
        });

         $.getJSON('getTiposPorPe', {pe: ""}, 
            function(response) {
                $("#proyecto_tipo").select2({
                   allowClear: true,
                   placeholder: 'Buscar...',
                   data: response
                });
          });

         $.getJSON('getActividadesPorTipo', {pt: ""}, 
            function(response) {
                $("#actividad").select2({
                 allowClear: true,
                 placeholder: 'Buscar...',
                 data: response
                });
          });

     /*******MODINFORME**********/
        $(document).off('click', '.btn_edita').on('click', '.btn_edita',function(event) {
           hash=$(this).attr("data-hash");
           $(".btn_ingresa_cpp").html('<i class="fa fa-edit" ></i> Modificar');
           $('#formCPP')[0].reset();
           $("#id_cpp").val("");
           $('#modal_cpp').modal("toggle");
           $(".cierra_mod_cpp").prop("disabled", false);
           $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", true);

            $.ajax({
              url: "getDataAct"+"?"+$.now(),  
              type: 'POST',
              cache: false,
              tryCount : 0,
              retryLimit : 3,
              data:{hash:hash},
              dataType:"json",
              beforeSend:function(){
               /*$(".btn_ingresa_cpp").prop("disabled",true); 
               $(".cierra_mod_cpp").prop("disabled",true); */
              },
              success: function (data) {
                if(data.res=="ok"){

                  for(dato in data.datos){
                      estado=data.datos[dato].id_estado;
                      id_actividad=data.datos[dato].id_actividad;
                      id_proyecto_empresa=data.datos[dato].id_proyecto_empresa;
                      id_proyecto_tipo=data.datos[dato].id_proyecto_tipo;

                      $("#id_cpp").val(data.datos[dato].hash_id);
                      $("#proyecto_empresa option[value='"+data.datos[dato].id_proyecto_empresa+"'").prop("selected", true);

                      setTimeout( function () {
                         $.getJSON('getTiposPorPe', {pe: id_proyecto_empresa}, 
                          function(response) {
                              $("#proyecto_tipo").select2({
                                 allowClear: true,
                                 placeholder: 'Buscar...',
                                 data: response
                              });
                        });
                      
                      $('#proyecto_tipo').val(id_proyecto_tipo).trigger('change');

                      },1000); 

                      setTimeout( function () {
                        $.getJSON('getActividadesPorTipo', {pt: id_proyecto_tipo}, 
                          function(response) {
                              $("#actividad").select2({
                               allowClear: true,
                               placeholder: 'Buscar...',
                               data: response
                              });
                        });
                      $('#actividad').val(id_actividad).trigger('change');

                      },1500); 


                      $("#cantidad").val(data.datos[dato].cantidad);
                      $("#fecha_finalizacion").val(data.datos[dato].fecha);
                      $("#proyecto_desc").val(data.datos[dato].proyecto_desc);
                      $("#comentarios").val(data.datos[dato].comentarios);
                  }
                  $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", false);
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
                        $('#modal_cpp').modal("toggle");
                    }    
                    return;
                }

                if (xhr.status == 500) {
                    $.notify("Problemas en el servidor, intente más tarde.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                    $('#modal_cpp').modal("toggle");
                }
            },timeout:5000
          }); 

        });

        $(document).off('click', '.btn_elimina').on('click', '.btn_elimina',function(event) {
          hash=$(this).attr("data-hash");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
              $.post('eliminaActividad'+"?"+$.now(),{"hash": hash}, function(data) {
                if(data.res=="ok"){
                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right'
                  });
                 table_cpp.ajax.reload();
                }else{
                  $.notify(data.msg, {
                    className:'danger',
                    globalPosition: 'top right'
                  });
                }
              },"json");
            }
        });


         $(document).off('change', '#proyecto_empresa').on('change', '#proyecto_empresa', function(event) {
          event.preventDefault();
          pe=$("#proyecto_empresa").val();
          $('#proyecto_tipo').html('').select2({data: [{id: '', text: ''}]});
          $.getJSON('getTiposPorPe', {pe: pe}, 
           function(response) {
              $("#proyecto_tipo").select2({
               allowClear: true,
               placeholder: 'Buscar...',
               data: response
              });
         });
        });

        $(document).off('change', '#proyecto_tipo').on('change', '#proyecto_tipo', function(event) {
          event.preventDefault();
          pt=$("#proyecto_tipo").val();
          $('#actividad').html('').select2({data: [{id: '', text: ''}]});
          $.getJSON('getActividadesPorTipo', {pt: pt}, 
           function(response) {
              $("#actividad").select2({
               allowClear: true,
               placeholder: 'Buscar...',
               data: response
              });
          });
        });


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

       $(".fecha_normal").datetimepicker({
          format: "YYYY-MM-DD",
          locale:"es",
          maxDate:"now"
        });


        $(document).off('click', '.btn_excel_informe').on('click', '.btn_excel_informe',function(event) {
           event.preventDefault();
           hash=$("#id_cpp").val();
           window.location="excelInforme/"+hash;
        });

  })
</script>

<!--FILTROS-->

  <div class="form-row">
    <div class="col-6 col-lg-2">
         <div class="form-group"> 
         <button type="button" class="btn-block btn btn-sm btn-sm btn-outline-primary btn_cpp">
         <i class="fa fa-plus-circle"></i> Nuevo 
         </button>
         </div>
    </div>

    <div class="col-lg-3">
      <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i></span>
        </div>
          <input type="text" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desdef" id="desdef">
          <input type="text" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hastaf" id="hastaf">
      </div>
    </div>
    </div>

    <div class="col-lg-3">
       <div class="form-group">
        <input type="text" placeholder="Ingrese su busqueda..." id="buscador_inf" class="buscador_inf form-control form-control-sm">
       </div>
    </div>

    <div class="col-6 col-lg-1">
       <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_filtra_cpp">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
    </div>

    <div class="col-6 col-lg-1">  
         <div class="form-group">
         <button type="button" disabled class="btn-block btn btn-sm btn-outline-primary btn-primary btn_excel_inf">
         <i class="fa fa-save"></i> Excel 
         </button>
         </div>
    </div>

  </div>

<!-- LISTADO -->

  <div class="row">
    <div class="col">
    <table id="tabla_cpp" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
      <thead>
        <tr>
          <th>Acciones</th>
          <th>Estado</th>
          <th>Usuario</th>  
          <th>Proyecto Empresa</th>  
          <th>Proyecto Tipo</th>  
          <th>Actividad</th>  
          <th>Fecha finalizaci&oacute;n</th>
          <th>Supervisor</th>
          <th>Fecha Aprobaci&oacute;n</th>
          <th>Digitador</th> 
          <th>Fecha Digitaci&oacute;n</th>   
          <th>Proyecto Descripci&oacute;n</th>
          <th>Cantidad</th>
          <th>Observaciones</th>    
          <th>&Uacute;ltima actualizaci&oacute;n</th>    
        </tr>
      </thead>

    </table>
    </div>
  </div>
  

<!-- FORMULARIOS-->
  
  <!--  NUEVO INFORME-->
     <div id="modal_cpp"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <?php echo form_open_multipart("formCPP",array("id"=>"formCPP","class"=>"formCPP"))?>
               <input type="hidden" name="id_cpp" id="id_cpp">
           
               <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
               <fieldset class="form-ing-cont">
                <legend class="form-ing-border">Ingreso de actividades</legend>
                    
                    <div class="form-row">
                      <div class="col-lg-3">  
                        <div class="form-group">
                         <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto empresa</label>
                            <select id="proyecto_empresa" name="proyecto_empresa" class="custom-select custom-select-sm">
                            <option selected value="">Seleccione Proyecto empresa</option>
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
                         <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto tipo</label>
                            <select id="proyecto_tipo" name="proyecto_tipo" class="custom-select custom-select-sm"  style="width:100%!important;">
                            <option selected value="">Seleccione Tipo proyecto </option>
                            </select>
                        </div>
                      </div>  

                      <div class="col-lg-4">  
                        <div class="form-group">
                        <label>Actividad</label>
                          <select id="actividad" name="actividad" class="custom-select custom-select-sm" style="width:100%!important;">
                            <option selected value="">Ingrese actividad</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-lg-2">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Cantidad</label>
                            <input type="text" autocomplete="off" placeholder="Ingrese cantidad" class="inttext form-control form-control-sm"  name="cantidad" id="cantidad">
                        </div>
                      </div>  

                      <div class="col-lg-2">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha finalizaci&oacute;n</label>
                            <input type="text" autocomplete="off" placeholder="Ingrese Fecha finalizaci&oacute;n" class="fecha_normal fecha_finalizacion form-control form-control-sm"  name="fecha_finalizacion" id="fecha_finalizacion">
                        </div>
                      </div>    

                      <div class="col-lg-4">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto descripci&oacute;n</label>
                            <input type="text" id="proyecto_desc" autocomplete="off" placeholder="Ingrese Proyecto descripci&oacute;n" class="form-control form-control-sm"  name="proyecto_desc">
                        </div>
                      </div>   

                      <div class="col-lg-6">  
                      <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Comentarios</label>
                          <input type="text" autocomplete="off" placeholder="Ingrese Comentarios" class="form-control form-control-sm"  name="comentarios" id="comentarios">
                      </div>
                      </div> 

                      <!-- <div class="col-lg-2">  
                        <div class="form-group">
                        <label>Estado</label>
                        <select id="actividad" name="actividad" style="width:100%!important;">
                          <option value="">Ingrese actividad...</option>
                        </select>
                        </div>
                      </div> -->

                    </div>
                </fieldset>

                <br>

                <div class="col-lg-4 offset-lg-4">
                  <div class="form-row">
                    <div class="col-6 col-lg-6">
                      <div class="form-group">
                        <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingresa_cpp">
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
