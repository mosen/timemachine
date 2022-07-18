<div id="timemachine-tab">
    <h2 data-i18n="timemachine.timemachine"></h2>
</div>

<div id="timemachine-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/timemachine/get_tab_data/' + serialNumber, function(data){

        // Check if we have data
        if( data[0]['alias_volume_name'] == null){
            $('#timemachine-msg').text(i18n.t('no_data'));
            $('#timemachine-header').removeClass('hide');

        } else {

            // Hide
            $('#timemachine-msg').text('');
            $('#timemachine-view').removeClass('hide');

            // Detail view
            items = data[0];
            $('.timemachine-last_success').text(function(){
               if(items.last_success && items.last_success == "None"){
                  return i18n.t('timemachine.never')
               } else if (items.last_success) {
                  return moment(items.last_success + 'Z').fromNow();
               }
            });
            $('.timemachine-duration')
               .text(moment.duration(items.duration, "seconds").humanize());
            $('.timemachine-last_failure_msg').text(function() {
               var message = items.last_failure_msg
               if ( ! message.startsWith("Backup failed with error ", 0) && message !== ""){
                  return i18n.t('timemachine.'+message);
               } else if (message.startsWith("Backup failed with error ", 0)) {
                  return message.replace("Backup failed with error ", "Error ");
               }
            });
            $('.timemachine-last_failure').text(function(){
               if(items.last_failure && items.last_failure == "None"){
                  return i18n.t('timemachine.never')
               } else if (items.last_failure) {
                  return moment(items.last_failure + 'Z').fromNow();
               }
            });
            $('.timemachine-location_name').text(items.alias_volume_name);
            $('.timemachine-destinations').text(items.destinations);
            $('.timemachine-result').text(items.result);

            var skipThese = ['id','serial_number','destinations','localized_disk_image_volume_name','alias_volume_name'];
            $.each(data, function(i,d){

                // Generate rows from data
                var rows = ''
                for (var prop in d){
                    // Skip skipThese
                    if(skipThese.indexOf(prop) == -1){
                        if (d[prop] == '' || d[prop] == null){
                           // Do nothing for empty values to blank them
                        } else if(prop.indexOf('bytes') > -1){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+fileSize(d[prop], 2)+'</td></tr>';

                        } else if(prop == "duration"){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td><span title="'+d[prop]+' '+i18n.t('power.seconds')+'">'+moment.duration(+d[prop], "seconds").humanize()+'</span></td></tr>';

                        } else if((prop == "last_success" || prop == "last_failure") && d[prop] == "None"){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>' + i18n.t('timemachine.never') + '</td></tr>';

                        } else if(prop == "last_success" || prop == "last_failure"){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td><span title="' + moment(d[prop]).format('llll') + '">'+moment(d[prop] + 'Z').fromNow()+'</span></td></tr>';

                        } else if((prop == "earliest_snapshot_date" || prop == "latest_snapshot_date") && d[prop] == "None"){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>' + i18n.t('timemachine.never') + '</td></tr>';

                        } else if(prop == "earliest_snapshot_date" || prop == "latest_snapshot_date"){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td><span title="' + moment(d[prop]).fromNow() + '">'+moment(d[prop] + 'Z').format('llll')+'</span></td></tr>';

                        } else if(prop == "consistency_scan_date" || prop == "date_of_latest_warning" || prop == "last_configuration_trace_date"){
                           var date = new Date(d[prop] * 1000);
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td><span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span></td></tr>';

                        } else if(prop == 'result'){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('timemachine.'+d[prop])+'</td></tr>';

                        } else if(prop == 'last_failure_msg' && ! d[prop].startsWith("Backup failed with error ", 0)){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('timemachine.'+d[prop])+'</td></tr>';

                        } else if(d[prop] == 'NotEncrypted'){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('unencrypted')+'</td></tr>';
                        } else if(d[prop] == 'Encrypted'){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('encrypted')+'</td></tr>';

                        } else if(prop == 'always_show_deleted_backups_warning' && d[prop] == 1){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        } else if(prop == 'always_show_deleted_backups_warning' && d[prop] == 0){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        }

                        else if(prop == 'auto_backup' && d[prop] == 1){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        } else if(prop == 'auto_backup' && d[prop] == 0){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        }

                        else if(prop == 'mobile_backups' && d[prop] == 1){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        } else if(prop == 'mobile_backups' && d[prop] == 0){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        }

                        else if(prop == 'skip_system_files' && d[prop] == 1){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        } else if(prop == 'skip_system_files' && d[prop] == 0){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        }

                        else if(prop == 'is_network_destination' && d[prop] == 1){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        } else if(prop == 'is_network_destination' && d[prop] == 0){
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';

                        } else if(prop == "snapshot_dates"){
                           var snapdates = d[prop].split(", ");
                           var outsnaps = "";
                           snapdates.forEach(function(snapdate) {
                               var date = new Date(snapdate * 1000);
                               outsnaps = outsnaps + '<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span><br>'
                           });
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+outsnaps+'</td></tr>';

                         } else if(prop == "apfs_snapshots"){
                           var apfsdates = d[prop].split(", ");
                           var outsnaps = "";
                           apfsdates.forEach(function(snapdate) {
                               if (snapdate != "") {
                                   var date = new Date(snapdate * 1000);
                                   outsnaps = outsnaps + '<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span><br>'
                               }
                           });
                           rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+outsnaps+'</td></tr>';

                        } else {
                            rows = rows + '<tr><th>'+i18n.t('timemachine.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                        }
                    }
                }

                // Fill in table
                $('#timemachine-tab')
                    .append($('<h4>')
                        .append($('<i>')
                            .addClass('fa fa-clock-o'))
                        .append(' '+d.alias_volume_name))
                    .append($('<div>')
                        .addClass('table-responsive')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            })
        }
	});
});
</script>
