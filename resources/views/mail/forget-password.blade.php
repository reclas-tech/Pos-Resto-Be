<div>
    <h1>{{ config('app.name') }}</h1>

    <p>Apakah anda benar melupakan kata sandi akun anda? Berikut kode OTP untuk melanjutkan proses anda</p>

    <table>
        <tr>
            <td>Email</td>
            <td>:</td>
            <td>{{ $data['email'] ?? 'NULL' }}</td>
        </tr>
        <tr>
            <td>OTP</td>
            <td>:</td>
            <td>{{ $data['otp'] ?? 'NULL' }}</td>
        </tr>
    </table>

    <p>Mohon masukkan kode OTP dengan sebaik mungkin</p>
</div>
