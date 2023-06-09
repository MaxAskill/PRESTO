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

        if($request->company == "EPC"){
            $old_data = PullOutItemModel::find($request->id);

            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutItemsTbl SET editedBy = \''.$request->name.'\', updated_at = \''.$date.'\', status = "edited"  WHERE id = \''.$request->id.'\' ');

            $itemCode = DB::table('pullOutItemsTbl')
                        ->select('itemCode')
                        ->where('id', $request->id)
                        ->first();

            //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
            $price = DB::table('epc_items')
                        ->select('EffectivePrice')
                        ->where('ItemNo', '=', $itemCode->itemCode)
                        ->first();

            //COMPUTATION TOTAL AMOUNT
            $amount = floatval($price->EffectivePrice) * floatval($request->quantity);

            $data = DB::select('UPDATE pullOutItemsTbl SET quantity = \''.$request->quantity.'\', amount = \''.$amount.'\'  WHERE id = \''.$request->id.'\' ');

            $table_affected = 'pullOutItemsTbl';
        } else if($request->company == "NBFI"){
            $old_data = PullOutItemModelNBFI::find($request->id);

            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutItemsTblNBFI SET editedBy = \''.$request->name.'\', updated_at = \''.$date.'\', status = "edited"  WHERE id = \''.$request->id.'\' ');

            $itemCode = DB::table('pullOutItemsTblNBFI')
                        ->select('itemCode')
                        ->where('id', $request->id)
                        ->first();

            //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
            $price = DB::table('nbfi_items')
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

        return response()->json($data);
    }

    public function updateStatus(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->first()->name;
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

        if($request->company == "EPC"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutBranchTbl SET status = \''.$request->status.'\', editedBy = \''.$request->name.'\', updated_at = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');
            $old_data = PullOutBranchModel::find($request->id);
            $table_affected = 'pullOutItemsTbl';

        } else if($request->company == "NBFI"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutBranchTblNBFI SET status = \''.$request->status.'\', editedBy = \''.$request->name.'\', updated_at = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');
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

        if($request->companyType == "NBFI" || $request->companyType == "CMC" || $request->companyType == "ASC"){
            $data = DB::select('UPDATE pullOutBranchTblNBFI
                                SET chainCode = \''.$request->chainCode.'\',
                                company = \''.$request->companyType.'\',
                                branchName = \''.$request->branchName.'\',
                                transactionType = \''.$request->transactionType.'\',
                                status = \''.$request->status.'\' WHERE id = \''.$request->id.'\'');
        } else if($request->companyType == "EPC" || $request->companyType == "AHLC"){
            $data = DB::select('UPDATE pullOutBranchTbl
                                SET chainCode = \''.$request->chainCode.'\',
                                company = \''.$request->companyType.'\',
                                branchName = \''.$request->branchName.'\',
                                transactionType = \''.$request->transactionType.'\',
                                status = \''.$request->status.'\' WHERE id = \''.$request->id.'\'');
        }

        return response()->json($data);
    }

    public function updatePullOutItemRequest(Request $request){

        if($request->companyType == "NBFI" || $request->companyType == "CMC" || $request->companyType == "ASC"){
            $data = DB::select('DELETE FROM pullOutItemsTblNBFI
                                WHERE plID = \''.$request->plID.'\'');
            $item = new PullOutItemModelNBFI();
            $price = DB::table('nbfi_items')
                        ->select('EffectivePrice')
                        ->where('ItemNo', '=', $request->itemCode)
                        ->first();



        }else if ($request->companyType == "EPC" || $request->companyType == "AHLC"){
            $data = DB::select('DELETE FROM pullOutItemsTbl
                                WHERE plID = \''.$request->plID.'\'');

            $item = new PullOutItemModel();
            $price = DB::table('epc_items')
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
        $item->status = $request->status;
        $item->dateTime = $date;

        //SAVING
        $item->save();

        return response()->json($item);
    }
}
