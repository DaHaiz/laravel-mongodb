<?php return [

	'host' => env('MONGODB_HOST', 'localhost'),

	'default-db' => env('MONGODB_DATABASE', 'laravel'),

	'mappings' => [

		/*
		'Company\Package\Domain\Entity' => [
			'db' => 'laravel',
			'collection' => 'entity',
			'indexes' => [
				'name' => [
					'keys' => [
						'name' => 'asc'
					]
				]
			],
			'fields' => [
				'name' => [
					'type' => 'string'
				],
				'thing' => [
					'type' => 'many',
				]
			],
		]
		*/

	]

];