<?php namespace Atrauzzi\LaravelMongodb\ValidationRule {

	use Doctrine\ODM\MongoDB\DocumentManager;


	class Unique {

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
		 * mongo_unique:users,email_address,10
		 *
		 * @param $attribute
		 * @param $value
		 * @param $parameters
		 * @return bool
		 */
		public function validate($attribute, $value, $parameters) {

			if(is_null($value))
				return true;

			$collection = $parameters[0];
			$field = $parameters[1];

			if(!empty($paramers[2]) && $value == $parameters[2])
				return true;

			return !((bool)$this->documentManager
				->createQueryBuilder($collection)
				->field($field)
				->equals($value)
				->count()
				->getQuery()
				->execute()
			);

		}

	}

}