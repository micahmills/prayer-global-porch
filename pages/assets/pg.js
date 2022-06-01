jQuery(document).ready(function($){

  /* video modal */
  $('#video-link-icon').on('click', function(){
    let body = $('#demo_video .modal-body')
    let modal = $('#demo_video')
    body.html('<iframe style="width:100%;max-width:600px;height:300px;" src="https://player.vimeo.com/video/715752828?h=d39d43cea8&amp;badge=0&amp;autoplay=1&amp;loop=1&amp;autopause=0&amp;player_id=0&amp;app_id=58479" title="Moravian challenge" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>')

    modal.modal('show')
    modal.on('hide.bs.modal', function () {
        body.empty()
      })
  })

})
