<?php $this->view('partials/head'); ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h3><span data-i18n="timemachine.report"></span> <span id="total-count" class='label label-primary'>…</span></h3>
            <table class="table table-striped table-condensed table-bordered">
                <thead>
                    <tr>
                        <th data-i18n="listing.computername" data-colname='machine.computer_name'></th>
                        <th data-i18n="serial" data-colname='reportdata.serial_number'></th>
                        <th data-i18n="username" data-colname='reportdata.long_username'></th>
                        <th data-i18n="backup.last_success" data-colname='timemachine.last_success'></th>
                        <th data-i18n="backup.last_failure" data-colname='timemachine.last_failure'></th>
                        <th data-i18n="backup.last_failure_msg" data-colname='timemachine.last_failure_msg'></th>
                        <th data-i18n="listing.checkin" data-colname='reportdata.timestamp'></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-i18n="listing.loading" colspan="7" class="dataTables_empty"></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- /span 13 -->
    </div> <!-- /row -->
</div>  <!-- /container -->

<script type="text/javascript">

    $(document).on('appUpdate', function(e){

        var oTable = $('.table').DataTable();
        oTable.ajax.reload();
        return;

    });

    $(document).on('appReady', function(e, lang) {

        // Get modifiers from data attribute
        var mySort = [], // Initial sort
            hideThese = [], // Hidden columns
            col = 0, // Column counter
            runtypes = [], // Array for runtype column
            columnDefs = [{ visible: false, targets: hideThese }]; // Column Definitions

        $('.table th').map(function(){

            columnDefs.push({name: $(this).data('colname'), targets: col, render: $.fn.dataTable.render.text()});

            if($(this).data('sort')){
              mySort.push([col, $(this).data('sort')])
            }

            if($(this).data('hide')){
              hideThese.push(col);
            }

            col++
        });

        oTable = $('.table').dataTable( {
            ajax: {
                url: appUrl + '/datatables/data',
                type: "POST",
                data: function(d){
                    d.mrColNotEmpty = "last_success"
                }
            },
            dom: mr.dt.buttonDom,
            buttons: mr.dt.buttons,
            order: mySort,
            columnDefs: columnDefs,
            createdRow: function( nRow, aData, iDataIndex ) {
                // Update name in first column to link
                var name=$('td:eq(0)', nRow).html();
                if(name == ''){name = "No Name"};
                var sn=$('td:eq(1)', nRow).html();
                var link = mr.getClientDetailLink(name, sn, '#tab_timemachine-tab');
                $('td:eq(0)', nRow).html(link);

                // Format last successful
                var date = $('td:eq(3)', nRow).html();
                if(date == "None"){
					$('td:eq(3)', nRow).html('<span class="label label-danger">'+i18n.t('timemachine.never')+'</span');
                } else if (date){
                    $('td:eq(3)', nRow).html('<span title="' + moment(date).format('llll') + '">'+moment(date + 'Z').fromNow()+'</span>');
                }

                // Format last failed date
                var date = $('td:eq(4)', nRow).html();
                if(date){
                    $('td:eq(4)', nRow).html('<span title="' + moment(date).format('llll') + '">'+moment(date + 'Z').fromNow()+'</span>');
                }
                
                // Format last failed message
                var message = $('td:eq(5)', nRow).html();
                if(! message.startsWith("Backup failed with error ", 0) && message !== ""){
                    $('td:eq(5)', nRow).html(function() {return i18n.t('timemachine.'+message)});
                } else if (message.startsWith("Backup failed with error ", 0)) {
                    $('td:eq(5)', nRow).html(message.replace("Backup failed with error ", "Error "));
                }

                // Format Check-In timestamp
                var checkin = parseInt($('td:eq(6)', nRow).html());
                var date = new Date(checkin * 1000);
                $('td:eq(6)', nRow).html('<span title="'+i18n.t('listing.checkin')+" "+moment(date).format('llll')+'">'+moment(date).fromNow()+'</span>');
            }
        } );
        // Use hash as searchquery
        if(window.location.hash.substring(1))
        {
            oTable.fnFilter( decodeURIComponent(window.location.hash.substring(1)) );
        }

    } );
</script>

<?php $this->view('partials/foot')?>
