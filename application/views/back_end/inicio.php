<style type="text/css">
  @media (min-width: 768px){
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size: 13px!important;
    }
  }
</style>
<script type="text/javascript" charset="utf-8"> 
  $(function(){ 
    $(".navbar").show();
    $.fn.modal.Constructor.prototype.enforceFocus = function() {}; 
    var base="<?php echo base_url()?>";
    var perfil="<?php echo $this->session->userdata("perfil"); ?>";

      $("#menu_cpp").addClass('disabled');
      $(".contenedor_principal").html("<center><img src='<?php echo base_url()?>assets3/imagenes/loader2.gif' class='loader'></center>");

      $("#menu_cpp").addClass('menuActivoh');     
      $("#menu_vista_mensual").removeClass('menuActivoh');     
      $("#menu_mant_act").removeClass('menuActivoh');     
      $("#menu_usuarios").removeClass('menuActivoh');     

      $.get("getCPPInicio", function( data ) {
        $(".contenedor_principal").html(data);    
        $("#menu_cpp").removeClass('disabled');
      });

   
    $(document).off('click', '#menu_cpp').on('click', '#menu_cpp',function(event) {
      event.preventDefault();
      $("#menu_cpp").addClass('disabled');
      $(".contenedor_principal").html("<center><img src='<?php echo base_url()?>assets3/imagenes/loader2.gif' class='loader'></center>");

      $("#menu_cpp").addClass('menuActivoh');     
      $("#menu_vista_mensual").removeClass('menuActivoh');     
      $("#menu_mant_act").removeClass('menuActivoh');     
      $("#menu_usuarios").removeClass('menuActivoh');     

      $.get("getCPPInicio", function( data ) {
        $(".contenedor_principal").html(data);    
        $("#menu_cpp").removeClass('disabled');
      });
    });


  });
</script>
<div class="contenido">
<div class="container-fluid">
<section>
<article class="content">

<!-- CONTENEDOR PRINCIPAL -->
 <div class="row">
   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
     <div class="contenedor_principal"></div>
   </div>
 </div>

</article>  
</section>
</div>
</div>
