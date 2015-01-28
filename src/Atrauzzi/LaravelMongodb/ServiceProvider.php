<?php namespace Atrauzzi\LaravelMongodb {

	use Illuminate\Support\ServiceProvider as Base;
	//
	use Illuminate\Foundation\Application;
	use Illuminate\Config\Repository as Config;
	use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
	use Illuminate\Contracts\Routing\Registrar as Router;
	use Doctrine\ODM\MongoDB\Configuration;
	use Doctrine\ODM\MongoDB\DocumentManager;
	use Doctrine\MongoDB\Connection;
	use MongoClient;


	class ServiceProvider extends Base {

		/**
		 * Called on framework init.
		 */
		public function register() {

			// Note:    If you'd like to use annotation, XML or YAML mappings, simply bind another
			//          implementation of this interface in your project and we'll use it! :)
			$this->app->singleton('Doctrine\Common\Persistence\Mapping\Driver\MappingDriver', function (Application $app) {

				/** @var \Illuminate\Config\Repository $laravelConfig */
				$laravelConfig = $app->make('Illuminate\Config\Repository');

				return new ConfigMapping($laravelConfig->get('mongodb.mappings'));

			});

			$this->app->singleton('Doctrine\MongoDB\Configuration', function (Application $app) {

				/** @var \Illuminate\Config\Repository $laravelConfig */
				$laravelConfig = $app->make('Illuminate\Config\Repository');

				$config = new Configuration();

				$config->setProxyDir(storage_path('cache/MongoDbProxies'));
				$config->setProxyNamespace('MongoDbProxy');

				$config->setHydratorDir(storage_path('cache/MongoDbHydrators'));
				$config->setHydratorNamespace('MongoDbHydrator');

				$config->setDefaultDB($laravelConfig->get('mongodb.default_db', 'laravel'));

				// Request whatever mapping driver is bound to the interface.
				$config->setMetadataDriverImpl($app->make('Doctrine\Common\Persistence\Mapping\Driver\MappingDriver'));

				return $config;

			});

			$this->app->singleton('MongoClient', function (Application $app) {
				/** @var \Illuminate\Config\Repository $laravelConfig */
				$laravelConfig = $app->make('Illuminate\Config\Repository');
				return new MongoClient($laravelConfig->get('mongodb.host'));

			});

			$this->app->singleton('Doctrine\MongoDB\Connection', function (Application $app) {
				return new Connection($app->make('MongoClient'));
			});

			// Because of our bindings above, this one's actually a cinch!
			$this->app->singleton('Doctrine\ODM\MongoDB\DocumentManager', function (Application $app) {
				return DocumentManager::create(
					$app->make('Doctrine\MongoDB\Connection'),
					$app->make('Doctrine\MongoDB\Configuration')
				);
			});

		}

		/**
		 * Called before any commands or requests are run.
		 *
		 * @param Config $config
		 * @param ValidatorFactory $validator
		 * @param Router $router
		 */
		public function boot(Config $config, ValidatorFactory $validator, Router $router) {

			$this->publishes([
				__DIR__ . '/../../../config/mongodb.php' => config_path('mongodb.php')
			]);

			$config->set('mongodb.default-db', 'localhost');

			$validator->extend('mongo_unique', 'Atrauzzi\LaravelMongodb\ValidationRule\Unique@validate');
			$validator->extend('mongo_exists', 'Atrauzzi\LaravelMongodb\ValidationRule\Exists@validate');

			// ToDo: Convert this to Laravel 5 middleware?
			$router->after('Atrauzzi\LaravelMongodb\ShutdownHandler');

		}

	}

}