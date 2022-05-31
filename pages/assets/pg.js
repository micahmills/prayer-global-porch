jQuery(document).ready(function($){

  /* video modal */
  $('#video-link-icon').on('click', function(){
    $('#demo_video .modal-body').html('<iframe style="width:100%;max-width:600px;height:300px;" src="https://player.vimeo.com/video/694876675?h=fa791a640e&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" title="Moravian challenge" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>')
    $('#demo_video').modal('show')
  })

})
