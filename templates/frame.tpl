<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>VPS Comparison Table</title>
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
		<script type="text/javascript" src="js/sorttable.js"></script>
		<script type="text/javascript" src="js/vps.js"></script>
		<script type="text/javascript">
			<%?head-script>
		</script>
		<script type="text/javascript">
		/* <![CDATA[ */
			(function() {
				var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
				s.type = 'text/javascript';
				s.async = true;
				s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
				t.parentNode.insertBefore(s, t);
			})();
		/* ]]> */</script>
		<script type="text/javascript">
		  WebFontConfig = {
			google: { families: [ 'Oxygen' ] }
		  };
		  (function() {
			var wf = document.createElement('script');
			wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
				'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
			wf.type = 'text/javascript';
			wf.async = 'true';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(wf, s);
		  })();
		</script>
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
		<link type="text/css" rel="stylesheet" href="css/dot-luv/jquery-ui-1.8.18.custom.css">
		<link type="text/css" rel="stylesheet" href="css/jquery.dataTables.css">
		<link type="text/css" rel="stylesheet" href="css/table.css">
		<link type="text/css" rel="stylesheet" href="css/vps.css">
	</head>
	<body>
		<div class="header">
			<h1>VPS Comparison Table</h1>
			<a href="index.php?action=list" class="button">The list</a>
			<a href="http://wiki.cryto.net/doku.php?id=projects:vpslist" target="_blank" class="button">About</a>
			<a href="quickadd.php" target="_blank" class="button">Submit plans</a>
			<a href="index.php?action=donate" class="button" style="color: #FFE42D; font-weight: bold;">Donate</a>
			<div class="flattr">
				<a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://vps-list.cryto.net/"></a>
				<noscript><a href="http://flattr.com/thing/607780/Cryto-VPS-Comparison-Table" target="_blank">
				<img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></noscript>
			</div>
			<div class="clear"></div>
		</div>
		<div class="notice-header">
			<strong>User submission page is now available!</strong> You can now add providers and plans yourself, without having to register. After review by 
			the site administrator they will become visible in the table. <a href="quickadd.php">Click here to go to the user submission page.</a>
		</div>
		<div class="main">			
			<%?contents>
		</div>
		<div id="sorting_arrow_down">▼</div>
		<div id="sorting_arrow_up">▲</div>
	</body>
</html>
