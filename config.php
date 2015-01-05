<?php return [

	'default_db' => 'laravel',

	'mappings' => [

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

	]

];
