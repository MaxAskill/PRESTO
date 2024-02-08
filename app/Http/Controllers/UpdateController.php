<?php

namespace App\Http\Controllers;

use App\Models\PullOutModel;
use App\Models\PullOutItemModel;
use App\Models\PullOutBranchModel;
use App\Models\PullOutItemModelNBFI;
use App\Models\PullOutBranchModelNBFI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TransactionModel;

class UpdateController extends Controller
{
    //

    public function updateItemQuantity(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->pluck('name');

        if($request->company == "EPC"){
            $old_data = PullOutItemModel::find($request->id);

            if($old_data->quantity == $request->quantity && $old_data->boxLabel == $request->boxLabel){
                    //setting the name who edit the item
                    $editBy = DB::select('UPDATE pullOutItemsTbl SET editedBy = \''.$name.'\',
                    updated_at = \''.$date.'\', boxNumber = \''.$request->boxNumber.'\',
                    boxLabel = \''.$request->boxLabel.'\', status = "edited"  WHERE id = \''.$request->id.'\' ');
            }


            $itemCode = DB::table('pullOutItemsTbl')
                        ->select('itemCode')
                        ->where('id', $request->id)
                        ->first();

            //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
            $price = DB::table('epc_items_barcode')
                        ->select('EffectivePrice')
                        ->where('ItemNo', '=', $itemCode->itemCode)
                        ->first();

            //COMPUTATION TOTAL AMOUNT
            $amount = floatval($price->EffectivePrice) * floatval($request->quantity);

            $data = DB::select('UPDATE pullOutItemsTbl SET quantity = \''.$request->quantity.'\', amount = \''.$amount.'\'  WHERE id = \''.$request->id.'\' ');

            $table_affected = 'pullOutItemsTbl';
        } else if($request->company == "NBFI"){
            $old_data = PullOutItemModelNBFI::find($request->id);

            if($old_data->quantity == $request->quantity && $old_data->boxLabel == $request->boxLabel){

                    //setting the name who edit the item
                    $editBy = DB::select('UPDATE pullOutItemsTblNBFI SET editedBy = \''.$name.'\',
                    updated_at = \''.$date.'\', boxNumber = \''.$request->boxNumber.'\',
                    boxLabel = \''.$request->boxLabel.'\', status = "edited"  WHERE id = \''.$request->id.'\' ');

            }
            $itemCode = DB::table('pullOutItemsTblNBFI')
                        ->select('itemCode')
                        ->where('id', $request->id)
                        ->first();

            //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
            $price = DB::table('nbfi_items_barcode')
                        ->select('EffectivePrice')
                        ->where('ItemNo', '=', $itemCode->itemCode)
                        ->first();

            //COMPUTATION TOTAL AMOUNT
            $amount = floatval($price->EffectivePrice) * floatval($request->quantity);

            $data = DB::select('UPDATE pullOutItemsTblNBFI SET quantity = \''.$request->quantity.'\', amount = \''.$amount.'\'  WHERE id = \''.$request->id.'\' ');

            $table_affected = 'pullOutItemsTblNBFI';
        }

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'update';
        $log->table_affected = $table_affected;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($request->boxLabel);
    }

    public function updateStatus(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->pluck('name');
        if($request->company == "EPC"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutItemsTbl SET editedBy = \''.$name.'\', updated_at = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');

            //setting the status per item
            $data = DB::select('UPDATE pullOutItemsTbl SET status = \''.$request->status.'\'  WHERE id = \''.$request->id.'\' ');

            $old_data = PullOutItemModel::find($request->id);
            $table_affected = 'pullOutItemsTbl';
        } else if($request->company == "NBFI"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutItemsTblNBFI SET editedBy = \''.$name.'\', updated_at = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');

            //setting the status per item
            $data = DB::select('UPDATE pullOutItemsTblNBFI SET status = \''.$request->status.'\'  WHERE id = \''.$request->id.'\' ');

