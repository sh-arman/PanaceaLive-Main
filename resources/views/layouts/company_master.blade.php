<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Panacea | Machine Room</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#00baff">
	<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="{{ asset('css/main.css') }}">
	<link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker3.standalone.min.css') }}">
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/south-street/jquery-ui.css">

	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
	<ul>
        <li><a href="{{ url($company_name.'/dashboard') }}">Dashboard</a></li>
        <li><a href="{{ url($company_name.'/code/generate') }}">Order Codes</a>
        <li><a href="{{ url($company_name.'/order') }}">Print Order Log</a></li>
		<li><a href="{{ url($company_name.'/log') }}">Activity Log</a></li>
		<li><a href="{{ url($company_name.'/logout') }}">Logout</a></li>
    </ul>

</div>
<!-- ./Sidebar -->

<div id="wrapper">
	<div class="wrapper-inside">
		@yield('content')
	</div>
</div>
<!-- Scripts -->
<script src="//code.jquery.com/jquery-latest.min.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>


<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script>
	$('#example1').DataTable();
</script>

<script src="{{ asset('js/main.js?v1.1') }}"></script>
<!-- ./Scripts -->
</body>
</html>
