<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Acme</title>
    <link rel="stylesheet" type="text/css" href="./css/app.css">
  </head>
  <body>
  	@include('inc.navbar')

	<main role="main" class="container">

		@if(Request::is('/'))
			@include('inc.showcase')
		@endif
	  	<div class="row">
	  		<div class="col-md-8 col-lg-8">
	  			@include('inc.messages')
	  			@yield('content')		
	  		</div>
	  		<div class="col-md-4 col-lg-4">
	  			@include('inc.sidebar')
	  		</div>
	  	</div>

	  	{{--
	  <div class="starter-template">
	    <h1>Bootstrap starter template</h1>
	    <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
	  </div>
	  --}}

	</main><!-- /.container -->

	<div class="container">

	</div>

	<footer id="footer" class="text-center">
		<p>Copyright 2019 &copy; Acme</p>
	</footer>

  </body>
</html>
