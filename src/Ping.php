<?php

	namespace Otodev\Ping;

	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\ClientException;
	use GuzzleHttp\Exception\ConnectException;
	use GuzzleHttp\Exception\GuzzleException;
	use GuzzleHttp\Exception\ServerException;

	/**
	 * Class Ping
	 * @package Otodev\Ping
	 */
	class Ping {
		/**
		 * @var Client
		 */
		protected $client;

		/**
		 * @var int
		 */
		protected $responseCode;

		/**
		 * @var int
		 */
		protected $timeout = 5;

		/**
		 * @var bool
		 */
		protected $allowRedirects = true;

		/**
		 * @var array
		 */
		protected $dns;

		/**
		 * Ping constructor.
		 */
		public function __construct() {
			$this->client = new Client([
				'timeout'         => $this->timeout,
				'allow_redirects' => $this->allowRedirects,
			]);
		}

		/**
		 * @param $url
		 *
		 * @return int|mixed
		 */
		public function check($url) {
			try {
				$response           = $this->client->get($url);
				$this->responseCode = $response->getStatusCode();
			} catch(ClientException $e) {
				$response           = $e->getResponse();
				$this->responseCode = $response->getStatusCode();
			} catch(ConnectException $e) {
				$this->responseCode = $e->getCode();
			} catch(ServerException $e) {
				$this->responseCode = $e->getCode();
			}

			return $this->responseCode;
		}

		/**
		 * @param $host
		 * @param $val
		 *
		 * @return bool
		 */
		public function checkA($host, $val) {
			$this->dns = collect(dns_get_record($host, DNS_A));
			$filtered  = $this->dns->filter(function($item, $key) use ($val) {
				return $item['ip'] == $val;
			});

			return $filtered->count() > 0;
		}

		/**
		 * @param $host
		 * @param $val
		 *
		 * @return bool
		 */
		public function checkCname($host, $val) {
			$this->dns = collect(dns_get_record($host, DNS_CNAME));
			$filtered  = $this->dns->filter(function($item, $key) use ($val) {
				return $item['target'] === $val;
			});

			return $filtered->count() > 0;
		}

		/**
		 * @param $host
		 * @param $val
		 *
		 * @return bool
		 */
		public function checkNameserver($host, $val) {
			$this->dns = collect(dns_get_record($host, DNS_NS));
			$filtered  = $this->dns->filter(function($item, $key) use ($val) {
				return $item['target'] === $val;
			});

			return $filtered->count() > 0;
		}

	}