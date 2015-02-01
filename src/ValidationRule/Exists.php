<?php namespace Atrauzzi\LaravelMongodb\ValidationRule {

	use Doctrine\ODM\MongoDB\DocumentManager;


	class Exists {

		/** @var \Doctrine\ODM\MongoDB\DocumentManager */
		protected $documentManager;

		/**
		 * @param DocumentManager $documentManager
		 */
		public function __construct(
			DocumentManager $documentManager
		) {
			$this->documentManager = $documentManager;
		}

		/**
		 * mongo_exists:users,email_address,10
		 *
		 * @param $attribute
		 * @param $value
		 * @param $parameters
		 * @return bool
		 */
		public function validate($attribute, $value, $parameters) {

			$collection = $parameters[0];

			if(empty($parameters[1]))
				$field = 'id';
			else
				$field = $parameters[1];

			return (bool)$this->documentManager
				->createQueryBuilder($collection)
				->field($field)
				->equals($value)
				->count()
				->getQuery()
				->execute()
			;

		}

	}

}
