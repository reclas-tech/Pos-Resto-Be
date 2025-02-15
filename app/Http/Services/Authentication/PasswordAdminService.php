<?php

namespace App\Http\Services\Authentication;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Mail\ForgetPasswordMail;
use App\Http\Services\Service;
use App\Helpers\Token;
use App\Models\Admin;
use Exception;

class PasswordAdminService extends Service
{
	private float $exp = 3; // in minute

	/**
	 * @param string $email
	 * 
	 * @return array|\Illuminate\Support\Collection|\Exception
	 */
	public function forgetPassword(string $email): array|Collection|Exception
	{
		$admin = Admin::firstWhere('email', $email);

		if ($admin === null) {
			return [
				[
					'message' => 'Email tidak valid',
					'property' => 'email',
				],
			];
		}

		DB::beginTransaction();

		try {
			$otp = fake()->randomNumber(6, true);

			$tokenData = Token::Generate(['sub' => $admin->email], $this->exp);
			$admin->update(['otp' => (string) $otp]);

			Mail::to($admin->email)->send(new ForgetPasswordMail($admin->only(['email', 'otp'])));

			DB::commit();

			return $tokenData;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

	/**
	 * @param string $email
	 * @param string $otp
	 * 
	 * @return array|\Illuminate\Support\Collection
	 */
	public function otpVerification(string $email, string $otp): array|Collection
	{
		$admin = Admin::where('email', $email)->where('otp', $otp)->first();

		if ($admin === null) {
			return [
				[
					'message' => 'Kode OTP tidak valid',
					'property' => 'otp',
				],
			];
		}

		$tokenData = Token::Generate(['sub' => $admin->email, 'otp' => 'valid'], $this->exp);

		$admin->refreshToken()->delete();

		return $tokenData;
	}

	/**
	 * @param string $email
	 * @param string $password
	 * 
	 * @return bool
	 */
	public function changePassword(string $email, string $password): bool
	{
		$admin = Admin::where('email', $email)->first();

		if ($admin === null || $admin?->otp === null) {
			return false;
		}

		$admin->update(['password' => $password, 'otp' => null]);

		return true;
	}
}