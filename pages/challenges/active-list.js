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
        html_content += `<tr>
                          <td>${v.start_time}</td>
                          <th>${v.post_title}</th>
                          <td>${v.stats.participants}</td>
                          <td>${v.stats.completed}</td>
                          <td>${v.stats.remaining}</td>
                          <td>${v.stats.time_elapsed_small}</td>
                          <td style="text-align:right;">
                            <a href="/prayer_app/custom/${v.lap_key}">Pray</a> |
                            <a href="/prayer_app/custom/${v.lap_key}/map">Map</a> |
                            <a href="/prayer_app/custom/${v.lap_key}/tools">Sharing</a> |
                            <a href="/prayer_app/custom/${v.lap_key}/display">Display</a>
                          </td>
                        </tr>`
      })

      jQuery('#content').html(
            `<table class="display responsive" style="width:100%;" id="list-table" data-order='[[ 0, "desc" ]]'>
                    <thead>
                        <th></th>
                        <th>Name</th>
                        <th class="desktop">Warriors</th>
                        <th class="desktop">Covered</th>
                        <th class="desktop">Remaining</th>
                        <th class="desktop">Pace</th>
                        <th class="desktop">Links</th>
                      </thead>
                    <tbody>
                       ${html_content}
                    </tbody>
                    </table>`
      )

      jQuery('#list-table').DataTable({
        lengthChange: false,
        pageLength: 30,
        responsive: true,
        order: [[0, 'desc']],
        columnDefs: [
          {
            target: 0,
            visible: false,
          }
        ],
      });


    })
}) // end .ready
