<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PullOutModel;
use App\Models\PullOutBranchModel;
use App\Models\PullOutItemModel;
use App\Models\PullOutBranchModelNBFI;
use App\Models\PullOutItemModelNBFI;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use PdfReport;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;
use App\Models\TransactionModel;
use App\Models\EpcBranchModel;
use App\Models\NbfiBranchModel;
use App\Models\EpcBrandModel;
use App\Models\NbfiBrandModel;
use App\Models\EpcDriverModel;
use App\Models\EpcReasonModel;
use App\Models\ImageBranchModel;
use App\Models\UserBranchModel;
use App\Models\RemarksModel;
use App\Models\pullOutLetterDates;
use App\Http\Controllers\BrevoSMService;
use Illuminate\Support\Facades\File;
use Image;

class PostController extends Controller
{

    public function checkAccStatus(Request $request){

        // $status = DB::table('users as a')
        // ->join('companyTbl as b', 'a.company', '=', 'b.id')
        // ->select('b.shortName as company','a.status')
        // ->where('a.email', $request->email)
        // ->get();
        $status = DB::table('users as a')
                    ->join('companyTbl as b', 'a.company', '=', 'b.id')
                    ->select('b.shortName as company', 'a.status')
                    ->where('a.email', $request->email)
                    ->get();

        // $status = DB::table('users as a')
        //             ->select('a.status')
        //             ->where('a.email', $request->email)
        //             ->get();

        return response()->json($status);
    }

    public function postPullOutRequest(Request $request){

        //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
        $price = DB::table('epc_items_barcode')
                    ->select('EffectivePrice')
                    ->where('ItemNo', '=', $request->itemCode)
                    ->first();

        //COMPUTATION TOTAL AMOUNT
        $amount = floatval($price->EffectivePrice) * floatval($request->quantity);

        //CONDITION WITH THE COMPANY
        if ($request->chainCode == "RDS"){
            $company = 5; //AHLC
        }else{
            $company = 4; //EPC
        }

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        //PUTTING IT INTO THE OBJECT FOR THE MODEL
        $input = new PullOutModel();
        $input->plID = $request->id;
        $input->chainCode = $request->chainCode;
        $input->company = $company;
        $input->branchName = $request->branchName;
        $input->brand = $request->brand;
        $input->transactionType = $request->transactionType;
        $input->boxNumber = $request->boxNumber;
        $input->boxLabel = $request->boxLabel;
        $input->itemCode = $request->itemCode;
        $input->quantity = $request->quantity;
        $input->amount = $amount;
        $input->status = 'unprocessed';
        $input->dateTime = $date;

        //SAVING
        $input->save();
        return response()->json($input);

    }

