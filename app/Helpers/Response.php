<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use App\Traits\StatusCode;

class Response
{
	use StatusCode;


	/*
	   |--------------------------------------------------------------------------
	   | Variables
	   |--------------------------------------------------------------------------
	   */

	private int $status = Response::OK;
	private string $message = '';
	private mixed $data = [];


	/*
	   |--------------------------------------------------------------------------
	   | Functions
	   |--------------------------------------------------------------------------
	   */

	/**
	 * @param int $status
	 * @param string $message
	 * @param mixed $data
	 */
	public function __construct(int $status = Response::OK, string $message = '', mixed $data = [])
	{
		$this->set($status, $message, $data);
	}

	/**
	 * @param int $status
	 * @param string $message
	 * @param mixed $data
	 * 
	 * @return void
	 */
	public function set(int|null $status = null, string|null $message = null, mixed $data = null): void
	{
		$this->message = $message ?? $this->message;
		$this->status = $status ?? $this->status;
		$this->data = $data ?? $this->data;
	}

	/**
	 * @param int $status
	 * @param string $message
	 * @param mixed $data
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function get(int|null $status = null, string|null $message = null, mixed $data = null): JsonResponse
	{
		$this->set($status, $message, $data);

		return $this->SetAndGet($this->status, $this->message, $this->data);
	}

	/**
	 * @param int $status
	 * @param string $message
	 * @param mixed $data
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	public static function SetAndGet(int $status = Response::OK, string $message = '', mixed $data = []): JsonResponse
	{
		return response()->json(
			data: [
				'statusCode' => $status,
				'message' => $message,
				'data' => $data,
			],
			status: $status,
		);
	}
}
