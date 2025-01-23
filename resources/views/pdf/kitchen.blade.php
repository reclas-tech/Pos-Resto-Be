<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <div class="w-full p-10 text-4xl">
        <p class="text-center font-medium uppercase">Checker</p>
        <p class="text-center uppercase">{{ $name ?? '-' }}</p>

        <table class="border-black w-full border-dashed mt-10 border-b-2">
            <tr class="*:pt-5">
                <td>No Transaksi</td>
                <td>:</td>
                <td>{{ $invoice ?? '-' }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ $created_at ?? "-" }}</td>
            </tr>
            <tr>
                <td>Nama Pemesan</td>
                <td>:</td>
                <td>{{ $customer ?? "-" }}</td>
            </tr>
            <tr class="*:pb-5">
                <td>Nomor Meja</td>
                <td>:</td>
                <td>
                    @foreach ($tables ?? [] as $item)
                        {{ $item }}
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                </td>
            </tr>
        </table>

        <div class="mt-2 space-y-2 border-b-2 border-dashed border-black pb-2">
            <table>
                @foreach ($products ?? [] as $item)
                    <tr>
                        <td class="pe-3">{{$item['quantity'] ?? '-'}}x</td>
                        <td colspan="2">{{$item['name'] ?? '-'}}</td>
                    </tr>
                    <tr class="*:pb-5 *:pt-2">
                        <td></td>
                        <td>Note :</td>
                        <td class="ps-2">{{$item['note'] ?? '-'}}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <p id="time-footer" class="mt-4 text-center">{{ $created_at ?? '-' }}</p>
        <p class="text-center">Cetak : Waroeng Aceh Garuda</p>
    </div>

</body>

</html>
