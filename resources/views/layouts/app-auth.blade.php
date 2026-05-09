<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', 'Login - Surat Desa')</title>

	<!-- Google Font: Inter -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">

	<style>
		body {
			font-family: 'Inter', sans-serif;
			background: #f4f7f6;
		}
	</style>

	@stack('css')
</head>

<body class="hold-transition">

	@yield('content')

	<!-- jQuery -->
	<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<!-- AdminLTE App -->
	<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

	@stack('js')
</body>

</html>