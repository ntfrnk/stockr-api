<?php

namespace App\Classes;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth 
{
	public $key;
	public $encode;

	public function __construct()
	{
		$this->key = env('JWT_KEY');
		$this->encode = env('JWT_ENCODE');
	}

	/**
	 * Encode the data
	 * 
	 * @param array $payload
	 */
	public function encode($payload)
	{
		return JWT::encode($payload, $this->key, $this->encode);
	}

	/**
	 * Decode the JWT Token
	 * 
	 * @param array $payload
	 */
	public function decode($token = null)
	{
		if( !is_null($token) ) {
			try {
				$response = JWT::decode($token, new Key($this->key, $this->encode));
			} catch(\RuntimeException $e) {
				$response = false;
			}
		} else {
			$response = false;
		}
		return $response;
	}

}
