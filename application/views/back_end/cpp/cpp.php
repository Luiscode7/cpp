<style type="text/css">
  .btn_elimina{
    margin-left: 10px!important;
  }
  .ver_obs_desp,.ver_obs{
    cursor: pointer;
    display: inline;
    margin-left: 5px;
    font-size: 13px;
    color: #2780E3;
  }
  .btndesp span:hover{
    color:#26A69A!important;
  }
</style>
<script type="text/javascript">
  $(function(){

  /*****DEFINICIONES*****/
      $.fn.modal.Constructor.prototype.enforceFocus = function() {};
      $('.clockpicker').clockpicker();
      var fecha_hoy="<?php echo $fecha_hoy; ?>";
      var fecha_anio_atras="<?php echo $fecha_anio_atras; ?>";
      $("#desdef").val(fecha_anio_atras);
      $("#hastaf").val(fecha_hoy);   
      $("#fecha_inicio").val(fecha_hoy);   
      $("#fecha_finalizacion").val(fecha_hoy);   
      var idUsuarioCPP="<?php echo $this->session->userdata("idUsuarioCPP"); ?>";
      var id_perfil_CPP="<?php echo $this->session->userdata("id_perfil_CPP"); ?>";
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

      function visibilidadExcel(){
        if(id_perfil_CPP==4){
          $(".btn_excel_act").hide();
        }else{
          $(".btn_excel_act").show();
        }
      }

      visibilidadExcel();

      function iniciaEjecutor(){

        if(id_perfil_CPP==1 || id_perfil_CPP==2){//ADMIN,GERENTES
          accion = 1;//TODO
        }
        if(id_perfil_CPP==3){//SUPERVISOR
          accion = 2;//SU PERSONAL
        }
        if(id_perfil_CPP==4){//EJECUTOR
          accion = 3;//SUS REGISTROS
        }

        $.getJSON('getUsuariosSel2CPP', {accion:accion}, 
            function(response) {
                $("#select_usuario").select2({
                   allowClear: true,
                   placeholder: 'Seleccione usuario',
                   data: response
            });
        });

        setTimeout( function () {
         $('#select_usuario').val(idUsuarioCPP).trigger('change'); 
        }, 2000 );  
       
      }

    /*****DATATABLE*****/  
      var table_cpp = $('#tabla_cpp').DataTable({
        "iDisplayLength":50, 
        "aaSorting" : [[1,'asc']],
        "scrollY": 420,
        "scrollX": true,
         select: true,

         "columnDefs":[
            {targets: [13],  visible : id_perfil_CPP!=4 ? true : false  }, 
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

                if(id_perfil_CPP==1 || id_perfil_CPP==2){//ADMIN,GERENTES
                  accion = 1;//TODO
                }
                if(id_perfil_CPP==3){//SUPERVISOR
                  accion = 2;//SU PERSONAL
                }
                if(id_perfil_CPP==4){//EJECUTOR
                  accion = 3;//SUS REGISTROS
                }
                param.accion = accion

            }
         },    

         "columns": [
            {
             "class":"","data": function(row,type,val,meta){
                if(id_perfil_CPP==1 || id_perfil_CPP==3){
                  btn='<center><a href="#!" title="Editar" data-hash="'+row.hash_id+'" class="btn_edita"><i class="fa fa-edit" style="font-size:15px;"></i> </a>';
                  btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash_id+'" class="btn_elimina"><i class="fa fa-trash" style="font-size:15px;"></i> </a></center>';
                }else if(id_perfil_CPP==4 && idUsuarioCPP==row.id_usuario){
                  if(row.estado==0){
                      btn='<center><a href="#!" title="Editar" data-hash="'+row.hash_id+'" class="btn_edita"><i class="fa fa-edit" style="font-size:15px;"></i> </a>';
                  }else{
                    btn="<center>-</center>";
                  }
                  //btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash_id+'" class="btn_elimina"><i class="fa fa-trash" style="font-size:14px;"></i> </a></center>';
                }else{
                   btn="<center>-</center>";
                }
                return btn;
              }
            },
            {
             "class":"","data": function(row,type,val,meta){
                if(row.estado==0){
                    b='<i class="fa fa-question-circle" style="color:red;font-size:13px;margin-top:2px;"></i> '+row.estado_str+'';
                }else
                if(row.estado==1){
                    b='<i class="fa fa-check-circle" style="color:#1E748D;font-size:13px;margin-top:2px;"></i> '+row.estado_str+'';
                }
                return b;
              }
            },
            { "data": "ejecutor" ,"class":"margen-td"},
            { "data": "fecha_inicio" ,"class":"margen-td"},
            {
             "class":"","data": function(row,type,val,meta){
                return row.hora_inicio.substring(0, 5);
              }
            },
            { "data": "fecha_termino" ,"class":"margen-td"},
            {
             "class":"","data": function(row,type,val,meta){
                return row.hora_termino.substring(0, 5);
              }
            },
            {
             "class":"","data": function(row,type,val,meta){
                return row.duracion;
              }
            },
            { "data": "proyecto_empresa" ,"class":"margen-td"},
            { "data": "proyecto_tipo" ,"class":"margen-td"},
            { "data": "actividad" ,"class":"margen-td"},
            { "data": "proyecto_desc" ,"class":"margen-td"},
            { "data": "unidad" ,"class":"margen-td"},    
            { "data": "valor" ,"class":"margen-td"},    
            { "data": "cantidad" ,"class":"margen-td"},    
            { "data": "supervisor" ,"class":"margen-td"},
            { "data": "fecha_aprob" ,"class":"margen-td"},
            {
             "class":"","data": function(row,type,val,meta){
                return row.hora_aprob.substring(0, 5);
              }
            },
            { "data": "digitador" ,"class":"margen-td"},
            { "data": "fecha_dig" ,"class":"margen-td"},

            {
             "class":"centered margen-td","data": function(row,type,val,meta){
              obs=row.comentarios.toLowerCase().capitalize();
              var str = obs;
              if(str.length > 40) {
                 str = str.substring(0,40)+"...";
                 return "<span class='btndesp2'>"+str+"</span><span title='Ver texto completo' class='ver_obs_desp' data-tit="+obs.replace(/ /g,"_")+" data-title="+obs.replace(/ /g,"_")+">Ver más</span>";
              }else{
                 return "<span class='btndesp2' data-title="+obs.replace(/ /g,"_")+">"+obs+"</span>";
              }
              }
            },

            { "data": "ultima_actualizacion" ,"class":"margen-td"}
         ]
        }); 
        
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
        }}, 1000 ); 

        $(document).off('click', '.btn_filtra_cpp').on('click', '.btn_filtra_cpp',function(event) {
          event.preventDefault();
           $(this).prop("disabled" , true);
           $(".btn_filtra_cpp").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
           table_cpp.ajax.reload();
        });

        $(document).on('click', '.ver_obs_desp', function(event) {
            event.preventDefault();
            val=$(this).attr("data-tit");
            elem=$(this);
            if(elem.text()=="Ver más"){
              elem.html("Ocultar");     
              elem.attr("title","Acortar texto");
              elem.prev(".btndesp2").text(val.replace(/_/g, ' '));
              var table = $.fn.dataTable.fnTables(true);
              if ( table.length > 0 ) {
                  $(table).dataTable().fnAdjustColumnSizing();
              }
            }else if(elem.text()=="Ocultar"){
              val = val.substring(0,40)+"...";
              elem.prev(".btndesp2").text(val.replace(/_/g, ' '));     
              elem.html("Ver más");
              elem.attr("title","Ver texto completo");
              var table = $.fn.dataTable.fnTables(true);
              if ( table.length > 0 ) {
                  $(table).dataTable().fnAdjustColumnSizing();
              }
            }
          });


    /*******NUEVO**********/

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
          $("#estado").attr("disabled", true);
          $("#unidad_medida").attr("disabled", true);
          $("#fecha_inicio").val(fecha_hoy);   
          $("#fecha_finalizacion").val(fecha_hoy);   
          iniciaEjecutor();
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
                  $(".btn_ingresa_cpp").attr("disabled", true);
                  $(".cierra_mod_cpp").attr("disabled", true);
                  $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", true);
                  $(".btn_ingresa_cpp").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...');
                },
                success: function (data) {
                  if(data.res == "error"){
                      $(".btn_ingresa_cpp").attr("disabled", false);
                      $(".cierra_mod_cpp").attr("disabled", false);
                      $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", false);
                      $("#estado").attr("disabled", true);
                      $("#unidad_medida").attr("disabled", true);

                      $.notify(data.msg, {
                        className:'error',
                        globalPosition: 'top right',
                        autoHideDelay:5000,
                      });

                      if($("#id_cpp").val()!=""){
                        $(".btn_ingresa_cpp").html('<i class="fa fa-edit"></i> Modificar');
                        $('#modal_cpp').modal("toggle");
                      }else{
                        $(".btn_ingresa_cpp").html('<i class="fa fa-save"></i> Guardar');
                      }
                      iniciaEjecutor();

                  }else if(data.res == "ok"){
                      $(".btn_ingresa_cpp").attr("disabled", false);
                      $(".cierra_mod_cpp").attr("disabled", false);
                      $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", false);
                      $("#estado").attr("disabled", true);
                      $("#unidad_medida").attr("disabled", true);
                      
                      $.notify(data.msg, {
                        className:'success',
                        globalPosition: 'top right',
                        autoHideDelay:5000,
                      });
                      table_cpp.ajax.reload();

                      if($("#id_cpp").val()!=""){
                        $(".btn_ingresa_cpp").html('<i class="fa fa-edit"></i> Modificar');
                        $('#modal_cpp').modal("toggle");
                      }else{
                        $(".btn_ingresa_cpp").html('<i class="fa fa-save"></i> Guardar');
                      }
                      iniciaEjecutor();
                  }else if(data.res=="sess"){
                    window.location="unlogin";
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

    /*******MODIFICAR**********/
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
             $(".btn_ingresa_cpp").prop("disabled",true); 
             $(".cierra_mod_cpp").prop("disabled",true); 
            },
            success: function (data) {
              if(data.res=="ok"){
                setTimeout( function () {
                    $("#formCPP input,#formCPP select,#formCPP button,#formCPP").prop("disabled", false);
                    $("#unidad_medida").attr("disabled", true);
                     if(id_perfil_CPP==1 || id_perfil_CPP==3){
                       $("#estado").attr("disabled", false);
                       /*$("#apr_sp").show();*/
                     }else{
                       /*$("#apr_sp").hide();*/
                      $("#estado").attr("disabled", true);
                     }
                     
                },1500); 
                 $("#estado").attr("disabled", true);
                 for(dato in data.datos){

                    estado=data.datos[dato].id_estado;
                    id_actividad=data.datos[dato].id_actividad;
                    id_proyecto_empresa=data.datos[dato].id_proyecto_empresa;
                    id_proyecto_tipo=data.datos[dato].id_proyecto_tipo;

                    $("#id_cpp").val(data.datos[dato].hash_id);
                    $("#proyecto_empresa option[value='"+data.datos[dato].id_proyecto_empresa+"'").prop("selected", true);
                    $("#estado option[value='"+data.datos[dato].estado+"'").prop("selected", true);

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

                    },600); 

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

                    },1200); 


                    /*$('#select_usuario').html('').select2({data: [{id: '', text: ''}]});
                    $.getJSON('getUsuariosSel2CPP', {idUsuarioCPP:data.datos[dato].id_usuario}, 
                        function(response) {
                            $("#select_usuario").select2({
                               allowClear: true,
                               placeholder: 'Seleccione usuario',
                               data: response
                        });
                    });

                    setTimeout( function () {
                      $('#select_usuario').val(data.datos[dato].id_usuario).trigger('change'); 
                    }, 2000 );  */

                    if(id_perfil_CPP==1 || id_perfil_CPP==2){//ADMIN,GERENTES
                      accion = 1;//TODO
                    }
                    if(id_perfil_CPP==3){//SUPERVISOR
                      accion = 2;//SU PERSONAL
                    }
                    if(id_perfil_CPP==4){//EJECUTOR
                      accion = 3;//SUS REGISTROS
                    }

                    $.getJSON('getUsuariosSel2CPP', {accion:accion}, 
                        function(response) {
                            $("#select_usuario").select2({
                               allowClear: true,
                               placeholder: 'Seleccione usuario',
                               data: response
                        });
                    });
                    setTimeout( function () {
                     $('#select_usuario').val(data.datos[dato].id_usuario).trigger('change'); 
                    }, 2000 );  



                    $("#cantidad").val(data.datos[dato].cantidad);
                    $("#fecha_inicio").val(data.datos[dato].fecha_inicio);
                    $("#hora_inicio").val(data.datos[dato].hora_inicio.substring(0, 5));
                    $("#fecha_finalizacion").val(data.datos[dato].fecha_termino);
                    $("#hora_finalizacion").val(data.datos[dato].hora_termino.substring(0, 5));
                    $("#proyecto_desc").val(data.datos[dato].proyecto_desc);
                    $("#comentarios").val(data.datos[dato].comentarios);
                }
              }else if(data.res=="sess"){
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
              }else if(data.res=="error"){
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }else if(data.res=="sess"){
                  window.location="unlogin";
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
        $("#unidad_medida").val("");
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
        $("#unidad_medida").val("");
      });

      $(document).off('change', '#actividad').on('change', '#actividad', function(event) {
        event.preventDefault();
        ac=$("#actividad").val();
        $.post('getUmPorActividad'+"?"+$.now(),{"ac": ac}, function(data) {
            if(data.res=="ok"){
             $("#unidad_medida").val(data.dato);
            }else{
              /*$.notify(data.msg, {
                className:'error',
                globalPosition: 'top right',
                autoHideDelay:5000,
              });*/
            }
        },"json");          
        
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

      /*$(".hora_text").keydown(function (event) {
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

      });*/

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

        $(document).off('click', '.btn_excel_act').on('click', '.btn_excel_act',function(event) {
           event.preventDefault();
           var desde = $("#desdef").val();
           var hasta = $("#hastaf").val();
          
           if(desde==""){
             $.notify("Debe seleccionar una fecha de inicio.", {
                 className:'error',
                 globalPosition: 'top right'
             });  
             return false;
           }
           if(hasta==""){
             $.notify("Debe seleccionar una fecha de término.", {
                 className:'error',
                 globalPosition: 'top right'
             });  
            return false;
           }
           
           window.location="excelTareas/"+desde+"/"+hasta;

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

    <div class="col-lg-5">
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
         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_excel_act">
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
          <th>Ejecutor</th>  
          <th>Fecha inicio</th>
          <th>Hr ini.</th>
          <th>Fecha fin.</th>
          <th>Hr fin.</th>
          <th>Duraci&oacute;n</th>
          <th>Proyecto Empresa</th>  
          <th>Proyecto Tipo</th>  
          <th>Actividad</th>  
          <th>Proyecto Descripci&oacute;n</th>
          <th>Unidad</th>
          <th>Valor</th>
          <th>Cantidad</th>
          <th>Supervisor</th> 
          <th>Fecha Aprob</th>   
          <th>Hr Aprob</th>   
          <th>Digitador</th>
          <th>Fecha digitaci&oacute;n</th>
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
                         <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Usuario</label>
                            <select id="select_usuario" name="ejecutor" class="custom-select custom-select-sm"  style="width:100%!important;">
                            <option selected value="">Seleccione usuario </option>
                            </select>
                        </div>
                      </div> 

                      <div class="col-lg-3">  
                        <div class="form-group">
                         <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto empresa</label>
                            <select id="proyecto_empresa" name="proyecto_empresa" class="custom-select custom-select-sm">
                            <option selected value="">Seleccione proyecto empresa</option>
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
                            <option selected value="">Seleccione tipo proyecto </option>
                            </select>
                        </div>
                      </div>  

                      <div class="col-lg-3">  
                        <div class="form-group">
                        <label>Actividad</label>
                          <select id="actividad" name="actividad" class="custom-select custom-select-sm" style="width:100%!important;">
                            <option selected value="">Ingrese actividad</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-lg-4">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto descripci&oacute;n</label>
                            <input type="text" id="proyecto_desc" autocomplete="off" placeholder="Ingrese Proyecto descripci&oacute;n" class="form-control form-control-sm"  name="proyecto_desc">
                        </div>
                      </div>   

                      <div class="col-lg-2">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Unidad medida</label>
                            <input type="text" autocomplete="off"  placeholder="" class="form-control form-control-sm"  name="" id="unidad_medida">
                        </div>
                      </div>  


                      <div class="col-lg-2">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Cantidad</label>
                            <input type="text" maxlength="5" autocomplete="off" placeholder="" class="inttext form-control form-control-sm"  name="cantidad" id="cantidad">
                        </div>
                      </div>  

                      <div class="col-lg-2">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha inicio</label>
                            <input type="text" autocomplete="off" placeholder="Ingrese Fecha inicio" class="fecha_normal fecha_inicio form-control form-control-sm"  name="fecha_inicio" id="fecha_inicio">
                        </div>
                      </div>    


                      <div class="col-lg-2">  
                       <div class="form-group">   
                         <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Hora inicio</label>
                            <input type="text" autocomplete="off" maxlength="5" class="clockpicker hora_text form-control form-control-sm"  data-autoclose="true"  data-align="top"  placeholder="Hora" autocomplete="on"  id="hora_inicio"  name="hora_inicio">
                       </div>
                      </div>

                      <div class="col-lg-2">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha finalizaci&oacute;n</label>
                            <input type="text" autocomplete="off" placeholder="Ingrese Fecha finalizaci&oacute;n" class="fecha_normal fecha_finalizacion form-control form-control-sm"  name="fecha_finalizacion" id="fecha_finalizacion">
                        </div>
                      </div>    

                      <div class="col-lg-2">  
                        <div class="form-group">   
                          <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Hora finalizaci&oacute;n</label>
                          <input type="text" autocomplete="off"  maxlength="5"  class="clockpicker hora_text form-control form-control-sm"  data-autoclose="true"  data-align="top"  placeholder="Hora" autocomplete="on"  id="hora_finalizacion"  name="hora_finalizacion">
                        </div>
                      </div>

                      <div class="col-lg-2">  
                        <div class="form-group">
                         <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado</label>
                              <select id="estado" name="estado" class="custom-select custom-select-sm">
                              <option selected value="0">Pendiente supervisor </option>
                              <option value="1" id="apr_sp">Aprobado supervisor </option>
                            </select>
                        </div>
                      </div>  

                      <div class="col-lg-6">  
                        <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Comentarios</label>
                            <input type="text" autocomplete="off" maxlength="200" placeholder="Ingrese Comentarios" class="form-control form-control-sm"  name="comentarios" id="comentarios">
                        </div>
                      </div> 

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
