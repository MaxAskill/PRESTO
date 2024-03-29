<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TransactionModel;
use App\Models\User;
use App\Models\EpcBranchModel;
use App\Models\NbfiBranchModel;
use App\Models\EpcBrandModel;
use App\Models\NbfiBrandModel;
use App\Models\EpcDriverModel;
use App\Models\EpcReasonModel;

class UpdateAdminController extends Controller
{
    //

    public function updateUserAcc(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $data = DB::table('users')
                    ->where('id', $request->id)
                    ->update([
                        'position' => $request->position,
                        'status' => $request->status,
                        'updated_at' => $date
                    ]);
        $old_data = User::find($request->id);

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'update';
        $log->table_affected = 'users';
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($data);

    }

    public function updateBranch(Request $request){


        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        if($request->company == "EPC"){
            $old_data = EpcBranchModel::find($request->id);
            $table_affected = 'epcbranchmaintenance';
        }
        else{
            $old_data = NbfiBranchModel::find($request->id);
            $table_affected = 'nbfibranchmaintenance';
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



        $data = DB::table($table_affected)
                    ->where('id', $request->id)
                    ->update(['status' => $request->status]);
        return response()->json($data);
    }

    public function updateBrand(Request $request){


        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        if($request->company == "EPC"){
            $old_data = EpcBrandModel::find($request->id);
            $table_affected = 'epcbrandsmaintenance';
        }else{
            $old_data = NbfiBrandModel::find($request->id);
            $table_affected = 'nbfibrandsmaintenance';
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

        $data = DB::table($table_affected)
                    ->where('id', $request->id)
                    ->update(['status' => $request->status]);

        return response()->json($data);
    }

    public function updateDriver(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $old_data = EpcDriverModel::find($request->id);

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'update';
        $log->table_affected = 'driverMaintenance';
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        $data = DB::table('driverMaintenance')
                    ->where('id', $request->id)
                    ->update([
                        'status' => $request->status,
                        'updated_at' => $date
                    ]);


        return response()->json($data);
    }

    public function updateReason(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $old_data = EpcReasonModel::find($request->id);

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'update';
        $log->table_affected = 'reasonMaintenance';
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        $data = DB::table('reasonMaintenance')
                    ->where('id', $request->id)
                    ->update([
                        'status' => $request->status,
                        'updated_at' => $date
                    ]);

        return response()->json($data);
    }
}
