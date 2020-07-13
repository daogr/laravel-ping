<?php

	namespace Otodev\Ping\Providers;

	use Illuminate\Contracts\Support\DeferrableProvider;
	use Illuminate\Support\ServiceProvider;
	use Otodev\Ping\Ping;

	/**
	 * Class PingServiceProvider
	 * @package Otodev\Ping\Providers
	 */
	class PingServiceProvider extends ServiceProvider implements DeferrableProvider {

		/**
		 * Register services.
		 *
		 * @return void
		 */
		public function register() {
			$this->app->singleton('ping', function($app) {
				return new Ping();
			});
		}

		/**
		 * Get the services provided by the provider.
		 *
		 * @return array
		 */
		public function provides() {
			return ['ping'];
		}
	}
