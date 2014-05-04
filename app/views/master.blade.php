<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pluto Technology</title>

<!--Master Layout CSS File-->
{{ HTML::style('css/index.css') }}

<!--Context Menu CSS File-->
{{ HTML::style('jQuery-contextMenu-master/src/jquery.contextMenu.css') }}

<!--Jquery CSS Framework Libarary-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<!--Dialog Box CSS File-->
{{ HTML::style('css/dialog.css') }}

<!--The link for Jquery Library -->

<!--The link for Jquery UI Library-->
<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<!--The link for CKEditor Library -->
{{ HTML::script('ckeditor/ckeditor.js') }}


<!--The Link for context menu e.g when right click the folder option -->
{{ HTML::script('jQuery-contextMenu-master/jquery.touchSwipe.min.js') }}
{{ HTML::script('jQuery-contextMenu-master/screen.js') }}
{{ HTML::script('jQuery-contextMenu-master/src/jquery.ui.position.js') }}
{{ HTML::script('jQuery-contextMenu-master/src/jquery.contextMenu.js') }}



<!--Inerternal Libarary --> 
{{ HTML::script('js/index.js') }}

</head>
<body>
	<div class="container">
    	<div class="header">
			@yield('header')
        </div>
        <div class="sidebar1">
			@yield('sidebar')
        </div>
        <div class="content">
			@yield('content')
        </div>
        <div class="footer">
			@yield('footer')
        </div>
	<!-- end .container --></div>
</body>
</html>