<?php

namespace App\Http\Services\Table;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\InvoiceTable;
use App\Models\Invoice;
use App\Models\Table;
use Exception;

class TableService extends Service
{
	protected $limit = 10;

	/**
	 * @param string $name
	 * @param int $capacity
	 * @param string $location
	 * 
	 * @return \App\Models\Table|\Exception
	 */
	public function create(string $name, int $capacity, string $location): Table|Exception
	{
		DB::beginTransaction();

		try {
			$table = Table::create([
				'name' => $name,
				'capacity' => $capacity,
				'location' => $location
			]);

			DB::commit();

			return $table;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

	/**
	 * @param string|null $search
	 * @param int|null $limit
	 * 
	 * 
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function list(string|null $search = null, int|null $limit = null): LengthAwarePaginator
	{
		$query = Table::query();

		$query->when(
			$search !== null,
			function (Builder $query) use ($search): Builder {
				return $query->whereLike('name', $search);
			}
		);

		$table = $query->paginate($limit ?? $this->limit);

		$table->getCollection()->transform(function ($item) {
			$check = $item->invoices()->whereHas('invoice', function (Builder $query): void {
				$query->where('status', Invoice::PENDING);
			})->exists();

			$check = $check ? 'terisi' : 'tersedia';

			$item->status = $check;

			return $item->only([
				'id',
				'name',
				'capacity',
				'location',
				'status'
			]);
		});

		return $table;
	}

	/**
	 * 
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll(): Collection
	{
		$kitchen = Table::all();

		return $kitchen;
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\Table|null
	 */
	public function getById(string $id): Table|null
	{
		return Table::find($id);
	}

	/**
	 * @param \App\Models\Table $table
	 * @param string $name
	 * @param int $capacity
	 * @param string $location
	 * 
	 * @return void
	 */
	public function update(Table $table, string $name, int $capacity, string $location): void
	{
		$table->name = $name;
		$table->capacity = $capacity;
		$table->location = $location;

		$table->save();
	}

	/**
	 * @param \App\Models\Table $table
	 * 
	 * @return bool|null
	 */
	public function delete(Table $table): bool|null
	{
		if ($table->invoices()->exists()) {
			return $table->delete();
		}

		return $table->forceDelete();
	}

	/**
	 * @param string|null $status
	 * 
	 * @return array
	 */
	public function listWithCondition(string|null $status = null): array
	{
		$tables = Table::orderBy('name')->get();

		$data = collect();

		$unavailable = 0;
		$available = 0;

		foreach ($tables as $table) {
			$invoice = $table->invoices()
				->whereHas('invoice', function (Builder $query): void {
					$query->where('status', Invoice::PENDING);
				});

			$check = $invoice->exists();

			if ($check) {
				$check = 'terisi';
				$unavailable++;
			} else {
				$check = 'tersedia';
				$available++;
			}

			if ($status === null || $status === $check) {
				$data->add([
					...$table->only([
						'id',
						'name',
						'capacity',
						'location',
					]),
					'invoice' => $invoice->first()['invoice_id'] ?? null,
					'status' => $check,
				]);
			}
		}

		return [
			'tables' => $data->toArray(),
			'unavailable' => $unavailable,
			'available' => $available,
		];
	}

	/**
	 * @param string $fromId
	 * @param array $toIds
	 * 
	 * @return bool|Exception|null
	 */
	public function changeOrderTable(string $fromId, array $toIds): bool|Exception|null
	{
		if ($from = $this->getById($fromId)) {
			$invoices = Invoice::whereHas('tables', function (Builder $query) use ($from): void {
				$query->whereBelongsTo($from, 'table');
			})
				->where('status', Invoice::PENDING)
				->get();
			$tables = Table::findMany($toIds);

			if ($tables->count() && $invoices->count()) {
				$currentDate = Carbon::now();

				DB::beginTransaction();

				try {
					$from->invoices()->whereRelation('invoice', 'status', Invoice::PENDING)->forceDelete();

					$data = [];
					foreach ($tables as $table) {
						foreach ($invoices as $invoice) {
							$data[] = [
								'id' => uuid_create(),

								'invoice_id' => $invoice->id,
								'table_id' => $table->id,

								'created_at' => $currentDate,
								'updated_at' => $currentDate,
							];
						}
					}

					InvoiceTable::insert($data);

					DB::commit();

					return true;
				} catch (Exception $e) {
					DB::rollBack();

					return $e;
				}
			}
		}

		return null;
	}
}
