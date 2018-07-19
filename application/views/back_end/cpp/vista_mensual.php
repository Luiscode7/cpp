<style type="text/css">
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
    var id_perfil_CPP="<?php echo $this->session->userdata("id_perfil_CPP"); ?>";
    var idUsuarioCPP="<?php echo $this->session->userdata("idUsuarioCPP"); ?>";
    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desdef").val(desde);
   /* $("#hastaf").val(hasta);  */ 
    iniciaEjecutor();

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
                $("#select_usuario_vm").select2({
                   allowClear: true,
                   placeholder: 'Seleccione usuario | Todos',
                   data: response
            });
        });

        setTimeout( function () {
         /*$('#select_usuario_vm').val(idUsuarioCPP).trigger('change'); */
        }, 2000 );  
      }

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


      /****GRILLA****/
       var table_vm = $('#tabla_vm').DataTable({
        "iDisplayLength":50, 
        "aaSorting" : [[2,'desc']],
        "scrollY": 420,
        "scrollX": true,
        "select": true,
        "ajax": {
            "url":"<?php echo base_url();?>listaMes",
            "dataSrc": function (json) {
                $(".btn_filtra_vm").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
                $(".btn_filtra_vm").prop("disabled" , false);
                desde = $("#desdef").val();
                /*hasta = $("#hastaf").val();*/
                return json;
            },       
            data: function(param){
                param.desde = $("#desdef").val();
                /* param.hasta = $("#hastaf").val();*/
                if(id_perfil_CPP==1 || id_perfil_CPP==2){//ADMIN,GERENTES
                  accion = 1;//TODO
                }
                if(id_perfil_CPP==3){//SUPERVISOR
                  accion = 2;//SU PERSONAL
                }
                if(id_perfil_CPP==4){//EJECUTOR
                  accion = 3;//SUS REGISTROS
                }
                param.accion = accion;
                param.usuario = $("#select_usuario_vm").val();
            }
         },    
         "columns": [
            { "data": "ejecutor" ,"class":"margen-td"},
            { "data": "dia" ,"class":"margen-td"},
            { "data": "fecha_termino" ,"class":"margen-td"},
            { "data": "id" ,"class":"margen-td"},
            { "data": "proyecto_empresa" ,"class":"margen-td"},
            { "data": "actividad" ,"class":"margen-td"},
            { "data": "unidad" ,"class":"margen-td"},    
            { "data": "cantidad" ,"class":"margen-td"},    
            { "data": "proyecto_desc" ,"class":"margen-td"},

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

            { "data": "estado_str" ,"class":"margen-td"},
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
            { "data": "fecha_aprob" ,"class":"margen-td"},
            {
             "class":"","data": function(row,type,val,meta){
                return row.hora_aprob.substring(0, 5);
              }
            },
            { "data": "fecha_dig" ,"class":"margen-td"},
            { "data": "ultima_actualizacion" ,"class":"margen-td"}
         ]
        }); 

        setTimeout( function () {
          var table_vm = $.fn.dataTable.fnTables(true);
          if ( table_vm.length > 0 ) {
              $(table_vm).dataTable().fnAdjustColumnSizing();
        }}, 1000 ); 


        String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

        $(document).on('keyup paste', '#buscador_vm', function() {
          table_vm.search($(this).val().trim()).draw();
        });


        $(document).off('click', '.btn_filtra_vm').on('click', '.btn_filtra_vm',function(event) {
          event.preventDefault();

          var us = $("#select_usuario_vm").val();
          /* if(us==""){
             $.notify("Debe seleccionar un usuario", {
                 className:'error',
                 globalPosition: 'top right'
             });  
             return false;
          }
          */
           $(this).prop("disabled" , true);
           $(".btn_filtra_vm").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
           table_vm.ajax.reload();
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

        $(document).off('click', '.btn_excel_vm').on('click', '.btn_excel_vm',function(event) {
           event.preventDefault();
            var fecha = $("#desdef").val();
            var hasta = $("#hastaf").val();
            var us = $("#select_usuario_vm").val();
            
            if(us==""){
              us="-";
            }else{
              us=us;
            }

            if(fecha==""){
             $.notify("Debe seleccionar una fecha de inicio.", {
                 className:'error',
                 globalPosition: 'top right'
             });  
             return false;
            }


            if(id_perfil_CPP==1 || id_perfil_CPP==2){//ADMIN,GERENTES
              accion = 1;//TODO
            }

            if(id_perfil_CPP==3){//SUPERVISOR
              accion = 2;//SU PERSONAL
            }

            if(id_perfil_CPP==4){//EJECUTOR
              accion = 3;//SUS REGISTROS
            }

           window.location="excelMensual/"+fecha+"/"+us+"/"+accion;

        });


        $(".fecha_mes").datetimepicker({
              format: "YYYY-MM",
              locale:"es",
              maxDate:"now"
        });

  })
</script>
<!--FILTROS-->

  <div class="form-row">
    
     <div class="col-lg-3">  
      <div class="form-group">
          <select id="select_usuario_vm" name="ejecutor" class="custom-select custom-select-sm"  style="width:100%!important;">
          <option value="">Seleccione usuario | Todos</option>
          </select>
      </div>
    </div> 

    <div class="col-lg-2">
      <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i></span>
        </div>
          <input type="text" autocomplete="off" placeholder="Desde" class="fecha_mes form-control form-control-sm"  name="desdef" id="desdef">
          <!-- <input type="text" autocomplete="off" placeholder="Hasta" class="fecha_mes form-control form-control-sm"  name="hastaf" id="hastaf"> -->
      </div>
    </div>
    </div>

    <div class="col-lg-5">
       <div class="form-group">
        <input type="text" placeholder="Ingrese su busqueda..." id="buscador_vm" class="buscador_vm form-control form-control-sm">
       </div>
    </div>


    <div class="col-6 col-lg-1">
       <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_filtra_vm">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
    </div>

    <div class="col-6 col-lg-1">  
         <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_excel_vm">
         <i class="fa fa-save"></i> Excel 
         </button>
         </div>
    </div>


  </div>

  <!-- GRILLA -->

  <div class="row">
    <div class="col">
    <table id="tabla_vm" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
      <thead>
        <tr>
          <th>Ejecutor</th>
          <th>D&iacute;a</th>
          <th>Fecha</th>
          <th>ID CPP</th>  
          <th>Proyecto Empresa</th>  
          <th>Actividad</th>  
          <th>Unidad</th>  
          <th>Cantidad</th>  
          <th>Proyecto ID/Descripci&oacute;n</th>  
          <th>Comentario</th>  
          <th>Estado</th>  
          <th>Fecha Inicio</th>  
          <th>Hr. Inicio</th>  
          <th>Fecha fin.</th>  
          <th>Hr. fin.</th>  
          <th>Fecha Aprobaci&oacute;n</th>  
          <th>Hr. Digitaci&oacute;n</th>  
          <th>Fecha Digitaci&oacute;n</th>  
          <th>&Uacute;ltima actualizaci&oacute;n</th>  
        </tr>
      </thead>

    </table>
    </div>
  </div>
