<!doctype html>
<html>

<head>
    <title>3rd Party Trucking Letter</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.1.2/tailwind.min.css" />
    <link rel="stylesheet" href="output.css" />
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body{
            background-color:white;
            font-size: 13px;
        }
        .subHeader {
            width: 100%;
            font-size: 13px;
            line-height: 1.1;
        }
        td {
            text-align: center;
        }
    </style>

</head>

<body >


    <content>

    <?php

if($company == "NBFI"){
    echo '<div>
    <div class="relative w-full">
        <div class="absolute top-0 left-0">
            <img  src="/home/Projects/PRESTOv1Test/public/images/NBFIlogo.png" class="w-full" alt="logo">
        </div>
    </div>
</div>';
}

else if($company == "ASC"){
    echo '<div>
    <div class="relative w-full">
        <div class="absolute top-0 left-0">
            <img  src="/home/Projects/PRESTOv1Test/public/images/ASClogo.jpg" class="w-full" alt="logo">
        </div>
    </div>
</div>';
} elseif ($company == "CMC") {
    echo '<div>
        <div class="relative w-full">
            <div class="absolute top-0 left-0">
                <img  src="/home/Projects/PRESTOv1Test/public/images/CMClogo.jpg" class="w-full" alt="logo">
            </div>
        </div>
    </div>';
} elseif ($company == "ASC") {
    echo '<div>
        <div class="relative w-full">
            <div class="absolute top-0 left-0">
                <img  src="/home/Projects/PRESTOv1Test/public/images/AHLClogo.png" class="w-full" alt="logo">
            </div>
        </div>
    </div>';
}
    else if($company == "AHLC"){
        echo '<div>
        <div class="relative w-full">
            <div class="absolute top-0 left-0">
                <img  src="/home/Projects/PRESTOv1Test/public/images/AHLClogo.png" class="w-full" alt="logo">
            </div>
        </div>
    </div>';
    }else if($company == "EPC"){
        echo '<div>
        <div class="relative w-full">
            <div class="absolute top-0 left-0">
                <img  src="/home/Projects/PRESTOv1Test/public/images/EPClogo.png" class="w-1/2" alt="logo">
            </div>
        </div>
    </div>';
    }
        // echo '<img src="'.$src.'" class="h-24" alt="logo">';
    ?>


    <br><br><br><br><br><br><br><br>
    <p>Date: <b>{{ $date }}</b> </p>
    <p>Branch Name: <b>{{ $branchName }}</b></p>
    <p>Subject: <b>PULL OUT FOR BACKLOADING</b> </p><br><br>

    <!-- Message -->
    <p>Dear Sir/Ma'am:</p>
    <p>Please process immediate backloading of the items stated below through Distribution Center delivery van.</p><br>

        <!-- PRODUCT -->
        <p>Below are the listed stocks for pull-out:</p><br>
        <div class="mx-24">
                <table id="branchMaintenanceTable" class="w-full text-xs text-left text-gray-500 dark:text-gray-400 border-collapse border border-slate-800">
                    <thead class="text-ml text-gray-900 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="w-full">

                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                BRAND
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                QUANTITY
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                AMOUNT (PHP)
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- For Loop for items -->
                        @foreach ($data as $item)
                       <tr class="w-full">
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ $item->brand }}</td>
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ $item->quantity_total }}</td>
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ $item->amount_total }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                    <tr class="w-full">

                        <th scope="col" class="py-1 px-3 border border-slate-300">
                            TOTAL
                        </th>
                        <th scope="col" class="py-1 px-3 border border-slate-300">
                            {{ $totalQuantity }}
                        </th>
                        <th scope="col" class="py-1 px-3 border border-slate-300">
                            {{ $totalAmount }}
                        </th>
                    </tr>
                    </tfoot>
                </table>
        </div><br>


        <div class="mx-24">
                <table id="branchMaintenanceTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border border-slate-800">

                    <tbody>
                       <tr>
                            <td class='py-1 px-3 border border-slate-300'><b>Total No. of Cartons</b></td>
                            <td class='py-1 px-3 border border-slate-300'><b>{{ $boxCount}}</b></td>
                            <!-- <td class="opacity-0 bg-transparent pointer-events-none">Hidden Content</td> -->

                        </tr>

                    </tbody>
                </table>
        </div><br><br>

        

    <p>Thank you for accommodating our request.</p><br>
    <p>Very truly yours,</p><br><br>
    <?php

    if($company == "NBFI" || $company == "ASC" || $company == "CMC"){
        echo '<p>Ms. Czareen Legaspi-Yanson</p>
        <p><b><i>Sales Manager</i></b></p>';
    } else {
        echo '<p>Ana Karla Brecio</p>
        <p><b><i>AVP- Sales and Merchandising</i></b></p>';
    }
    ?>
    </content>
</body>


</html>
