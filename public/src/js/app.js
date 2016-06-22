(function ($, undefined) {

	$('#rating-pagination').hide();

	$('#build-rating').closest('form').on('submit', function (e) {
		e.preventDefault();
		SendForm($(this), 'GET', function ( data ){

				$('#rating-list').html(data.html);

				DrawGraph($('#rating-graph'), data.graph);
				
				if(parseInt($('[name=pages_all]').val()) != 1) {
					$('#rating-pagination').show();
   				}

   				$('#rating-graph').show();

			},
			function () {
				StatusLoadHtml($('#rating-list'));
				$('#rating-pagination').hide();
				$('#rating-graph').hide();
			}
		);
	});

	var ChartIsLoaded = false;

	var SendForm = function ($form, method, callback, beforeSend) {
		$.ajax({
			type: method,
			url: $form.attr('action'),
			data: $form.serialize(),
			dataType: 'json',
			beforeSend: function( xhr ) {
				console.log('before');
			    beforeSend();
			  },
			complete: function( xhr ){
				console.log('complete');
			},
			success: function(data){
				console.log('success');
				callback(data);
			}
		});

	};

	var StatusLoadHtml = function ($parent) {
		var statusCode = 
			'<br><br><br><br>' + 
			'<h4>завантаження...</h4><br>'+
			'<div class="cssload-loader-walk">' +
				'<div></div><div></div><div></div><div></div><div></div>' +
			'</div>';

		$parent.html(statusCode);
	};

   var DrawGraph = function ($parent, data) {

   		var coords = [['Move', 'Мені подобається', { role: 'style' }]];

   		$.each(data, function (key, value) {
   			$.each(value, function (name, rating) {
   				coords.push([name, rating, 'color: #e5e4e2']);
   			});
   		});

      	var drawStuff = function() {

		    var data = new google.visualization.arrayToDataTable(coords);

		    var options = {
		      title: 'Оцінка записів профіля',
		      width: $('rating-graph').closest('div').width(),
		      height:500,
		      legend: { position: 'none' },
		      chart: { subtitle: '' },
		      axes: {
		        x: {
		          0: { side: 'top', label: 'Рейтинг по оцінках записів'} // Top x-axis.
		        }
		      },
		      bar: { groupWidth: "100%" }
		    };

		    var chart = new google.charts.Bar($parent[0]);


		    chart.draw(data, google.charts.Bar.convertOptions(options));
		}
		if(!ChartIsLoaded) {
			google.charts.load('current', {'packages':['bar']});
			ChartIsLoaded = true;
		}

      	google.charts.setOnLoadCallback(drawStuff);

   };

    $('.paginate-prev').on('click', function (e) {

    	e.preventDefault();

    	$('.paginate-next').closest('li').removeClass('disabled');

    	var $li = $(this).closest('li');

    	if(!($li.hasClass('disabled') || $li.hasClass('active'))){
    		$('[name=page]').val(parseInt($('[name=page]').val())-1);

    		if($('[name=page]').val() == 1) {
    			$li.addClass('disabled');
    		}

    		$('.js-paginate-active').html(parseInt($('[name=page]').val()));

    		$('#build-rating').closest('form').submit();
    	}
    });

    $('.paginate-next').on('click', function (e) {

    	e.preventDefault();
    	
    	$('.paginate-prev').closest('li').removeClass('disabled');

   		var $li = $(this).closest('li');

    	var pages_all = $('[name=pages_all]').val();

    	if(!($li.hasClass('disabled') || $li.hasClass('active'))){
    		$('[name=page]').val(parseInt($('[name=page]').val())+1);

    		if($('[name=page]').val() == pages_all) {
    			$li.addClass('disabled');
    		}

    		$('.js-paginate-active').html(parseInt($('[name=page]').val()));

    		$('#build-rating').closest('form').submit();
    	}
    });

})(jQuery);