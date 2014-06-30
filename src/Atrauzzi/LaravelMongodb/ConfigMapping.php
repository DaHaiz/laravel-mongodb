<?php namespace Atrauzzi\LaravelMongodb {

	use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
	//
	use Doctrine\Common\Persistence\Mapping\ClassMetadata;
	use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;


	class ConfigMapping implements MappingDriver {

		/** @var array */
		protected $config;

		/** @var array */
		protected $classCache;

		public function __construct(array $config) {
			$this->config = $config;
			$this->classCache = [];
		}

		/**
		 * Loads the metadata for the specified class into the provided container.
		 *
		 * ToDo: Mapped superclass
		 * ToDo: Embedded document
		 * ToDo: Repository classes
		 *
		 * ToDo: Inheritance type
		 * ToDo: Change tracking policy
		 * ToDo: Discriminator field
		 * ToDo: Discriminator map
		 * ToDo: Not saved
		 * ToDo: Also load
		 *
		 * @param string $className
		 * @param \Doctrine\Common\Persistence\Mapping\ClassMetadata|\Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo $metadata
		 *
		 * @return void
		 */
		public function loadMetadataForClass($className, ClassMetadata $metadata) {

			if(empty($this->config[$className]))
				return;

			/** @var array $config */
			$config = $this->config[$className];

			// If this entity is supposed to go to a different db.
			if(!empty($config['db']))
				$metadata->setDatabase($config['db']);

			// If this entity's collection is specified.
			if(!empty($config['collection']))
				$metadata->setCollection($config['collection']);

			// If we're requiring indexes.
			if(!empty($config['require-indexes']))
				$metadata->setRequireIndexes(true);

			// If slave is okay.
			if(!empty($config['slave-okay']))
				$metadata->setSlaveOkay(true);

			// If any indexes need to be set up.
			if(!empty($config['indexes']))
				foreach($config['indexes'] as $name => $index)
					$this->addIndex($metadata, $name, $index);

			// Map any fields.
			if(!empty($config['fields']))
				foreach($config['fields'] as $fieldConfig)
					$metadata->mapField($fieldConfig);

			// Map any references.
			if(!empty($config['references']))
				foreach($config['references'] as $referenceName => $referenceConfig)
					$this->mapReference($metadata, $referenceName, $referenceConfig);

			// Map any embeds.
			if(!empty($config['embeds']))
				foreach($config['embeds'] as $embedName => $embedConfig)
					$this->mapEmbed($metadata, $embedName, $embedConfig);


		}

		/**
		 * Gets the names of all mapped classes known to this driver.
		 *
		 * @return array The names of all mapped classes known to this driver.
		 */
		public function getAllClassNames() {
			return array_keys($this->config);
			// I'm not sure if we should only be returning the classes we've *already* mapped, or all the ones we know *how* to map?
			//return array_keys($this->classCache);
		}

		/**
		 * Returns whether the class with the specified name should have its metadata loaded.
		 * This is only the case if it is either mapped as an Entity or a MappedSuperclass.
		 *
		 * @param string $className
		 *
		 * @return boolean
		 */
		public function isTransient($className) {
			// TODO: Implement isTransient() method.
		}

		//
		//
		//

		/**
		 * @param ClassMetadataInfo $metadata
		 * @param string $name
		 * @param array $index
		 */
		protected function addIndex(ClassMetadataInfo $metadata, $name, array $index) {

			if(empty($index['keys']))
				return;

			$keys = $index['keys'];
			unset($index['keys']);

			$options = array_merge($index, [
				'name' => $name
			]);

			$metadata->addIndex($keys, $options);

		}

		/**
		 * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo $metadata
		 * @param string $referenceName
		 * @param array $referenceConfig
		 */
		protected function mapReference(ClassMetadataInfo $metadata, $referenceName, array $referenceConfig) {



		}

		protected function mapEmbed(ClassMetadataInfo $metadata, $referenceName, array $referenceConfig) {
		}

	}

}