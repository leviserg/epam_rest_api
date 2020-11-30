<?php
	$pd = require "prdir.php";
	return [
		$pd              			=> [ 'model'=>'Main',	'action'=>'getAll',		'method'=>'GET'		],
		$pd.'todos'		 			=> [ 'model'=>'Main',	'action'=>'getAll',		'method'=>'GET'		],
		$pd.'todos/{id:\d+}'		=> [ 'model'=>'Main',	'action'=>'getOne',		'method'=>'GET' 	],
		$pd.'todos/new/save'		=> [ 'model'=>'Main',	'action'=>'insNew',		'method'=>'POST'	],
		$pd.'todos/{id:\d+}/save'	=> [ 'model'=>'Main',	'action'=>'updOne',		'method'=>'PUT'		],
		$pd.'todos/{id:\d+}/del'	=> [ 'model'=>'Main',	'action'=>'delOne',		'method'=>'DELETE'	]
	];
?>