<?php

namespace App\Http\Services\Example;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use App\Models\Example;
use App\Models\User;
use Exception;

class ExampleService extends Service
{
	/**
	 * @param string $code
	 * @param string $name
	 * @param string $description
	 * @param string|null $tag
	 * @param \App\Models\User|int|null $user
	 * 
	 * @return \App\Models\Example|\Exception
	 */
	public function create(string $code, string $name, string $description, string|null $tag = null, User|int|null $user = null): Example|Exception
	{
		DB::beginTransaction();

		try {
			$example = new Example([
				'description' => $description,
				'code' => $code,
				'name' => $name,
				'tag' => $tag,
			]);

			$example->user_id = $user?->id ?? $user;

			$example->save();

			DB::commit();

			return $example;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

	/**
	 * @param bool $deleted
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll(bool $deleted = false): Collection
	{
		return Example::withTrashed($deleted)->latest()->get();
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\Example|null
	 */
	public function getById(string $id): Example|null
	{
		return Example::find($id);
	}

	/**
	 * @param \App\Models\Example $example
	 * @param string $code
	 * @param string $name
	 * @param string $description
	 * @param string|null $tag
	 * @param \App\Models\User|int|null $user
	 * 
	 * @return void
	 */
	public function update(Example $example, string $code, string $name, string $description, string|null $tag = null, User|int|null $user = null): void
	{
		$example->description = $description;
		$example->code = $code;
		$example->name = $name;
		$example->tag = $tag;

		$example->save();

		$this->updateUser($example, $user);
	}

	/**
	 * @param \App\Models\Example $example
	 * @param \App\Models\User|int|null $user
	 * 
	 * @return bool
	 */
	public function updateUser(Example $example, User|int|null $user): bool
	{
		$example->user_id = $user?->id ?? $user;

		return $example->save();
	}

	/**
	 * @param \App\Models\Example $example
	 * 
	 * @return bool|null
	 */
	public function delete(Example $example): bool|null
	{
		return $example->forceDelete() ?? $example->delete();
	}
}
