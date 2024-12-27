<?php

namespace App\Http\Services\Authentication;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Mail\ForgetPasswordMail;
use App\Http\Services\Service;
use Illuminate\Support\Str;
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

		if ($admin !== null) {
			DB::beginTransaction();

			try {
				$otp = Str::random(6);

				$tokenData = Token::generate(['sub' => $admin->email], $this->exp);
				$admin->update(['otp' => $otp]);

				Mail::to($admin->email)->send(new ForgetPasswordMail($admin->toArray()));

				DB::commit();

				return $tokenData;
			} catch (Exception $e) {
				DB::rollBack();

				return $e;
			}
		}

		return [
			[
				'message' => 'Email tidak valid',
				'property' => 'email',
			],
		];
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

		if ($admin !== null) {
			$tokenData = Token::generate(['sub' => $admin->email, 'otp' => 'valid'], $this->exp);

			return $tokenData;
		}

		return [
			[
				'message' => 'Kode OTP tidak valid',
				'property' => 'otp',
			],
		];
	}
}