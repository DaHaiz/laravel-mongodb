<?php namespace Atrauzzi\LaravelMongodb {

	use Illuminate\Support\ServiceProvider as Base;
	//
	use Illuminate\Foundation\Application;
	use Doctrine\ODM\MongoDB\Configuration;
	use Doctrine\MongoDB\Connection;
	use Doctrine\ODM\MongoDB\DocumentManager;
	use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;


	class ServiceProvider extends Base {

		public function boot() {
			$this->package('atrauzzi/laravel-mongodb', 'mongodb');
		}

		public function register() {

			$this->app->singleton('Doctrine\MongoDB\Configuration', function (Application $app) {

				/** @var \Illuminate\Config\Repository $laravelConfig */
				$laravelConfig = $app->make('Illuminate\Config\Repository');

				$config = new Configuration();

				$config->setProxyDir(storage_path('cache/MongoDbProxies'));
				$config->setProxyNamespace('MongoDbProxy');

				$config->setHydratorDir(storage_path('cache/MongoDbHydrators'));
				$config->setHydratorNamespace('MongoDbHydrator');

				$config->setDefaultDB($laravelConfig->get('mongodb::default_db'));

				return $config;

			});

			$this->app->singleton('', function () {

			});

		}

	}

}