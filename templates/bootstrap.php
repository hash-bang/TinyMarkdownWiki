<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?=$title?></title>
	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
	<style>
	h1 {
		margin: 30px 0 15px 0;
	}

	#content {
		margin-top: 20px;
	}

	#affix {
		width: 258px;
		margin: 30px 0 0;
		padding: 0;
		background-color: #fff;
		-webkit-border-radius: 6px;
		-moz-border-radius: 6px;
		border-radius: 6px;
		-webkit-box-shadow: 0 1px 4px rgba(0,0,0,.065);
		-moz-box-shadow: 0 1px 4px rgba(0,0,0,.065);
		box-shadow: 0 1px 4px rgba(0,0,0,.065);
	}

	#affix > li > a {
		display: block;
		width: 190px 9;
		margin: 0 0 -1px;
		padding: 8px 14px;
		border: 1px solid #e5e5e5;
	}

	#affix > li:first-child > a {
		-webkit-border-top-left-radius: 6px;
		-webkit-border-top-right-radius: 6px;
		-moz-border-top-left-radius: 6px;
		-moz-border-top-right-radius: 6px;
		border-top-left-radius: 6px;
		border-top-right-radius: 6px;
	}

	#affix > li:last-child > a {
		-webkit-border-bottom-left-radius: 6px;
		-webkit-border-bottom-right-radius: 6px;
		-moz-border-bottom-left-radius: 6px;
		-moz-border-bottom-right-radius: 6px;
		border-bottom-left-radius: 6px;
		border-bottom-right-radius: 6px;
	}

	#affix > li > a > i {
		float: right;
	}
	</style>
	<script>
	$(function() {
		console.log($('h1'));
		$('h1').each(function() {
			var my = $(this);
			var link = my.text().replace(/[^a-z0-9\-]+/i, '-').toLowerCase();
			my.prepend('<a name="' + link + '"/>');
			$('#affix').append('<li><a href="#' + link + '"><i class="icon-chevron-right"></i>' + $(this).text() + '</a></li>');
		});
	});
	</script>
</head>
<body>
	<div id="content" class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<ul id="affix" class="nav nav-list affix">
				</ul>
			</div>
			<div class="span9">
				<?=$markdown?>	
			</div>
		</div>
	</div>
</body>
</html>
