<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?=$title?></title>
	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
	<style>
	h1 {
		margin: 30px 0 15px 0;
		border-bottom: 1px solid #ddd;
		font-size: 35px;
		padding-bottom: 12px;
	}

	h2 {
		font-size: 25px;
		border-bottom: 1px solid #CCC;
	}

	#content {
		margin-top: 20px;
	}

	#content .span9 {
		margin-left: 18%;
	}

	#content .span9 a[name] {
		display: block;
		position: relative;
		top: -45px;
	}


	#navbar .navbar-inner {
		padding-left: 20px;
	}

	#affix {
		width: 16%;
		margin-top: 60px;
		padding: 0px;
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
		var path = location.pathname.split('/'); // Figure out current page
		path = path[path.length-1];

		// Fix plain UL elements in navbars to draw correctly in Bootstrap
		$('#navbar > .navbar-inner > ul')
			.addClass('nav')
			.children('li')
				.after('<li class="divider-vertical"></li>') // Add vertical spacer after each LI in navbar
				.each(function() {
					if ($(this).children('ul').length) { // Has children - transform into dropdown
						if (!$(this).children('a').length) { // No 'a' inner on dropdown list probbaly <ul><li>Item<ul><li>Sub-item 1...</li></ul> format
							var ul = $(this).children('ul');
							$(this).children('ul').remove();
							$(this)
								.html('<a href="#" class="dropdown-toggle" data-toggle="dropdown">' + this.outerText + '</a>')
								.append(ul);
						}

						$(this)
							.addClass('dropdown')
							.children('ul')
								.addClass('dropdown-menu');
					}

					var href = $(this).children('a').attr('href');
					if (href) {
						if (href.substr(0, 1) == '/') // Stip leading '/' if present
							href = href.substr(1);
						if (href == path) // Is this the active path?
							$(this).addClass('active');
					}
				});




		// Transform all flat content tables into the correct Bootstrap classes
		$('#content table').addClass('table table-bordered table-stripped');

		// Put each H1 tag in the left hand Affix navigator
		$('h1').each(function() {
			var my = $(this);
			var link = my.text().replace(/[^a-z0-9\-]+/gi, '-').toLowerCase();
			my.prepend('<a name="' + link + '"/>');
			$('#affix').append('<li><a href="#' + link + '"><i class="icon-chevron-right"></i>' + $(this).text() + '</a></li>');
		});


		$(document).on('scroll', function() {
			var docScroll = Math.ceil($('body')[0].scrollTop);
			$('#affix > li').removeClass('active');
			$('#content a[name]').each(function() {
				if (docScroll < Math.ceil($(this).closest('h1').offset().top)) {
					$('#affix > li > a[href="#' + $(this).attr('name') + '"]').closest('li').addClass('active');
					return false;
				}
			});
		}).trigger('scroll');
	});
	</script>
</head>
<body>
	<div id="navbar" class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<?=$menu?>
		</div>
	</div>
	<div id="content" class="container-fluid">
		<div class="row-fluid">
			<div class="span3 hidden-phone">
				<ul id="affix" class="nav nav-list affix"></ul>
			</div>
			<div class="span9">
				<?=$markdown?>	
			</div>
		</div>
	</div>
</body>
</html>
