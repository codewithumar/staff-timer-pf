<?php

namespace App\Console\Commands;

use App\Http\Controllers\WorkersRecordController;
use App\Models\OfficeIp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class timetrack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:timetrack';
    protected $baseIp = "https://backend.grabdata.org/api/pf";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $officeIps = OfficeIp::all();
        $response = $this->getApiData();
        // $currentDate = Carbon::now()->toDateString();
        // $filteredResponse = collect($response)->filter(function ($record) use ($currentDate) {
        //     return Carbon::parse($record['checked_in_at'])->toDateString() === $currentDate;
        // });
        $employeeDataGrouped = collect($response)->groupBy('user_id');
        error_log(print_r($employeeDataGrouped, true));


        // Initialize an empty array to store the results
        $results = [];
        foreach ($employeeDataGrouped as $userId => $employeeRecords) {
            // Initialize an array to store hours spent in each office for this employee
            $hoursSpentInOffices = [];

            // Initialize a variable to store out-of-office hours
            $totaltime = 0;

            // Iterate through each office IP
            foreach ($officeIps as $office) {
                // Filter the employee records for this office's IP
                $recordsInOffice = $employeeRecords->filter(function ($record) use ($office) {
                    return $record['ip_address'] === $office['ip'];
                });

                // Calculate the total hours spent in this office
                $totalHoursInOffice = $recordsInOffice->sum(function ($record) {
                    $checkIn = strtotime($record['checked_in_at']);
                    $checkOut = strtotime($record['checked_out_at']);
                    return ($checkOut - $checkIn) / 3600;
                });

                $hoursSpentInOffices[$office['name']] = $totalHoursInOffice;

                $recordsoutOffice = $employeeRecords->filter(function ($record) use ($office) {
                    return $record['ip_address'] !== $office['ip'];
                });
                // Calculate out-of-office hours for this employee
                $totaltime = $recordsoutOffice->sum(function ($record) {

                    $checkIn = strtotime($record['checked_in_at']);
                    $checkOut = strtotime($record['checked_out_at']);
                    return ($checkOut - $checkIn) / 3600;
                });
            }

            // Calculate the total hours spent by the user across all offices
            $totalofficeHoursForUser = array_sum($hoursSpentInOffices);

            // Determine attendance based on total hours
            if ($totalofficeHoursForUser <= 3) {
                $attendance = 'Absent';
            } elseif ($totalofficeHoursForUser > 3 && $totalofficeHoursForUser <= 5) {
                $attendance = 'Half Day';
            } else {
                $attendance = 'Full Day';
            }

            // Store the results for this employee including attendance and out-of-office hours
            // $results[$userId] = [
            //     'user_id' => $userId,
            //     'total_hours_in_office' => $totalHoursForUser,
            //     'attendance' => $attendance,
            //     'totaltime' => $outOfOfficeHours,
            // ];

            WorkersRecordController::RecordWorkersData([
                'userid' => $userId,
                'total_hours_in_office' => $totalofficeHoursForUser,
                'total_out_of_office' => $totaltime == 0 ? 0 : $totaltime - $totalofficeHoursForUser,
                'attendance' => $attendance,
                'totaltime' => $totaltime,
            ]);
        }
        Log::info($results);
        // foreach ($responce as $entry) {
        //     $userId = $entry['user_id'];
        //     $checkIn = Carbon::parse($entry['checked_in_at']);
        //     $checkOut = Carbon::parse($entry['checked_out_at']);
        //     $totalTime = $checkIn->diffInHours($checkOut);
        //     if (!isset($userTotalTimes[$userId])) {
        //         $userTotalTimes[$userId] = $totalTime;
        //     } else {
        //         $userTotalTimes[$userId] += $totalTime;
        //     }
        // }
        // Log::info($userTotalTimes);
        // foreach ($userTotalTimes as $userId => $totalTime) {

        //     $user = User::find($userId);

        //     if ($user) {
        //         $user->total_time = $totalTime;
        //         $user->save();
        //         $this->info("User ID $userId: Total Time - " . gmdate('H:i:s', $totalTime));
        //     }
        // }

        // $this->info('Total times calculated successfully.');
    }



    private function getApiData()
    {
        $response = Http::get($this->baseIp);

        if ($response->successful()) {
            return $response->json()['data'];
        } else {
            return null;
        }
    }
}
