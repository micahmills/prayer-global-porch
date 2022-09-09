jQuery(document).ready(function() {
  /**
   * API HANDLERS
   */
  window.api_post = (action, data) => {
    return jQuery.ajax({
      type: "POST",
      data: JSON.stringify({action: action, parts: jsObject.parts, data: data}),
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      url: jsObject.root + jsObject.parts.root + '/v1/' + jsObject.parts.type,
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-WP-Nonce', jsObject.nonce)
      }
    })
      .fail(function (e) {
        console.log(e)
      })
  }

  window.api_post( 'get_global_list', {} )
    .done(function(data) {
      console.log(data)

      let html_content = ''
      jQuery.each( data, function(i,v){
        html_content += `<tr data-value="/prayer_app/custom/${v.lap_key}/map">
                          <th>${v.post_title}</th>
                          <td style="text-align:right;">
                            <a href="/prayer_app/custom/${v.lap_key}">Pray</a> |
                            <a href="/prayer_app/custom/${v.lap_key}/map">Map</a> |
                            <a href="/prayer_app/custom/${v.lap_key}/tools">Share</a>
                          </td>

                        </tr>`
      })

      jQuery('#content').html( `<table class="table table-hover">

                    <tbody>
                       ${html_content}
                    </tbody>
                    </table>` )

      jQuery('.challenge-row').on('click', function(i,v){
        let url = jQuery(this).data('value')
        window.location.href = jsObject.site_url + url
      })


    })
}) // end .ready