    public function generatePDF(Request $request){
        $name = $request->input('322p312t'); // GET THE NAME
        $id = $request->input('3430211w'); // GET THE ID
        $date_start = $request->input('2s2p382t2b382p3638'); // GET THE DATE START
        $date_end = $request->input('2s2p382t1x322s'); // GET THE DATE END
        $promoEmail = $request->input('2t312p2x30'); // GET THE PROMO EMAIL
        $userID = $request->input('39372t36211w'); // GET THE USER ID
        $holder_status = $request->input('37382p383937'); // GET THE STATUS
        $company = $request->input('2r3331342p323d'); // GET THE COMPANY
        $regenerate = $request->input('362t2v2t322t362p382t'); // GET THE REGENERATE STATUS


        if($company == "EPC"){
            $tbPullOutBranch = 'pullOutBranchTbl';
            $tbPullOutItems = 'pullOutItemsTbl';
            $tbItems = 'epc_items_barcode';
        } else if($company == "NBFI"){
            $tbPullOutBranch = 'pullOutBranchTblNBFI';
            $tbPullOutItems = 'pullOutItemsTblNBFI';
            $tbItems = 'nbfi_items_barcode';
        }

        //GET THE ONLY NEEDED SINGLE DATA AND WILL BE USE THE QUANTITY AND TOTAL AMOUNT
        $tempdata = DB::table($tbPullOutBranch . ' as a')
                        ->join($tbPullOutItems . ' as b', 'a.id', '=', 'b.plID')
                        ->join($tbItems . ' as c', 'b.itemCode', '=', 'c.ItemNo')
                        ->select('a.chainCode', 'a.branchName', 'a.transactionType',
                        DB::raw('CAST(a.dateTime AS DATE) as date'), 'b.quantity',
                        'b.amount', 'b.status', 'b.boxLabel',
                        'c.ItemDescription as itemDescription', 'b.itemCode', 'b.brand', 'a.company')
                        ->where('a.id', $id)
                        ->get();

        $emaildata = DB::table($tbPullOutBranch . ' as a')
                        ->join($tbPullOutItems . ' as b', 'a.id', '=', 'b.plID')
                        ->join($tbItems . ' as c', 'b.itemCode', '=', 'c.ItemNo')
                        ->select('a.chainCode', 'a.branchName', 'a.transactionType',
                        DB::raw('CAST(a.dateTime AS DATE) as date'), 'b.quantity',
                        'b.amount', 'b.status', 'b.boxLabel',
                        'c.ItemDescription as itemDescription', 'b.itemCode', 'b.brand')
                        ->where('a.id', $id)
                        ->where('b.status', 'edited')
                        ->get();

        //GETTING THE ONLY BOX LABEL GROUP AND THE SUB TOTAL OF QUANTITY AND AMOUNT\
        if ($company == "NBFI")
        $data = DB::table($tbPullOutItems . ' as a')
                        ->select('a.brand',
                            DB::raw('SUM(a.quantity) as quantity_total'),
                            DB::raw('SUM(a.amount) as amount_total')
                        )
                        ->where('a.plID', $id)
                        ->groupBy('a.brand')
                        ->get();
        else if($company == "EPC"){
            $data = DB::table($tbPullOutItems)
                            ->select('itemCode', 'quantity', 'amount')
                            ->where('plID', $id)
                            ->get();

            $brandTotals = [];

            foreach($data as $item){
                $brandCode = substr($item->itemCode, 1, 2);
                $brand = DB::table('epcbrandsmaintenance') // Getting the brand per item
                                ->select('brandNames as brand')
                                ->where('id', $brandCode)
                                ->pluck('brand')->first();

                // Check if the brand already exists in the totals array
                if (isset($brandTotals[$brand])) {
                    // Brand already exists, update the totals
                    $brandTotals[$brand]['quantity_total'] += $item->quantity;
                    $brandTotals[$brand]['amount_total'] += $item->amount;
                } else {
                    // Brand doesn't exist, add a new entry
                    $brandTotals[$brand] = [
                        'brand' => $brand, // Add the brand key here
                        'quantity_total' => $item->quantity,
                        'amount_total' => $item->amount,
                    ];

                }
            }

            $data = array_values($brandTotals); // For reseeding the index

            // Convert the array to an array of stdClass objects
            $data = array_map(function ($item) {
                return (object) $item;
            }, $data);
        }



        //GETTING THE BOX COUNT
        $box = DB::table($tbPullOutItems)
                ->select('boxLabel')
                ->where('plID', $id)
                ->groupBy('boxLabel')
                ->get();


        //GETTING THE DRIVERS
        $drivers = DB::table('driverMaintenance')
                    ->select('name', 'position')
                    ->where('status', 'Active')
                    ->get();

        $boxCount = $box->count(); //BOX COUNT

        $length = $tempdata->count(); // LENGTH OF THE DATA

        $totalQuantity = 0;
        $totalAmount = 0;

        $statusCount = false;
        //COMPUTATION FOR THE TOTAL OF QUANTITY AND AMOUNT
        for ($i = 0; $i < $length; $i++){
            $totalQuantity = $totalQuantity + $tempdata[$i]->quantity;
            $totalAmount = $totalAmount + $tempdata[$i]->amount;

            if($tempdata[$i]->status == "edited"){
                $statusCount = true;
            }

        }

        $tempdate = $tempdata[0]->date;
        //FORMATTING DATE
        $formattedDateStart = date("F d, Y", strtotime($date_start));
        $formattedDateEnd = date("F d, Y", strtotime($date_end));
        $formattedDate = date("F d, Y", strtotime($tempdate));


        //FORMATTING AMOUNT
        $formattedAmount = number_format($totalAmount, 2, '.', ',');
        foreach($data as $item){
            // dump($item);
            $item->quantity_total = number_format($item->quantity_total);
            $item->amount_total = number_format($item->amount_total, 2, '.', ',');
        }
        $totalQuantity = number_format($totalQuantity);

        if($regenerate == "regenerate"){
            //TRANSFERRING INTO ARRAY TO BE EASY ACCESS ON SINGLE DATA
            $info = [
                'name' => $name,
                'boxCount' => $boxCount,
                'totalQuantity' => $totalQuantity,
                'totalAmount' => $formattedAmount,
                'date' => $formattedDate,
                'branchName' => $tempdata[0]->branchName,
                'chainCode' => $tempdata[0]->chainCode,
                'dateStart' => $formattedDateStart,
                'dateEnd' => $formattedDateEnd,
                'company' => $tempdata[0]->company,
                'transactionType' => $tempdata[0]->transactionType
            ];
        }else{
            //TRANSFERRING INTO ARRAY TO BE EASY ACCESS ON SINGLE DATA
            $info = [
                'name' => $name,
                'boxCount' => $boxCount,
                'totalQuantity' => $totalQuantity,
                'totalAmount' => $formattedAmount,
                'date' => $formattedDate,
                'branchName' => $tempdata[0]->branchName,
                'chainCode' => $tempdata[0]->chainCode,
                'dateStart' => $formattedDateStart,
                'dateEnd' => $formattedDateEnd,
                'company' => $tempdata[0]->company,
                'transactionType' => $tempdata[0]->transactionType
            ];
        }


        //CONVERTING IT INTO STRING FOR THE FILE NAME
        $string = strval($tempdata[0]->branchName);
        $today = date('Y-m-d');

        // //CONDITION OF PDF TO BE SHOW
        if ($company == "NBFI"){
            switch($tempdata[0]->transactionType){
                case "CPO Item for Disposal in the Store c/o Supervisor":
                    $file = "NBFILetterItemDisposal";
                    break;

            case "CPO for Transfer to Another Store":
                    $file = "NBFILetterAnotherStore";
                    break;

                case "CPO Back to WH via Chain Distribution Center":
                    $file = "NBFILetterChainDistribution";
                    break;

            case "CPO Back to WH via 3rd Party Trucking":
                    $file = "NBFILetter3rdPartyTrucking";
                    break;

                case "CPO Back to WH via In-House Delivery Service":
                    $file = "NBFILetterInHouseDeliveryService";
                    break;

                case "CPO Back to WH c/o Supervisor":
                    $file = "NBFILetterWHSupervisor";
                    break;

                default:
                    $file = "NBFILetterItemDisposal";

            }
        } elseif ($company == "EPC") {
            switch($tempdata[0]->transactionType){
                case "CPO Item for Disposal in the Store c/o Supervisor":
                    $file = "EPCLetterItemDisposal";
                    break;

                case "CPO for Transfer to Another Store":
                    $file = "EPCLetterAnotherStore";
                    break;

                case "CPO Back to WH via Chain Distribution Center":
                    $file = "EPCLetterChainDistribution";
                    break;

                case "CPO Back to WH via 3rd Party Trucking":
                    $file = "EPCLetter3rdPartyTrucking";
                    break;

                case "CPO Back to WH via In-House Delivery Service":
                    $file = "EPCLetterInHouseDeliveryService";
                    break;

                case "CPO Back to WH c/o Supervisor":
                    $file = "EPCLetterWHSupervisor";
                    break;

                default:
                    $file = "EPCLetterItemDisposal";

            }
        }

        //LOADING IT INTO THE PDF
        $pdf = PDF::loadView($file, array('data' => $data, 'drivers' => $drivers),  $info)->setPaper('legal','portrait');

        if($statusCount == true){
            $viewToEmail = "EmailApprovedwithChanges";
            $tempdata = $emaildata;
        }else{
            $viewToEmail = "EmailApproved";
        }

        $boxLabels = '';
        $itemCodes = '';
        $itemDescriptions = '';
        $brands = '';
        $quantities = '';

        foreach ($tempdata as $data) {
            $boxLabels .= $data->boxLabel . ',';
            $itemCodes .= $data->itemCode . ',';
            $itemDescriptions .= $data->itemDescription . ',';
            $brands .= $data->brand . ',';
            $quantities .= $data->quantity . ',';
        }

        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $userID)
                    ->pluck('name');

