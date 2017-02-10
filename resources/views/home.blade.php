<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Home</title>
	<link rel="stylesheet" href="{{ url('/css/home.css') }}">
</head>
<body>
	<div class="container">
		<nav class="nav has-shadow">
			<div class="nav-left">
				<div class="nav-item">
					<span class="icon"><i class="fa fa-home"></i></span>
					<span>ほーむ</span>
				</div>
			</div>
		</nav>
		<div>&nbsp;</div>
		<div class="tile is-ancestor">
			<div class="tile is-parent is-3 is-vertical">
				<div class="tile is-child box">
					<p class="title">One</p>
					<p>...</p>
				</div>
				<div class="tile is-child box">
					<p class="title">Two</p>
					<p>...</p>
				</div>
			</div>
			<div class="tile is-parent">
				<div class="tile is-child box">
					<p class="title">Three</p>
					<p>...</p>

					<div id="app"></div>
					
				</div>
			</div>
		</div>
	</div>
	<script src="{{ url('/js/home.js') }}"></script>
</body>
</html>