<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PullOutModel;
use App\Models\PullOutBranchModel;
use App\Models\PullOutItemModel;

class FetchController extends Controller
{
    //

    //GETTING THE LAST ID IN THE PULL OUT TBL
    public function fetchLastID(){
        $data = PullOutModel::orderBy('plID', 'desc')->first()->plID;

        return response()->json($data);
    }

    public function fetchLastIDBranch(){
        $data = PullOutBranchModel::orderBy('id', 'desc')->first()->id;

        return response()->json($data);
    }

    public function fetchChain(Request $request){

        $company = $request->company;
        // $data = DB::table('epcbranchmaintenance')
        //             ->select('chainCode')
        //             ->distinct()
        //             ->orderby('chainCode')
        //             ->get();
        if($company == "EPC"){
            $data = DB::table('epcbranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'EPC')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }else if($company == "AHLC"){
            $data = DB::table('epcbranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'AHLC')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }else if($company == "ASC"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'ASC')
                    ->orWhere('chainCode', 'SM DEPT. STORE')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }else if($company == "CMC"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'CMC')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }else if($company == "NBFI"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'NBFI')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchChainName(Request $request){

        $company = $request->company;

        $data = DB::table('epcbranchmaintenance')
                ->select('branchName')
                ->where('chainCode', $request->chainCode)
                ->where('status', 'Active')
                ->distinct()
                ->orderby('branchName')
                ->get();
        if($company == "EPC"){
            $data = DB::table('epcbranchmaintenance')
                ->select('branchName')
                ->where('chainCode', $request->chainCode)
                ->where('status', 'Active')
                ->distinct()
                ->orderby('branchName')
                ->get();
        }else if($company == "AHLC"){
            $data = DB::table('epcbranchmaintenance')
                ->select('branchName')
                ->where('chainCode', $request->chainCode)
                ->where('status', 'Active')
                ->distinct()
                ->orderby('branchName')
                ->get();
        }else if($company == "ASC"){
            $data1 = DB::table('epcbranchmaintenance')
                    ->select('branchName')
                    ->where('chainCode', $request->chainCode)
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('branchName')
                    ->get();

            $data2 = DB::table('epcbranchmaintenance')
                    ->select('branchName')
                    ->where('chainCode', $request->chainCode)
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('branchName')
                    ->get();
            $data = $data2->union($data1);
        }else if($company == "CMC"){
            $data = DB::table('epcbranchmaintenance')
                    ->select('branchName')
                    ->where('chainCode', $request->chainCode)
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('branchName')
                    ->get();
        }else if($company == "NBFI"){
            $data = DB::table('epcbranchmaintenance')
                    ->select('branchName')
                    ->where('chainCode', $request->chainCode)
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('branchName')
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchBrands(Request $request){

        // $chainCode = request('chainCode');
        if($request->companyType == 'EPC' || $request->companyType == 'AHLC')
            $data = DB::table('epcbrandsmaintenance')
                    ->select('brandNames')
                    ->where('id', $request->brandCode)
                    ->where('status', 'Y')
                    ->distinct()
                    ->orderby('brandNames')
                    ->get();
        else if($request->companyType == 'NBFI' || $request->companyType == 'CMC' || $request->companyType == 'ASC')
            $data = DB::table('nbfibrandsmaintenance')
                    ->select('brandNames')
                    ->where('id', $request->brandCode)
                    ->where('status', 'Y')
                    ->distinct()
                    ->orderby('brandNames')
                    ->get();

        return response()->json($data);
    }

    public function fetchCategory(Request $request){

        $data = DB::table('epcbrandscategory')
                ->select('categoryName')
                ->where('brandName', $request->brandName)
                ->distinct()
                ->orderby('categoryName')
                ->get();

        return response()->json($data);
    }

    public function fetchBranch(){
        $data = DB::table('branch_maintenance')
                ->select('branchCode', 'branchName')
                ->get();

        return response()->json($data);
    }

    public function fetchItems(Request $request){
        $data1 = DB::table('epc_items')
                ->select('ItemNo', 'ItemDescription')
                ->where('ItemNo', 'LIKE', '%'.$request->ItemNo)
                ->get();

        $data2 = DB::table('epc_items')
                ->select('ItemNo', 'ItemDescription')
                ->where('ItemNo', 'LIKE', $request->ItemNo.'%')
                ->get();

        $data = $data1->union($data2);

        return response()->json($data);
    }

    public function compareItemCode(Request $request){
        if($request->companyType == 'EPC' || $request->companyType == 'AHLC')
            $data = DB::table('epc_items')
                    ->select('ItemNo', 'ItemDescription', 'StyleCode')
                    ->where('ItemNo', '=' ,$request->ItemNo)
                    ->get();

        else if($request->companyType == 'NBFI' || $request->companyType == 'CMC' || $request->companyType == 'ASC')
            $data = DB::table('nbfi_items')
                    ->select('ItemNo', 'ItemDescription', 'StyleCode')
                    ->where('ItemNo', '=' ,$request->ItemNo)
                    ->get();

        return response()->json($data);
    }

    public function fetchItemsNBFI(Request $request){
        $data1 = DB::table('nbfi_items')
                ->select('ItemNo', 'ItemDescription')
                ->where('ItemNo', 'LIKE', '%'.$request->ItemNo)
                ->get();

        $data2 = DB::table('nbfi_items')
                ->select('ItemNo', 'ItemDescription')
                ->where('ItemNo', 'LIKE', $request->ItemNo.'%')
                ->get();

        $data = $data1->union($data2);

        return response()->json($data);
    }

    public function fetchPullOutRequest(){

        // $data = DB::table('pullOutTbl')
        //         ->select('plID', 'chainCode', 'branchName',
        //         'brand', 'transactionType', 'dateTime')
        //         ->distinct()
        //         ->get();
        // $data = DB::table('pullOutTbl as a')
        //         ->join('companyTbl as b', 'a.company', '=', 'b.id')
        //         ->select(DB::raw(('(SELECT shortName FROM companyTbl WHERE id = a.company) as company')),'a.plID', 'a.chainCode', 'a.branchName', 'a.amount',
        //         'a.brand', 'a.transactionType', DB::raw('CAST(a.dateTime AS DATE) as date'),
        //         DB::raw('TIME(dateTime) as time'))
        //         ->distinct()
        //         ->get();

        $data = DB::table('pullOutTbl as a')
                ->join('companyTbl as b', 'a.company', '=', 'b.id')
                ->select('a.plID','a.branchName', 'a.transactionType',
                DB::raw('CAST(a.dateTime AS DATE) as date'),
                DB::raw('TIME(dateTime) as time'))
                ->distinct()
                ->where('status', '!=', 'deleted')
                ->orderBy('a.dateTime', 'desc')
                ->get();

        return response()->json($data);
    }

    public function fetchPullOutRequestUnprocessed(Request $request){

        if($request->company == "EPC" || $request->company == "AHLC"){
            $data = DB::table('pullOutBranchTbl as a')
                ->join('users as b', 'a.promoEmail', '=', 'b.email')
                ->select('a.id as plID','a.branchName', 'a.transactionType',
                DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail')
                ->distinct()
                ->where('a.status', 'unprocessed')
                ->orderBy('a.dateTime', 'desc')
                ->get();
        }else if($request->company == "NBFI" || $request->company == "ASC" || $request->company == "CMC"){
            $data = DB::table('pullOutBranchTblNBFI as a')
                ->join('users as b', 'a.promoEmail', '=', 'b.email')
                ->select('a.id as plID','a.branchName', 'a.transactionType',
                DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail')
                ->distinct()
                ->where('a.status', 'unprocessed')
                ->orderBy('a.dateTime', 'desc')
                ->get();
        }

        return response()->json($data);
    }

    public function fetchPullOutRequestUnprocessed2(){

        $data = DB::table('pullOutBranchTbl as a')
                ->join('companyTbl as b', 'a.company', '=', 'b.id')
                ->select('a.id as plID','a.branchName', 'a.transactionType',
                DB::raw('CAST(a.dateTime AS DATE) as date'),
                DB::raw('TIME(dateTime) as time'))
                ->distinct()
                ->where('status', 'unprocessed')
                ->orderBy('a.dateTime', 'desc')
                ->get();

        return response()->json($data);
    }

    public function fetchPullOutRequestItem(Request $request){

        if($request->company == "EPC" || $request->company == "AHLC"){
            $data = DB::table('pullOutItemsTbl')
                    ->select('id','plID', 'boxNumber', 'boxLabel', 'brand', 'itemCode', 'quantity', 'editedBy')
                    ->where('status', '!=', 'deleted')
                    ->where('plID', '=', $request->plID)
                    ->orderBy('boxLabel')
                    ->get();
            return response()->json($data);

        }else if ($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC") {
            $data = DB::table('pullOutItemsTblNBFI')
                    ->select('id','plID', 'boxNumber', 'boxLabel', 'brand', 'itemCode', 'quantity', 'editedBy')
                    ->where('status', '!=', 'deleted')
                    ->where('plID', '=', $request->plID)
                    ->orderBy('boxLabel')
                    ->get();
            return response()->json($data);

        }

    }

    public function fetchPullOutRequestApproved(Request $request){

        if($request->company == "EPC" || $request->company == "AHLC"){
            $data = DB::table('pullOutBranchTbl as a')
                ->join('users as b', 'a.promoEmail', '=', 'b.email')
                ->select('a.id as plID','a.branchName', 'a.transactionType',
                DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail')
                ->distinct()
                ->where('a.status', 'approved')
                ->orderBy('a.dateTime', 'desc')
                ->get();
        }else if($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC"){
            $data = DB::table('pullOutBranchTblNBFI as a')
                ->join('users as b', 'a.promoEmail', '=', 'b.email')
                ->select('a.id as plID','a.branchName', 'a.transactionType',
                DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail')
                ->distinct()
                ->where('a.status', 'approved')
                ->orderBy('a.dateTime', 'desc')
                ->get();
        }

        return response()->json($data);
    }

    public function fetchPullOutRequestDenied(Request $request){

        if($request->company == "EPC" || $request->company == "AHLC"){
            $data = DB::table('pullOutBranchTbl as a')
                ->select('a.id as plID','a.branchName', 'a.transactionType',
                DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'))
                ->where('status', 'denied')
                ->orderBy('a.dateTime', 'desc')
                ->get();
        }else if($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC"){
            $data = DB::table('pullOutBranchTblNBFI as a')
                ->select('a.id as plID','a.branchName', 'a.transactionType',
                DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'))
                ->where('status', 'denied')
                ->orderBy('a.dateTime', 'desc')
                ->get();
        }

        return response()->json($data);
    }
    public function fetchItemsRequest(Request $request){

        $data = DB::table('pullOutTbl')
                ->select('id','plID', 'boxNumber', 'boxLabel', 'brand', 'itemCode', 'quantity')
                ->where('status', '!=', 'deleted')
                ->where('plID', '=', $request->plID)
                ->orderBy('boxLabel')
                ->get();

        return response()->json($data);
    }
    public function fetchAllItemsRequest(Request $request){

        $id = $request->input('plID');

        if($request->company == "EPC" || $request->company == "AHLC"){
            $data = DB::table('pullOutBranchTbl as a')
                ->join('pullOutItemsTbl as b', 'a.id', '=', 'b.plID')
                ->select('a.branchName', 'b.brand', 'a.transactionType',
                        DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'),'b.boxLabel', 'b.itemCode', 'b.quantity',
                        'a.status')
                ->whereIn('a.id', $id)
                ->get();
        }else if($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC"){
            $data = DB::table('pullOutBranchTblNBFI as a')
                ->join('pullOutItemsTblNBFI as b', 'a.id', '=', 'b.plID')
                ->select('a.branchName', 'b.brand', 'a.transactionType',
                        DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'),'b.boxLabel', 'b.itemCode', 'b.quantity',
                        'a.status')
                ->whereIn('a.id', $id)
                ->get();
        }


        return response()->json($data);
    }

    public function users(Request $request){

        $user = DB::table('users')
                ->select('id', 'position', 'name')
                ->where('email', $request->email)
                ->get();

        return response()->json($user);
    }

    public function getPromoName(Request $request){

        $email = "";
        if ($request->company == "EPC" || $request->company == "AHLC"){
            $email = DB::table('pullOutBranchTbl')
                        ->select('promoEmail')
                        ->where('id', $request->id)
                        ->first();
        } else if($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC"){
            $email = DB::table('pullOutBranchTblNBFI')
                        ->select('promoEmail')
                        ->where('id', $request->id)
                        ->first();
        }


        $name = DB::table('users')
                ->select('name', 'email')
                ->where('email', $email->promoEmail)
                ->first();

        return response()->json($name);
    }

    public function getBranchStatus(Request $request){

        $data = DB::table('pullOutBranchTbl')
                ->select('status')
                ->where('id', $request->id)
                ->get();

        return response()->json($data);
    }

    public function getReasons(Request $request){

        $data = DB::table('reasonMaintenance')
                ->select('id', 'reasonLabel')
                ->where('status', 'Active')
                ->get();

        return response()->json($data);
    }

    public function fetchCompany(Request $request){

        $company = DB::table('companyTbl')
                    ->select('id', 'shortName')
                    ->get();

        return response()->json($company);
    }

    public function fetchUserRequestDraft(Request $request){

        $company = $request->company;
        // if($company == 'NBFI' || $company == 'ASC' || $company == 'CMC'){
            $data1 = DB::table('pullOutBranchTblNBFI as a')
                    // ->join('companyTbl as b', 'a.company', '=', 'b.id')
                    ->select('a.id as plID', 'a.chainCode', 'a.branchName', 'company', 'a.transactionType',
                        DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'))
                    ->where('status', 'draft')
                    ->where('promoEmail', $request->promoEmail)
                    ->orderBy('a.dateTime', 'desc');
        // } else if($company == 'EPC' || $company == 'AHLC'){
            $data2 = DB::table('pullOutBranchTbl as a')
                    // ->join('companyTbl as b', 'a.company', '=', 'b.id')
                    ->select('a.id as plID', 'a.chainCode', 'a.branchName', 'company', 'a.transactionType',
                        DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'))
                    ->where('status', 'draft')
                    ->where('promoEmail', $request->promoEmail)
                    ->orderBy('a.dateTime', 'desc');
        // }
            $data = $data1->union($data2)->get();

        return response()->json($data);
    }

    public function fetchUserRequestTransactionList(Request $request){

        $company = $request->company;
        if($company == 'NBFI' || $company == 'ASC' || $company == 'CMC'){
            $data = DB::table('pullOutBranchTblNBFI as a')
                    // ->join('companyTbl as b', 'a.company', '=', 'b.id')
                    ->select('a.id as plID', 'a.chainCode', 'a.branchName', 'a.company', 'a.transactionType',
                        DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'status')
                    ->where('status', '!=', 'draft')
                    ->where('promoEmail', $request->promoEmail)
                    ->orderBy('a.dateTime', 'desc')
                    ->get();
        } else if($company == 'EPC' || $company == 'AHLC'){
            $data = DB::table('pullOutBranchTbl as a')
                    // ->join('companyTbl as b', 'a.company', '=', 'b.id')
                    ->select('a.id as plID', 'a.chainCode', 'a.branchName', 'a.company', 'a.transactionType',
                        DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'status')
                    ->where('status', '!=', 'draft')
                    ->where('promoEmail', $request->promoEmail)
                    ->orderBy('a.dateTime', 'desc')
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchSameItem(Request $request){

        // $styleCode = substr($request->ItemNo, -3);


        $company = $request->company;
        if($company == "NBFI" || $company == "ASC" || $company == "CMC"){
            $data = DB::table('nbfi_items')
                ->select('ItemNo', 'ItemDescription', 'Size', 'StyleCode')
                ->where('ItemDescription', $request->ItemDescription)
                ->where('StyleCode', $request->StyleCode)
                ->groupBy('ItemNo', 'ItemDescription', 'Size', 'StyleCode')
                ->get();

        }else {
            $data = DB::table('epc_items')
                ->select('ItemNo', 'ItemDescription', 'Size', 'StyleCode')
                ->where('ItemDescription', $request->ItemDescription)
                ->where('StyleCode', $request->StyleCode)
                ->groupBy('ItemNo', 'ItemDescription', 'Size', 'StyleCode')
                ->get();
                }
        return response()->json($data);

    }

    public function fetchChainCode(Request $request){

        $company = $request->company;
        if($company == "NBFI" || $company == "ASC" || $company == "CMC"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('chainCode')
                    ->where('company', $company)
                    ->distinct()
                    ->get();

        }else {
            $data = DB::table('epcbranchmaintenance')
                    ->select('chainCode')
                    ->where('company', $company)
                    ->distinct()
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchEditDraftBranch(Request $request){
        $company = $request->company;
        if($company == 'NBFI' || $company == 'ASC' || $company == 'CMC'){
            $data = DB::table('pullOutBranchTblNBFI')
                    ->select('id', 'chainCode', 'branchName', 'company', 'transactionType',
                        DB::raw('CONCAT(MONTHNAME(dateTime), " ", DATE_FORMAT(dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(dateTime, "%h:%i %p") as time'), 'status')
                    ->where('id', $request->plID)
                    ->get();

        } else if($company == 'EPC' || $company == 'AHLC'){
            $data = DB::table('pullOutBranchTbl as a')
                    ->select('id', 'chainCode', 'branchName', 'company', 'transactionType',
                        DB::raw('CONCAT(MONTHNAME(dateTime), " ", DATE_FORMAT(dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(dateTime, "%h:%i %p") as time'), 'status')
                    ->where('id', $request->plID)
                    ->get();

        }
        return response()->json($data);

    }
    public function fetchEditDraftItem(Request $request){
        $company = $request->company;
        if($company == 'NBFI' || $company == 'ASC' || $company == 'CMC'){
            $data = DB::table('pullOutItemsTblNBFI as a')
                    ->join('nbfi_items as b', 'a.itemCode', '=', 'b.ItemNo')
                    ->select('plID', 'boxNumber', 'boxLabel', 'itemCode as code', 'b.ItemDescription as description', 'a.brand as categorybrand', 'quantity', 'amount', 'status',
                        DB::raw('CONCAT(MONTHNAME(dateTime), " ", DATE_FORMAT(dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(dateTime, "%h:%i %p") as time'))
                    ->where('plID', $request->plID)
                    ->get();
            return response()->json($data);

        } else if($company == 'EPC' || $company == 'AHLC'){
            $data = DB::table('pullOutItemsTbl as a')
                    // ->join('epc_items as b', 'a.itemCode', '=', 'b.ItemNo')
                    ->select('plID', 'boxNumber', 'boxLabel', 'a.itemCode', 'a.brand', 'quantity', 'amount', 'status',
                        DB::raw('CONCAT(MONTHNAME(dateTime), " ", DATE_FORMAT(dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(dateTime, "%h:%i %p") as time'))
                    ->where('plID', $request->plID)
                    ->get();
            return response()->json($data);

        }

    }


}
