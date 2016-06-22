<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="A layout example that shows off a responsive product landing page.">
		<title>RDEV ANALYTICS</title>
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css">
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
		<link rel="stylesheet" href="{{ public_asset('/src/themes/pure-layout-marketing/css/layouts/marketing.css')}}">
		<link rel="stylesheet" href="{{ public_asset('/src/css/app.css')}}">

	</head>
	<body>
		<div class="header" require-js="vk-guest">
		    <div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
		        <a class="pure-menu-heading" href="">Rdev Analytics</a>

		        <ul class="pure-menu-list">
		            @if(Vk::auth())
			            <li class="pure-menu-item"><a href="{{ route('likes') }}" class="pure-menu-link">Мені подобається</a></li>
			            <li class="pure-menu-item"><a href="{{ route('audio') }}" class="pure-menu-link">Жанри аудіо</a></li>
		            	<li class="pure-menu-item"><a href="{{route('logout')}}" class="pure-menu-link">Вийти</a></li>
		            @else
		            	<li class="pure-menu-item"><a href="{{route('login')}}" class="pure-menu-link">Увійти</a></li>
		            @endif
		        </ul>
		    </div>
		</div>

		<div class="splash-container">
		    <div class="splash">
		        <div class="splash-head-with-user">
			        <div id="vk-user-photo">
			        	<img width="100" height="100" src="{{public_asset('src/default-teacher-avatar.png')}}">
			        </div>
		        	<span id="vk-user-text">Привіт, гість</span>
		        </div>
		        <p class="splash-subhead">
		            Do something interesting:)
		        </p>
		        <p>
		        	@if(Vk::auth())
		            	<a href="{{route('likes')}}" class="pure-button pure-button-primary">Розпочати Зараз</a>
		            @else
		            	<a href="{{route('login')}}" class="pure-button pure-button-primary">Розпочати Зараз</a>
		            @endif
		            
		        </p>
		    </div>
		</div>

		<div class="content-wrapper">
		    <div class="content">
		        <h2 class="content-head is-center">Активний учасник соціальної мережі VK.COM ?</h2>

		        <div class="pure-g">
		            <div class="l-box pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">

		                <h3 class="content-subhead">
		                    <i class="fa fa-users"></i>
		                    Дослідіть дані своїх друзів
		                </h3>
		                <p>
		                    Проводьте різноманітні дослідження з даними своєї сторінки та даними сторінок своїх друзів.
		                </p>
		            </div>
		            <div class="l-box pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">
		                <h3 class="content-subhead">
		                    <i class="fa fa-heart"></i>
		                    Порахуйте кількість лайків
		                </h3>
		                <p>
		                    Порахуйте кількість "мені подобається" на своїй сторінці та сторінках друзів.
		                </p>
		            </div>
		            <div class="l-box pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">
		                <h3 class="content-subhead">
		                    <i class="fa fa-music"></i>
		                    Визначайте жанри музики
		                </h3>
		                <p>
		                    Визначте свої найулюбленіші жанри музики, та жанри своїх друзів.
		                </p>
		            </div>
		            <div class="l-box pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">
		                <h3 class="content-subhead">
		                    <i class="fa fa-signal "></i>
		                    Будуйте діаграми
		                </h3>
		                <p>
		                    На основі досліджень будуйте діаграми, та діліться результатами із свохми друзями.
		                </p>
		            </div>
		        </div>
		    </div>

		    <div class="ribbon l-box-lrg pure-g">
		        <div class="l-box-lrg is-center pure-u-1 pure-u-md-1-2 pure-u-lg-2-5">
		            <img class="pure-img-responsive" alt="File Icons" width="300" src="{{public_asset('src/918611187.jpg')}}">
		        </div>
		        <div class="pure-u-1 pure-u-md-1-2 pure-u-lg-3-5">

		            <h2 class="content-head content-head-ribbon">RDEV NANLYTICS</h2>

		            <p>
		                RDEV ANALYTICS - веб ресурс для аналітики даних із соціальної мережі vk.com. Даний ресурс є інструментом для досліджень своєї сторінки та сторінок своїх друзів, визначення їхніх інтересів та вподобань. 
		            </p>
		        </div>
		    </div>

		    <div class="content">
		        <h2 class="content-head is-center">Як зв'язатися з нами</h2>

		        <div class="pure-g">
		            <div class="l-box-lrg pure-u-1 pure-u-md-2-5">
		                <img src="{{public_asset('src/map.jpg')}}" width="500">
		            </div>

		            <div class="l-box-lrg pure-u-1 pure-u-md-3-5">
		            	<h4>Ідеї та пропозиції</h4>
			            <p>
			                Якщо у вас виникли якісь запитання, нові ідеї щодо функціоналу, або ж цікаві пропозиції, будьласка, висловлюйте їх нам.
			            </p>
		                <h4>Наші контакти</h4>
			            <p>
			            
			                <b>Email:</b> <a href="mailto:rom.loboda@gmail.com">rom.loboda@gmail.com</a><br><br>
			                <a href="https://vk.com/lremman"><img src="http://image0.flaticon.com/icons/png/128/3/3886.png" alt="Vkontakte logo" title="Vkontakte logo" width="64" height="64"></a>
			                <a href="https://www.facebook.com/roman.loboda.56"><img src="http://image0.flaticon.com/icons/png/128/23/23747.png" alt="Facebook" title="Facebook" width="64" height="64"></a>
			                <a href="https://plus.google.com/114795936364897607226"><img src="http://image0.flaticon.com/icons/png/128/7/7541.png" alt="Social google plus square button" title="Social google plus square button" width="64" height="64"></a>
			            </p>
		            </div>
		        </div>

		    </div>

		    <div class="footer l-box is-center">
		        RDEV ANALYTICS - 2016
		    </div>

		</div>

		<script src="{{ public_asset('/src/vendor/js/jquery/jquery-2.1.4.min.js')}}" type="text/javascript"></script>
		<script src="{{ public_asset('/src/vendor/js/jquery-ui-1.11.4/jquery-ui.min.js')}}" type="text/javascript"></script>
		<script src="{{ public_asset('/src/themes/material-tile/material-tile.js')}}" type="text/javascript"></script>
		<script src="//vk.com/js/api/openapi.js"></script>
		<script>
			VK.init({
			  apiId: {{ env('VK_APP_ID') }}
			});

			var vkLogUrl = '{{ route('set_guest_status') }}';

			var vkSendTok = '{{csrf_token()}}';
		</script>
		<script src="{{ public_asset('/src/js/vk-guest.js')}}" type="text/javascript"></script>


	</body>

</html>

<a href="{{route('login')}}">Login</a>