            $old_data = PullOutItemModelNBFI::find($request->id);
            $table_affected = 'pullOutItemsTblNBFI';
        }

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'delete';
        $log->table_affected = $table_affected;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json(['message' => 'Success'], 200);
    }

    public function updateBranchStatus(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $name = DB::table('users')
                    ->where('id', $request->userID)
                    ->value('name');
        // SETTING TABLES
        if($request->company == "EPC"){
            $old_data = PullOutBranchModel::find($request->id);
            $tbBranch = 'pullOutBranchTbl';
            $tbItems = 'pullOutItemsTbl';
            $tbItem = 'epc_items_barcode';

        } else if($request->company == "NBFI"){
            $old_data = PullOutBranchModelNBFI::find($request->id);
            $tbBranch = 'pullOutBranchTblNBFI';
            $tbItems = 'pullOutItemsTblNBFI';
            $tbItem = 'nbfi_items_barcode';
        }

        // UPDATING STATUS
        if($request->status == "endorsement")
            $editBy = DB::table($tbBranch)
                            ->where('id', $request->id)
                            ->update([
                                'status' => $request->status,
                                'editedBy' => $name,
                                'updated_at' => $date,
                                'dateTime' => $date,
                            ]);
        else if($request->status == "approved")
            $editBy = DB::table($tbBranch)
                            ->where('id', $request->id)
                            ->update([
                                'status' => $request->status,
                                'approvedBy' => $name,
                                'approvedDate' => $date,
                            ]);
        else if($request->status == "unprocessed")
        $editBy = DB::table($tbBranch)
                        ->where('id', $request->id)
                        ->update([
                            'status' => $request->status,
                        ]);

        $oldData = $old_data->toArray(); // Retrieve the old data before the update
        $newData = array(
            'Status' => $request->status,
            'editBy' => $name,
            );

        // TRANSACTION LOGS
        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'update';
        $log->table_affected = $tbBranch;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($newData);
        $log->save();

        // IF EDIT IS TRUE
        if($request->editStatus){
            $items = $request->transactionData['items']; // SETTING THE ITEMS
            $boxes = $request->transactionData['boxLabels']; // SETTING THE BOX
            $status = $request->status; // SETTING THE STATUS
            foreach($items as $item){  // LOOPING THE ITEMS
                foreach($boxes as $box){ // LOOPING THE BOX
                    if($box['id'] == $item['boxNumber']){ // CONDITION IF THE BOX ID IS EQUAL TO ITEM BOXNUMBER FOR PUTTING BOXLABEL
                        switch($request->company){ // SETTING THE TABLE
                            case "NBFI":
                                $tbItemSave = new PullOutItemModelNBFI();
                                break;
                            case "EPC":
                                $tbItemSave = new PullOutItemModel();
                                break;
                        }

                        if(isset($item['id'])){ // CHECKING IF GOT AN ID FOR UPDATE
                            $old_data = DB::table($tbItems)
                                            ->select('quantity', 'boxLabel')
                                            ->where('id', $item['id'])
                                            ->get()
                                            ->first();

                            if($old_data->quantity != $item['quantity'] || $old_data->boxLabel != $item['boxLabel'])
                                $status = "edited";

                            DB::table($tbItems)->where('id', $item['id'])->delete(); // DELETE THE ITEM FOR REPLACEMENT
                        }

                        // GETTING THE PRICE
                        $price = DB::table($tbItem)
                                    ->select('EffectivePrice')
                                    ->where('ItemNo', '=', $item['code'])
                                    ->first();

                        // SETTING THE TOTAL AMOUNT
                        $amount = floatval($price->EffectivePrice) * floatval($item['quantity']);

                        // SETTING THE VARIABLES
                        $tbItemSave->plID = $request->id;
                        $tbItemSave->brand = $item['categorybrand'];
                        $tbItemSave->boxNumber = $item['boxNumber'];
                        $tbItemSave->boxLabel = $box['boxLabel'];
                        $tbItemSave->itemCode = $item['code'];
                        $tbItemSave->quantity = $item['quantity'];
                        $tbItemSave->amount = $amount;
                        $tbItemSave->status = $status;
                        $tbItemSave->dateTime = $date;

                        // SAVING
                        $tbItemSave->save();
                    }
                }
            }

            // DELETING ITEMS
            DB::table($tbItems)
                    ->whereIn('id', $request->removedItems)
                    ->delete();
            return response()->json($items);
        }

    }

    public function updateBranchStatusApprover(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->pluck('name');

        if($request->company == "EPC"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutBranchTbl SET status = \''.$request->status.'\', approvedBy = \''.$name.'\', approvedDate = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');
            $old_data = PullOutBranchModel::find($request->id);
            $table_affected = 'pullOutItemsTbl';

        } else if($request->company == "NBFI"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutBranchTblNBFI SET status = \''.$request->status.'\', approvedBy = \''.$name.'\', approvedDate = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');
            $old_data = PullOutBranchModelNBFI::find($request->id);
            $table_affected = 'pullOutItemsTblNBFI';
        }

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'update';
        $log->table_affected = $table_affected;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json(['message' => 'Success'], 200);
    }

    public function updatePullOutBranchRequest(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        if($request->companyType == "NBFI" || $request->companyType == "CMC" || $request->companyType == "ASC"){
            $data = DB::select('UPDATE pullOutBranchTblNBFI
                                SET chainCode = \''.$request->chainCode.'\',
                                company = \''.$request->companyType.'\',
                                branchName = \''.$request->branchName.'\',
                                transactionType = \''.$request->transactionType.'\',
                                status = \''.$request->status.'\', dateTime = \''.$date.'\' WHERE id = \''.$request->id.'\'');

            // if($request->status == 'draft')
            // DB::select('DELETE FROM pullOutItemsTblNBFI WHERE plID = \''.$request->id.'\'');
        } else if($request->companyType == "EPC" || $request->companyType == "AHLC"){
            $data = DB::select('UPDATE pullOutBranchTbl
                                SET chainCode = \''.$request->chainCode.'\',
                                company = \''.$request->companyType.'\',
                                branchName = \''.$request->branchName.'\',
                                transactionType = \''.$request->transactionType.'\',
                                status = \''.$request->status.'\', dateTime = \''.$date.'\' WHERE id = \''.$request->id.'\'');

            // if($request->status == 'draft')
            // DB::select('DELETE FROM pullOutItemsTbl WHERE plID = \''.$request->id.'\'');
        }

        return response()->json($data);
    }

    public function updatePullOutItemRequest(Request $request){

        $status = $request->status;

        if($request->companyType == "NBFI" || $request->companyType == "CMC" || $request->companyType == "ASC"){

            // $old_data = PullOutItemModelNBFI::find($request->id);

            $old_data = DB::table('pullOutItemsTblNBFI')
                            ->select('quantity', 'boxLabel')
                            ->where('id', $request->id)
                            ->get()
                            ->first();

            if($request->status != "draft"){
                if($old_data->quantity != $request->quantity || $old_data->boxLabel != $request->boxLabel){
                    $status = "edited";
                }
            }


            DB::table('pullOutItemsTblNBFI')->where('id', $request->id)->delete();

            $item = new PullOutItemModelNBFI();
            $price = DB::table('nbfi_items_barcode')
                        ->select('EffectivePrice')
                        ->where('ItemNo', '=', $request->itemCode)
                        ->first();



        }else if ($request->companyType == "EPC" || $request->companyType == "AHLC"){

            // $old_data = PullOutItemModel::find($request->id);

                $old_data = DB::table('pullOutItemsTbl')
                            ->select('quantity', 'boxLabel')
                            ->where('id', $request->id)
                            ->get()
                            ->first();

            if($request->id != null){
                if($request->status != "draft"){
                    if($old_data->quantity != $request->quantity || $old_data->boxLabel != $request->boxLabel){
                        $status = "edited";
                    }
                }
            }



            DB::table('pullOutItemsTbl')->where('id', $request->id)->delete();

            $item = new PullOutItemModel();
            $price = DB::table('epc_items_barcode')
                        ->select('EffectivePrice')
                        ->where('ItemNo', '=', $request->itemCode)
                        ->first();
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
        $item->status = $status;
        $item->dateTime = $date;

        //SAVING
        $item->save();

        return response()->json($request->quantity);
    }

    public function updateUserBranch(Request $request){
        $status = ($request->status == "Deactivated") ? 'Inactive' : 'Active';

        DB::table('users')
            ->where('id', $request->userID)
            ->update(['status' => $status]);

        DB::table('userBranchMaintenance')
            ->where('userID', $request->userID)
            ->update(['status' => $request->status]);
    }

    public function updateUserBranchByRequest(Request $request){
        // $date = now()->timezone('Asia/Manila');
        // $dateStart = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateStart'))
        //             ->addDay();
        // $dateEnd = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateEnd'))
        //             ->addDay();
        // DB::table('userBranchMaintenance')->where('id', $request->id)->where('request', 'remove')->delete();
        // DB::select("UPDATE userBranchMaintenance SET request = null, date_start = \"".$request->dateStart."\", date_end = \"".$request->dateEnd."\" WHERE id = \"".$request->id."\" AND request = 'additional'");
        DB::table('userBranchMaintenance')
                ->where('id', $request->id)
                ->where('request', 'remove')
                ->delete();
        DB::table('userBranchMaintenance')
                ->where('id', $request->id)
                ->where('request', 'additional')
                ->update([
                    'request' => null,
                    'date_start' => $request->dateStart,
                    'date_end' => $request->dateEnd
                ]);

    }

    public function removeTempBranch(Request $request){
        $data = DB::table('userBranchMaintenance')
                    ->where('id', $request->id)
                    ->update(['expired' => 'removed']);

        return response()->json($data);
    }

    public function updateTempBranch(Request $request){
        $data = DB::table('userBranchMaintenance')
                    ->where('id', $request->id)
                    ->update(['date_start' => $request->startDate, 'date_end' => $request->endDate]);

        return response()->json($data);
    }

    public function updatePermBranch(Request $request){
        DB::table('userBranchMaintenance')
            ->where('company', '=', $request->company)
            ->where('chainCode', '=', $request->chainCode)
            ->where('branchName', '=', $request->branchName)
            ->where('expired', '=', null)
            ->delete();

        $data = DB::table('userBranchMaintenance')
                    ->where('id', $request->id)
                    ->update(['company' => $request->company, 'chainCode' => $request->chainCode, 'branchName' => $request->branchName]);

        return response()->json($data);
    }
}
