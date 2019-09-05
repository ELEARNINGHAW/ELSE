<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>AjAX Praxisbeispiel</title>
		<script type='text/javascript' src='https://code.jquery.com/jquery-3.1.0.min.js'></script>
 		<style>
			*{ 	font-family: "courier new";	}
			.ausgabe{ margin: 8px; padding: 8px; }
		</style>
 	</head>
 
	<body>
      <main>
		<a class='btn btn-default btn-sm' href='javascript:;' onCLick="$.ajax({url: './ajax.php', type: 'GET', success: function(data){$('.ausgabe').html(data);}});">AjAX-Request ausführen</a>
		  <div class='ausgabe'>
			Hier steht was, nachdem du auf den Button klickst
		</div>
		</main>
 
	</body>
 
</html>