<!DOCTYPE html>
<html>
	<head>
		<title>Movie Recommendations</title>

		<!-- Bootstrap -->
		<link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    	<!--[if lt IE 9]>
      		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
	<!-- Scripts -->
    <script src="/js/app.js"></script>
		<div class="container">
			<div class="row">
				<div class="navbar-header">
					<h2 class="navbar-brand">Movie Recommendations</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					@yield('content')
				</div>
			</div>
			<div class="row">
				@if(Session::has('msg'))
					<div class="alert">{{ Session::get('msg') }}</div>
				@endif
			</div>
		</div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    	<!-- Include all compiled plugins (below), or include individual files as needed -->
    	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	</body>
</html>