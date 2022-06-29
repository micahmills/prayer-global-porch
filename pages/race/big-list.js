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

      let end_time

      content.empty()
      content.append(`<div class="row">
                <div class="col-sm-2">
                    <strong>Lap Number</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Completed Date</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Warriors</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Minutes Prayed</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Pace</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Map</strong>
                </div>
            </div>`)
      jQuery.each( data, function(i,v){
        end_time = v.stats.end_time_formatted
        if( ! v.end_time ) {
          end_time = 'running'
        }
        content.append(
          `<div class="w-100"><hr></div>
            <div class="row">
                <div class="col-sm-2">
                    Lap #${v.lap_number}
                </div>
                <div class="col-sm-2">
                    ${ end_time }
                </div>
                <div class="col-sm-2">
                    ${v.stats.participants}
                </div>
                <div class="col-sm-2">
                    ${v.stats.minutes_prayed}
                </div>
                <div class="col-sm-2">
                    ${v.stats.time_elapsed_small}
                </div>
                <div class="col-sm-2">
                    <a href="/prayer_app/global/${v.lap_key}/map">Map</a>
                </div>
            </div>`
        )
      })

      content.append(
        `<div class="w-100"><hr style="border-top:1px solid darkgrey;border-bottom:1px solid darkgrey;"></div>
            <div class="row">
                <div class="col-sm-2">
                    Total
                </div>
                <div class="col-sm-2">

                </div>
                <div class="col-sm-2">
                    ${jsObject.global_race.participants} <span style="vertical-align:super;font-size:.6em;">*</span>
                </div>
                <div class="col-sm-2">
                    ${jsObject.global_race.minutes_prayed}
                </div>
                <div class="col-sm-2">
                    ${jsObject.global_race.time_elapsed_small}
                </div>
                <div class="col-sm-2">
                    <a href="/race_app/big_map/">Big Map</a>
                </div>
            </div>`
      )

      content.append(
        `<div class="w-100"><br><br></div>
            <div class="row">
                <div class="col" style="font-size:.6em;">
                    * unique warriors over all laps.
                </div>
            </div>`
      )


    })
}) // end .ready
