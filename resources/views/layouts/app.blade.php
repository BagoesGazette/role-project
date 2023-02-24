<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title>{{ config('app.name') }} - Mazer Admin Dashboard</title>
	<link href="{{ asset('assets/css/main/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/main/app-dark.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/images/logo/favicon.svg') }}" rel="shortcut icon" type="image/x-icon">
	<link href="{{ asset('assets/images/logo/favicon.png') }}" rel="shortcut icon" type="image/png">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('plugin-css')
    @stack('custom-css')
</head>
<body>
	<div id="app">
		@include('layouts.sidebar')
		<div id="main">
			<header class="mb-3">
				<a class="burger-btn d-block d-xl-none" href="#"><i class="bi bi-justify fs-3"></i></a>
			</header>
			@yield('content')
        
			@include('layouts.footer')
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.3.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="{{ asset('assets/js/bootstrap.js') }}"></script> 
	<script src="{{ asset('assets/js/app.js') }}"></script>
	@if (Session::has('success'))
	<script>
	   Swal.fire(
		  '{{ Session::get("success") }}',
		  'You clicked the button!',
		  'success'
	   )
		</script>
	@endif

	@if (Session::has('error'))
		<script>
		Swal.fire(
			'{{ Session::get("error") }}',
			'You clicked the button!',
			'error'
		)
		</script>
	@endif
    @stack('plugin-js')
    @stack('custom-js')

</body>
</html>