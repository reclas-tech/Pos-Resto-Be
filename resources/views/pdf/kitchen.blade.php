<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <div id="struk" class="w-fit bg-pink-300 p-2.5 text-xl">
        <p class="text-center font-medium uppercase">Checker</p>
        <p id="kitchen" class="text-center uppercase">Bar</p>

        <div class="mt-6 grid grid-cols-2 border-b border-dashed border-black pb-2 text-left">
            <p>No Transaksi</p>
            <p>: <span id="no_transaksi">TRX001</span></p>
            <p>Waktu</p>
            <p>: <span id="time">12:00</span></p>
            <p>Nama Pemesan</p>
            <p>: <span id="customer">John Doe</span></p>
            <p>Nomor Meja</p>
            <p>: <span id="table">A1</span></p>
        </div>

        <div id="products" class="mt-2 space-y-2 border-b border-dashed border-black pb-2">
            <!-- Product template -->
            <div class="product">
                <span class="flex space-x-[2%]">
                    <p><span class="quantity">1</span>x</p>
                    <p class="name">Nasi Goreng</p>
                </span>
                <ul class="ml-6 list-disc">
                    <li class="note">Pedas Extra</li>
                </ul>
            </div>
        </div>

        <p id="time-footer" class="mt-4 text-center">12:00</p>
        <p class="text-center">Cetak : Waroeng Aceh Garuda</p>
    </div>

</body>

</html>
