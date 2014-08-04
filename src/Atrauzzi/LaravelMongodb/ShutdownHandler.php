<?php namespace Atrauzzi\LaravelMongodb {

	use Doctrine\ODM\MongoDB\DocumentManager;


	class ShutdownHandler {

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

		public function filter() {

			$this->documentManager->flush();

		}

	}

}