        $promoName = DB::table('users')
                        ->select('name')
                        ->where('email', $promoEmail)
                        ->pluck('name');


        //SEND EMAIL IF APPROVED
        $mail = array(
            'transactionID' => $id,
            'status' => $holder_status,
            'email' => $promoEmail,
            'name' => $promoName,
            'viewToEmail' => $viewToEmail,
            'adminName' => $name,
            'viewEmail' => $viewToEmail,
            'chainCode' => $tempdata[0]->chainCode,
            'branchName' => $tempdata[0]->branchName,
            'transactionType' => $tempdata[0]->transactionType,
            'boxLabels' => $boxLabels,
            'itemCodes'  => $itemCodes,
            'itemDescriptions' => $itemDescriptions,
            'brands' => $brands,
            'quantities' => $quantities,
        );

        if($regenerate == "generate")
            Mail::to($promoEmail)->send(new MailNotify($mail));

        // $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        // $log = new TransactionModel();
        // $log->dateTime = $date;
        // $log->userID = $userID;
        // $log->action_type = 'Generate PDF';
        // $log->table_affected = 'generatePDF';
        // $log->new_data = json_encode($request->all());
        // $log->save();

        return $pdf->stream($today.' '.$string.'.pdf');
    }

    public function sendDeniedBranch(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->value('name');

        if($request->company == "EPC"){
            $denied = DB::table('pullOutBranchTbl')
                            ->where('id', $request->id)
                            ->update([
                                'editedBy' => $name,
                                'updated_at' => $date,
                                'reasonRemarks' => $request->reason,
                                'status' => 'denied'
                            ]);
            $table_affected = "EPC";
            $old_data = PullOutBranchModel::find($request->id);
        } else if($request->company == "NBFI"){
            $denied = DB::table('pullOutBranchTblNBFI')
                            ->where('id', $request->id)
                            ->update([
                                'editedBy' => $name,
                                'updated_at' => $date,
                                'reasonRemarks' => $request->reason,
                                'status' => 'denied'
                            ]);
            $table_affected = "NBFI";
            $old_data = PullOutBranchModelNBFI::find($request->id);

        }

        $reasons = DB::table('reasonMaintenance')
                    ->select('reasonLabel')
                    ->get();

        $flag = true;
        for ($ctr = 0; $ctr < $reasons->count(); $ctr++){
            if(strtolower($reasons[$ctr]->reasonLabel) == strtolower($request->reason)){
                $flag = false;
                break;
            }
        }
        if($flag){

            $input = new EpcReasonModel();
            $input->reasonLabel = ucfirst($request->reason);
            $input->dateTime = $date;
            $input->company = $request->company;
            $input->status = 'Active';

            $input->save();

            $log = new TransactionModel();
            $log->dateTime = $date;
            $log->userID = $request->userID;
            $log->action_type = 'insert';
            $log->table_affected = 'reasonmaintenance';
            $log->new_data = json_encode($request->all());
            $log->save();

        }


        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->value('name');

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'denied';
        $log->table_affected = $table_affected;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        $data = array(
            'transactionID' => $request->id,
            'name' => $request->promoName,
            'status' => 'Denied',
            'reason' => $request->reason,
            'adminName' => $name
        );

        $res = Mail::to($request->email)->send(new MailNotify($data));

        return response()->json($reasons);

    }

    public function savePullOutBranchRequest(Request $request){


            $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

            //SELECTING MODEL AND TABLE BY COMPANY
            if($request->companyType == 'EPC' || $request->companyType == 'AHLC'){
                $branch = new PullOutBranchModel();
                $tbItem = 'epc_items_barcode';
                $table_affected = 'pullOutBranchTbl';
            }
            else if($request->companyType == 'NBFI' || $request->companyType == 'ASC' || $request->companyType == 'CMC'){
                $branch = new PullOutBranchModelNBFI();
                $tbItem = 'nbfi_items_barcode';
                $table_affected = 'pullOutBranchTblNBFI';
            }

            //FIELDS FOR SAVING ON DB
            $branch->chainCode = $request->chainCode;
            $branch->company = $request->companyType;
            $branch->branchName = $request->branchName;
            $branch->transactionType = $request->transactionType;
            $branch->status = $request->status;
            $branch->dateTime = $date;
            $branch->promoEmail = $request->email;
            $branch->SLA_count = "15";
            $branch->SLA_status = "In Time";

            //SAVING
            $branch->save();

            //FILTERING FOR SAVING ON LOGS
            $new_data = array(
                'id' => $branch->id,
                'branchName' => $request->branchName,
                'promoEmail' => $request->email,
                'status' => $request->status
            );
            //GETTING THE ID OF THE USER
            $dataID = DB::table('users')
                        ->where('email', $request->email)
                        ->value('id');


            //FIELDS ON TRANSACTION LOGS
            $log = new TransactionModel();
            $log->dateTime = $date;
            $log->userID = $dataID;
            $log->action_type = 'insert';
            $log->table_affected = $table_affected;
            $log->new_data = json_encode($new_data);

            //SAVING
            $log->save();

            //TRANSFERING THE OBJECT
            $items = $request->items;
            $boxes = $request->boxes;

            foreach($items as $item){ //LOOPING BY ITEMS
                foreach($boxes as $box){ //LOOPING BY BOX
                    if($box['id'] == $item['boxNumber']){ //CONDITION FOR BOX LABEL

                        $price = DB::table($tbItem)
                                    ->select('EffectivePrice')
                                    ->where('ItemNo', '=', $item['code'])
                                    ->first(); //GETTING THE PRICE PER CODE

                        //INITIALIZE FOR THE MODEL OF ITEM
                        $itemSave = $request->companyType == 'EPC' || $request->companyType == 'AHLC' ? new PullOutItemModel() : new PullOutItemModelNBFI();

                        //COMPUTATION TOTAL AMOUNT
                        $amount = floatval($price->EffectivePrice) * floatval($item['quantity']);

                        //FIELDS FOR ITEMS
                        $itemSave->plID = $branch->id;
                        $itemSave->brand = $item['categorybrand'];
                        $itemSave->boxNumber = $item['boxNumber'];
                        $itemSave->boxLabel = $box['boxLabel'];
                        $itemSave->itemCode = $item['code'];
                        $itemSave->quantity = $item['quantity'];
                        $itemSave->amount = $amount;
                        $itemSave->status = $request->status;
                        $itemSave->dateTime = $date;

                        //SAVING
                        $itemSave->save();

                        //TABLE AFFECTED FOR LOGS
                        $table_affected = $request->companyType == 'EPC' || $request->companyType == 'AHLC' ? 'pullOutBranchTbl' : 'pullOutBranchTblNBFi';

                        //FIELDS FOR NEW DATA FOR LOGS
                        $new_data = array(
                            'ItemCode' => $item['code'],
                            'BoxNumber' => $box['boxLabel'],
                            'Quantity' => $item['quantity'],
                            'Amount' => $amount
                        );

                        //FIELDS FOR TRANSACTION LOGS
                        $log = new TransactionModel();
                        $log->dateTime = $date;
                        $log->userID = $dataID;
                        $log->action_type = 'insert';
                        $log->table_affected = $table_affected;
                        $log->new_data = json_encode($new_data);

                        //SAVING
                        $log->save();
                    }
                }
            }


            return response()->json($branch->id);

    }

    public function savePullOutItemRequest(Request $request){

            if($request->quantity != 0 || $request->status == 'draft'){
                //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
            if($request->companyType == 'EPC' || $request->companyType == 'AHLC'){
                $item = new PullOutItemModel();
                $price = DB::table('epc_items_barcode')
                            ->select('EffectivePrice')
                            ->where('ItemNo', '=', $request->itemCode)
                            ->first();
                $table_affected = 'pullOutItemsTbl';
            }
            else if($request->companyType == 'NBFI' || $request->companyType == 'CMC' || $request->companyType == 'ASC'){
                $item = new PullOutItemModelNBFI();
                $price = DB::table('nbfi_items_barcode')
                            ->select('EffectivePrice')
                            ->where('ItemNo', '=', $request->itemCode)
                            ->first();
                $table_affected = 'pullOutItemsTblNBFI';
            }

            //COMPUTATION TOTAL AMOUNT
            $amount = floatval($price->EffectivePrice) * floatval($request->quantity);

            $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

            $item->plID = $request->plID;
            $item->brand = $request->brand;
            $item->boxNumber = $request->boxNumber;
            $item->boxLabel = $request->boxLabel;
            $item->itemCode = $request->itemCode;
            $item->quantity = $request->quantity;
            $item->amount = $amount;
            $item->status = $request->status;
            $item->dateTime = $date;

            //SAVING
            $item->save();

            $dataID = DB::table('users')
                        ->select('id')
                        ->where('email', $request->email)
                        ->first();



            $log = new TransactionModel();
            $log->dateTime = $date;
            $log->userID = $dataID->id;
            $log->action_type = 'insert';
            $log->table_affected = $table_affected;
            $log->new_data = json_encode($request->all());
            $log->save();
        }else{
            $item = [];
        }

        return response()->json($item);

    }

    public function addNewBranch(Request $request){

        if($request->company == "EPC")
        $input = new EpcBranchModel();
        else
        $input = new NbfiBranchModel();

        $input->chainCode = $request->chainCode;
        $input->branchCode = strtoupper($request->branchCode);
        $input->branchName = strtoupper($request->branchName);
        $input->company = $request->companyType;
        $input->status = 'Active';

        $input->save();

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = $request->company == "EPC" ? 'epcbranchmaintenance' : 'nbfibranchmaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($input);
    }

    public function addNewBrand(Request $request){

        if($request->company == "EPC"){
            $data = EpcBrandModel::orderBy('id', 'desc')->pluck('id');
            $input = new EpcBrandModel();
            $table_affected = 'epcbrandsmaintenance';

        }else if($request->company == "NBFI"){
            $data = NbfiBrandModel::orderBy('id', 'desc')->pluck('id');
            $input = new NbfiBrandModel();
            $table_affected = 'nbfibrandsmaintenance';

        }

        $id = $data + 1;

        $input->id = $id;
        $input->brandNames = strtoupper($request->brandName);
        $input->status = 'Y';

        $input->save();

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = $table_affected;
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($input);
    }

    public function addNewDriver(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $input = new EpcDriverModel();
        $input->name = ucwords($request->name);
        $input->position = $request->position;
        $input->dateTime = $date;
        $input->status = 'Active';

        $input->save();

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = 'drivermaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($input);
    }

    public function addNewReason(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $input = new EpcReasonModel();
        $input->reasonLabel = ucfirst($request->reasonLabel);
        $input->dateTime = $date;
        $input->status = 'Active';

        $input->save();

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = 'reasonmaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($input);
    }



    public function generateReport(Request $request){

        $name = $request->name;
        $company = $request->company;
        $date = $request->date;

        $title = 'Pull Out Letter';

        $meta = [
            'Name' => $name,
            'Company' => $company,
            'Date' => $date
        ];

        $queryBuilder = DB::table('pullOutTbl as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.id')
                        ->select(DB::raw(('(SELECT shortName FROM companyTbl WHERE id = a.company) as company')),'a.plID', 'a.chainCode', 'a.branchName', 'a.amount',
                        'a.brand', 'a.transactionType', DB::raw('CAST(a.dateTime AS DATE) as date'),
                        DB::raw('TIME(dateTime) as time'))
                        ->distinct()
                        ->get();

        $columns = [
            'Company' => 'company',
            'Chain Code' => 'chainCode',
            'Branch Name' => 'branchName',
            'Amount' => 'amount'
        ];

        return PdfReport::of($title, $meta, $queryBuilder, $columns)->stream();

    }

    public function deleteDraft(Request $request){

        if($request->company == "NBFI" || $request->company == "ASC" || $request->company == "CMC")
            $draft = DB::table('pullOutBranchTblNBFI')->where('id', $request->id)->delete();
        else if($request->company == "EPC" || $request->company == "AHLC")
            $draft = DB::table('pullOutBranchTbl')->where('id', $request->id)->delete();

        return response()->json(['message'=>'Success'], 200);
    }

    public function deleteUserBranch(Request $request){
        if($request->req == 'additional'){
            DB::table('userBranchMaintenance')->where('userID', $request->userID)->where('request', 'additional')
            ->where(function ($query) use ($request) {
                $query->where('company', $request->company)
                    ->where('chainCode', $request->chainCode)
                    ->where('branchName', $request->branchName);
            })->delete();
        } else if($request->req == 'remove') {
            DB::select("UPDATE userBranchMaintenance SET request = null WHERE userID = \"".$request->userID."\" AND company = \"".$request->company."\" AND chainCode = \"".$request->chainCode."\" AND branchName = \"".$request->branchName."\"");
        }
    }

    public function postUserBranch(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        // $dateEnd = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateEnd'))
        //             ->addDay(); // Add one day to the dateEnd

        // $dateEnd = Carbon::parse($request->input('dateEnd'))->format('Y-m-d');

        $promo = new UserBranchModel();

        $promo->userID = $request->userID;
        $promo->company = $request->company;
        $promo->chainCode = $request->chainCode;
        $promo->branchName = $request->branchName;
        $promo->created_date = $date;
        // $promo->date_end = $dateEnd;
        $promo->status = 'Activated';
        $promo->permanent = true;


        $promo->save();

        switch ($request->company) {
                case "NBFI":
                $company = 1;
                break;
                case "ASC":
                $company = 2;
                break;
                case "CMC":
                $company = 3;
                break;
                case "EPC":
                $company = 4;
                break;
                case "AHLC":
                $company = 5;
                break;

                default:
                break;
            }

        DB::table('users')
            ->where('id', $request->userID)
            ->update([
                'status' => 'Active',
                'company' => $company
            ]);

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->user;
        $log->action_type = 'insert';
        $log->table_affected = 'userBranchMaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($promo);
    }

    public function upload(Request $request)
    {


        $branchName = str_replace(' ', '-', $request->branchName);

        if($request->company == "NBFI" || $request->company == "ASC" || $request->company == "CMC"){
            if ($request->image) {
                $image = $request->image;
                $imageName = $request->transactionID . '-' . $branchName . '-' . time() . '.' . $image->getClientOriginalExtension();

                $filePath = public_path('uploads/NBFI');

                $img = Image::make($image->path());
                $img->resize(1280, 720, function ($const) {
                    $const->aspectRatio();
                })->save($filePath.'/'.$imageName);

                // $image->move($filePath, $imageName);

                $docImage = new ImageBranchModel();
                $docImage->transactionID = $request->transactionID;
                $docImage->company = "NBFI";
                $docImage->path = $imageName;

                $docImage->save();

                return $imageName;
            }
        }else if ($request->company == "EPC" || $request->company == "AHLC"){
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $request->transactionID . '-' . $branchName . '-' . time() . '.' . $image->getClientOriginalExtension();


                $filePath = public_path('uploads/EPC');

                $img = Image::make($image->path());
                $img->resize(1280, 720, function ($const) {
                    $const->aspectRatio();
                })->save($filePath.'/'.$imageName);

                // $image->move(public_path('uploads/EPC'), $imageName);

                $docImage = new ImageBranchModel();
                $docImage->transactionID = $request->transactionID;
                $docImage->company = "EPC";
                $docImage->path = $imageName;

                $docImage->save();

                return $imageName;
            }
        }
        return "No Image found";
    }

    public function postRemarks(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $remarks = new RemarksModel();

        $remarks->remarksName = $request->remarksName;
        $remarks->date = $date;

        $remarks->save();

        return response()->json($remarks);
    }

    public function postPromoUserBranch(Request $request){
        // print_r($request->all());
        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        // if($request->req === "remove")
        //     DB::select("UPDATE userBranchMaintenance SET request = 'remove' WHERE id = \"".$request->id."\"");

        // else if ($request->req === "additional"){
            $promoBranch = new UserBranchModel();
            $promoBranch->userID = $request->userID;
            $promoBranch->company = $request->company;
            $promoBranch->chainCode = $request->chainCode;
            $promoBranch->branchName = $request->branchName;
            $promoBranch->date_start = $request->date_start;
            $promoBranch->date_end = $request->date_end;
            $promoBranch->created_date = $date;
            $promoBranch->request = $request->req;
            $promoBranch->status = 'Activated';
            $promoBranch->permanent = false;

            $promoBranch->save();
        // }

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = 'userBranchMaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();


    }

    public function postDatesLetter(Request $request){

        // $dateStart = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateStarted'))
        //             ->addDay(); // Add one day to the dateStart

        // $dateEnd = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateEnded'))
        //             ->addDay(); // Add one day to the dateEnd

        if($request->status != "approved"){
            $letterDate = new pullOutLetterDates();
            $letterDate->plID = $request->id;
            $letterDate->company = $request->company;
            $letterDate->authorizedPersonnel = $request->authorizedPersonnel;
            $letterDate->dateStart = $request->dateStarted;
            $letterDate->dateEnd = $request->dateEnded;

            $letterDate->save();
        }else{
            DB::table('pullOutLetterDates')
                ->where([
                    ['plID', $request->id],
                    ['company', $request->company],
                ])
                ->update([
                    'dateStart' => $request->dateStarted,
                    'dateEnd' => $request->dateEnded,
                    'authorizedPersonnel' => $request->authorizedPersonnel,
                ]);
        }


        return response()->json($request->all());

    }

    public function logsDeleteItemEdit(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'delete';
        $log->table_affected = 'Items';
        $log->new_data = json_encode($request->all());
        $log->save();
    }

    public function deleteImage(Request $request){

        $company = $request->company;

        if ($company == "ASC" || $company == "CMC" || $company == "NBFI") {
            $fileToDelete = public_path('uploads/NBFI/' . $request->path);
            $company = "NBFI";
        } else {
            $fileToDelete = public_path('uploads/EPC/' . $request->path);
            $company = "EPC";
        }

        if (File::exists($fileToDelete)) {
            File::delete($fileToDelete);
            // Optionally, delete the associated database record.
            // Return a success response.
            DB::table('imageBranchDocTbl')
            ->where('path', $request->path)
            ->where('company', $company) // Add this line to filter by company
            ->delete();
            return response()->json('Success Delete');
        } else {
            // File doesn't exist, return an error response.
            return response()->json($fileToDelete); // File doesn't exist, return a 404 status.

        }

    }

}
