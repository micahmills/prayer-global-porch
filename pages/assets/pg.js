jQuery(document).ready(function($){

  /* video modal */
  $('#video-link-icon').on('click', function(){
    $('#demo_video .modal-body').html('<iframe style="width:100%;max-width:600px;height:300px;" src="https://www.youtube.com/embed/xIZbADbgkwA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>')
    $('#demo_video').modal('show')
  })

})
