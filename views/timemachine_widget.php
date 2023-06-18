<div class="col-lg-4 col-md-6">
    <div class="card" id="time-machine-status-widget">
            <div class="card-header" data-container="body" title="">
                <i class="fa fa-clock-o"></i>
                <span data-i18n="timemachine.timemachine"></span>
                <a href="/show/listing/timemachine/timemachine" class="pull-right text-reset"><i class="fa fa-list"></i></a>
            </div>
        <div class="card-body text-center"></div>
    </div><!-- /card -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/timemachine/get_stats', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var card = $('#time-machine-status-widget div.card-body'),
			baseUrl = appUrl + '/show/listing/timemachine/timemachine';
		card.empty();
		
		// Set statuses
		if(data.week_plus){
			card.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+data.week_plus+'</span><br>'+i18n.t('timemachine.week_plus')+'</a>');
		}
		if(data.lastweek){
			card.append(' <a href="'+baseUrl+'" class="btn btn-warning"><span class="bigger-150">'+data.lastweek+'</span><br>'+i18n.t('timemachine.lastweek')+'</a>');
		}
		if(data.today){
			card.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.today+'</span><br>'+i18n.t('timemachine.today')+'</a>');
		}
        
        $('#time-machine-status-widget .counter').html(data.total);
    });
});
</script>
