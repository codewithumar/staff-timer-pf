<?php

namespace App\Http\Controllers;

use App\Models\WorkersRecord;
use Illuminate\Http\Request;

class WorkersRecordController extends Controller
{
    //
    public static function  RecordWorkersData($dataToSave)
    {
        // $dataToSave = [
        //     'user_id' => 2,
        //     'total_hours_in_office' => 0,
        //     'attendance' => 'Absent',
        //     'Total' => 4.5,
        // ];

        // Save the data to the workersrecord table
        WorkersRecord::create($dataToSave);

        // Optionally, you can return a response or perform other actions
        return response()->json(['message' => 'Data saved successfully']);
    }
}
