<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>首页</title>
	<script src='/bootstrap/js/bootstrap.min.js'></script>
	<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
</head>
<body>
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <a class="navbar-brand" href="#">SixGuys
	      </a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav navbar-right">
	        <li><a href="{{ url('/') }}">Sig in</a></li>
	        <li><a href="{{ url('/') }}">Sig up</a></li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid --> 
	</nav>
	<div class="page-header text-center">
  		<h1>WELCOME</h1>
	</div>
	<form class="form-inline text-center">
	  <div class="form-group">
	    <label for="exampleInputName2">Name</label>
	    <input type="text" class="form-control" id="exampleInputName2" placeholder="Six guy">
	  </div>
	  <div class="form-group">
	    <label for="exampleInputEmail2">Pass</label>
	    <input type="email" class="form-control" id="exampleInputEmail2" placeholder="SixGuys@example.com">
	  </div>
	  <button type="button" class="btn btn-default" onclick="msg()">Login</button>
	</form>
</body>
<script>
	function msg()
	{
	    alert('功能还没有写好 看好你哟！')
	}
</script>
</html>