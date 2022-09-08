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

  console.log(jsObject)

  let content = jQuery('#content')

  window.api_post( 'get_global_list', {} )
    .done(function(data) {
      console.log(data)

      content.empty()
      content.append(`<div class="row" style="background-color: lightgrey; padding:1em;margin-bottom:1em;">
                <div class="col-sm-4">
                    <strong>Challenge Name</strong>
                </div>
                <div class="col-sm-1">
                    <strong>Warriors</strong>
                </div>
                <div class="col-sm-1">
                    <strong>Prayed</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Needed</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Pace</strong>
                </div>
                <div class="col-sm-2">
                    <strong></strong>
                </div>
            </div>`)
      jQuery.each( data, function(i,v){
        content.append(
          `<div class="row challenge-full-row" data-value="/prayer_app/custom/${v.lap_key}/map">
                <div class="col-sm-4 challenge-row">
                    ${v.post_title}
                </div>
                <div class="col-sm-1 challenge-row">
                    ${v.stats.participants}
                </div>
                <div class="col-sm-1 challenge-row">
                    ${v.stats.completed}
                </div>
                <div class="col-sm-2 challenge-row">
                    ${v.stats.remaining}
                </div>
                <div class="col-sm-2 challenge-row">
                    ${v.stats.time_elapsed_small}
                </div>
                <div class="col-sm-2">
                    <a href="/prayer_app/custom/${v.lap_key}/map">View</a> |
                    <a href="/prayer_app/custom/${v.lap_key}/tools">Tools</a>
                </div>
            </div>
            <div class="w-100"><hr></div>
            `
        )
      })

      content.append(
        `<div class="w-100"><br><br></div>
            <div class="row">
                <div class="col" style="font-size:.6em;">
                </div>
            </div>`
      )

      jQuery('.challenge-full-row').on('click', function(i,v){
        let url = jQuery(this).data('value')
        window.location.href = jsObject.site_url + url
      })


    })
}) // end .ready
