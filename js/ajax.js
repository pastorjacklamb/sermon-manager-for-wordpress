jQuery(document).ready(function(){
  jQuery('#sermon-navigation a').live('click', function(e){
    e.preventDefault();
    var link = jQuery(this).attr('href');
    jQuery('#wpfc_sermon').fadeOut(500).load(link + ' #wpfc_loading', function(){ jQuery('#wpfc_sermon').fadeIn(500); });
  });

});
  