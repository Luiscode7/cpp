<style type="text/css">
  @media (min-width: 768px){
      div.dataTables_wrapper div.dataTables_paginate {
        margin-top:10px;
      }
  }

    @media (max-width: 768px){
      div.dataTables_wrapper div.dataTables_paginate {
        text-align: center;
        margin: 10px auto!important;
    }
  }
  .custom-select-sm {
      padding-bottom: .175rem!important;
      font-size: .875rem!important;
  }
  @media (min-width: 768px){

    .select2-results__option {
      padding: 1px!important; 
      font-size: 14px; 
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      font-size: .875rem!important;
    }


  }

</style>
<script type="text/javascript">
  $(function(){
      var perfil="<?php echo $this->session->userdata("perfil"); ?>";

      $(".nav-tabs-int").addClass('disabled');
      $("#menu_detalle_diario a").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Detalle de actividades diarias');
      $(".contenedor_produccion").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");

      $("#menu_detalle_diario").addClass('menuActivo');    
      $("#menu_vista_mensual").removeClass('menuActivo');  
      $("#menu_mantenedor_actividades").removeClass('menuActivo');  
      $("#menu_usuarios").removeClass('menuActivo');  

      $.get("getCPPView", function( data ) {
        $(".contenedor_produccion").html(data);    
        $(".nav-tabs-int").removeClass('disabled');
        $("#menu_detalle_diario a").html('<i class="fa fa-list-ul"></i> Detalle de actividades diarias');
      });
      

      $(document).off('click', '#menu_detalle_diario').on('click', '#menu_detalle_diario',function(event) {
        event.preventDefault();
        $(".nav-tabs-int").addClass('disabled');
        $("#menu_detalle_diario a").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Detalle de actividades diarias');
        $(".contenedor_produccion").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");
        
        $("#menu_detalle_diario").addClass('menuActivo');    
        $("#menu_vista_mensual").removeClass('menuActivo');  
        $("#menu_mantenedor_actividades").removeClass('menuActivo');  
        $("#menu_usuarios").removeClass('menuActivo');  
        
        $.get("getCPPView", function( data ) {
          $(".contenedor_produccion").html(data);    
          $(".nav-tabs-int").removeClass('disabled');
          $("#menu_detalle_diario a").html('<i class="fa fa-list-ul"></i> Detalle de actividades diarias');
        });

       });

      $(document).off('click', '#menu_vista_mensual').on('click', '#menu_vista_mensual',function(event) {
        event.preventDefault();
        $(".nav-tabs-int").addClass('disabled');
        $("#menu_vista_mensual a").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Vista mensual');
        $(".contenedor_produccion").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");

        $("#menu_detalle_diario").removeClass('menuActivo');    
        $("#menu_vista_mensual").addClass('menuActivo');  
        $("#menu_mantenedor_actividades").removeClass('menuActivo');  
        $("#menu_usuarios").removeClass('menuActivo');  
        
        $.get("getVistaMensualView", function( data ) {
          $(".contenedor_produccion").html(data);    
          $(".nav-tabs-int").removeClass('disabled');
          $("#menu_vista_mensual a").html('<i class="fa fa-list-ul"></i> Vista mensual');
        });

       });

      $(document).off('click', '#menu_mantenedor_actividades').on('click', '#menu_mantenedor_actividades',function(event) {
        event.preventDefault();
        $(".nav-tabs-int").addClass('disabled');
        $("#menu_mantenedor_actividades a").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Mantenedor actividades');
        $(".contenedor_produccion").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");
        
        $("#menu_detalle_diario").removeClass('menuActivo');    
        $("#menu_vista_mensual").removeClass('menuActivo');  
        $("#menu_mantenedor_actividades").addClass('menuActivo');  
        $("#menu_usuarios").removeClass('menuActivo');  
        
        $.get("getMantActView", function( data ) {
          $(".contenedor_produccion").html(data);    
          $(".nav-tabs-int").removeClass('disabled');
          $("#menu_mantenedor_actividades a").html('<i class="fa fa-list-ul"></i> Mantenedor actividades');
        });

       });

      $(document).off('click', '#menu_usuarios').on('click', '#menu_usuarios',function(event) {
        event.preventDefault();
        $(".nav-tabs-int").addClass('disabled');
        $("#menu_usuarios a").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Mantenedor Usuarios');
        $(".contenedor_produccion").html("<center><img src='<?php echo base_url()?>assets/imagenes/loader2.gif' class='loader'></center>");
        
        $("#menu_detalle_diario").removeClass('menuActivo');    
        $("#menu_vista_mensual").removeClass('menuActivo');  
        $("#menu_mantenedor_actividades").removeClass('menuActivo');  
        $("#menu_usuarios").addClass('menuActivo');  
        
        $.get("getMantUsView", function( data ) {
          $(".contenedor_produccion").html(data);    
          $(".nav-tabs-int").removeClass('disabled');
          $("#menu_usuarios a").html('<i class="fa fa-user"></i> Mantenedor Usuarios');
        });

       });


  })
 </script>

<!-- MENU -->
  <div class="row menu_superior">
    <div class="col-12"> 
       <ul class="nav nav-tabs navbar-left nav-tabs-int">
        <li id="menu_detalle_diario" class="active"><a><i class="fa fa-list-ul"></i> Detalle de actividades diarias</a></li>   
        <!-- <li id="menu_vista_mensual" class="active"><a><i class="fa fa-list-ul"></i> Vista mensual</a></li>   
        <li id="menu_mantenedor_actividades" class="active"><a><i class="fa fa-list-ul"></i> Mantenedor actividades</a></li> -->   
        <li id="menu_usuarios" class="active"><a><i class="fa fa-user"></i> Mantenedor Usuarios</a></li>   
      </ul>  
    </div> 
  </div>

  <!-- CONTENEDOR MATERIALES -->

 <div class="row">
   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
     <div class="contenedor_produccion"></div>
   </div>
 </div>

