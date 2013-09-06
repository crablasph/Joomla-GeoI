$( "#SearchTask" ).click(function() {
		$( "#SearchWindow" ).slideToggle();
		$( "#LoginWindow" ).hide();
	//$( "#SearchWindow" ).toggle();
			});

$( "#AuthTask" ).click(function() {
	$( "#LoginWindow" ).slideToggle();
	$( "#SearchWindow" ).hide();
		});


$( ".CloseWindow" ).click(function() {
	$(this).parent().hide();
	});

