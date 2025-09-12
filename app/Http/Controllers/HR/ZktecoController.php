<?php

namespace App\Http\Controllers\HR;

use App\Models\Admin\Branch;
use App\Models\HR\ZkData;
use App\Models\HRM\Employees;
use App\Services\CourseTypeService;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Gate;
use Laradevsbd\Zkteco\Http\Library\ZktecoLib;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;
use App\Models\HRM\HrmEmployeeAttendance;


class ZktecoController extends Controller
{
    protected $CourseTypeService;

    public function __construct(CourseTypeService $CourseTypeService)
    {
        $this->CourseTypeService = $CourseTypeService;
    }


    public function ShowAttendance() //zkt-ShowAttendance
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        ini_set('max_execution_time', 200);
        $zk = new ZKTeco('192.168.99.201', 4370);

        // dd($zk->connect());
        if ($zk->connect()) {
            $attendance = $zk->getAttendance();
            dd($attendance);
        } else {
            dd("here");
        }
    }

    public function ShowUser() //zkt-show-user
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        ini_set('max_execution_time', 200);

        $zk = new ZKTeco('192.168.99.201', 4370);

        if ($zk->connect()) {
            $users = $zk->getUser();
            //            dd($users);
//            foreach ($users as $user) {
//                // Check if $user is an array
//                if (is_array($user)) {
//                    ZkData::create([
//                        'uid' => $user['uid'] ?? null,
//                        'userid' => $user['userid'] ?? null,
//                        'name' => $user['name'] ?? null,
//                        'role' => $user['role'] ?? null,
//                        'password' => $user['password'] ?? null,
//                        'cardno' => $user['cardno'] ?? null,
//                    ]);
//                } else {
//                    echo  "here";
//                }
//            }

        }

        return view('hr.zkt.ZktShowUsers', compact('users'));
    }

    public function textVoice() //zkt-test-voice
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $zk = new ZKTeco('192.168.3.201', 4370);
        if ($zk->connect()) {
            $zk->testVoice();
            return ('ok');
        } else {
            return 'Not ok';
        }
    }

    public function DeleteUser(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        ini_set('max_execution_time', 200);
        $zk = new ZKTeco('192.168.99.201', 4370);

        if ($zk->connect()) {
            $user = $zk->getUser();

            //3 ,4,5.6,7,8,9
            foreach ($user as $item) {
                if ($item['uid'] != 1) {
                    $zk->removeUser($item['uid']);
                }
            }


            //            for ($i = 0 ; $i <= 69 ;$i++){
//                $zk->removeUser($i);
//            }

            return 'ok';
        } else {
            return 'Not ok';
        }
    }

    public function DeleteAttendance(Request $request) //zkt-delete-attendance-machine
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        ini_set('max_execution_time', 200);
        $zk = new ZKTeco('192.168.99.201', 4370);
        if ($zk->connect()) {
            //            dd(12);
            $zk->clearAttendance();
            return 'ok';
        } else {
            return 'Not ok';
        }
    }

    public function addUser() //zkt-add-user
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $zk = new ZKTeco('192.168.3.201', 4370);
        if ($zk->connect()) {
            $users = $zk->getUser();

            $total = end($users);
            $lastId = $total['uid'];
            $newId = $lastId + 1;

            $name = 'user';
            $password = '123';
            $role = '14';
            $cardno = 0000000001;
            $zk->setUser($newId, '167', $name, $password, $role, $cardno);
            //            $zk->setUser(1, 1, $name, $password, $role, $cardno );
            return "User added successfully";
        } else {
            return "Device not connected";
        }
    }

    public function AddDataInDataBase() //zkt-add-attendance-db
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $zk = new ZKTeco('192.168.99.222', 4370);
        if ($zk->connect()) {
            $attendance = $zk->getAttendance();


            foreach ($attendance as $item) {
                $datetime = $item['timestamp'];
                $date_arr = explode(" ", $datetime);
                $date = $date_arr[0];
                $time = $date_arr[1];
                $currentdate = date('Y-m-d');
                $user1 = HrmEmployeeAttendance::where('user_id', $item['id'])->first();

                if ($user1 && ($date == $currentdate) && ($user1->user_id == $item['id'])) {
                    if ($item['type'] == 0) {
                        $user1->checkin_time = $time;
                    }
                    if ($item['type'] == 1) {
                        $user1->checkout_time = $time;
                    }
                    if ($item['type'] == 4) {
                        $user1->overtime_in = $time;
                    }
                    if ($item['type'] == 5) {
                        $user1->overtime_out = $time;
                    }
                    $user1->save();
                } else {
                    $user2 = new HrmEmployeeAttendance();
                    $user2->is_machine = 'Machine';
                    $user2->user_id = $item['id'];
                    $user2->date = $date;
                    $user2->manual_attendance = null;
                    if (isset($item['status'])) {
                        $user2->status = $item['status'];
                    } else {
                        $user2->status = 1;
                    }
                    if ($item['type'] == 0) {
                        $user2->checkin_time = $time;
                    }
                    if ($item['type'] == 1) {
                        $user2->checkout_time = $time;
                    }
                    if ($item['type'] == 4) {
                        $user2->overtime_in = $time;
                    }
                    if ($item['type'] == 5) {
                        $user2->overtime_out = $time;
                    }
                    $user2->save();
                }
            }
            return 'Data added to db';
        } else {
            return 'Failed to Connect to device';
        }
    }

    public function addAttendance(Request $request) //
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $user1 = HrmEmployeeAttendance::where(['user_id' => $request->user_id, 'date' => $request->date])->first();

        if ($user1) {
            //            dd($request);
//            dd($request->checkin_time , $request->checkout_time,  $request->overtime_in, $request->overtime_out );
            if ($request->checkin_time) {
                $user1->checkin_time = $request->checkin_time;
            }
            if ($request->checkout_time) {
                $user1->checkout_time = $request->checkout_time;
            }
            if ($request->overtime_in) {
                $user1->overtime_in = $request->overtime_in;
            }
            if ($request->overtime_out) {
                $user1->overtime_out = $request->overtime_out;
            }
            $user1->save();
        } else {
            $user = new HrmEmployeeAttendance;
            $user->is_machine = Null;
            $user->user_id = $request->user_id;
            $user->date = $request->date;
            $user->checkin_time = $request->checkin_time;
            $user->checkout_time = $request->checkout_time;
            $user->overtime_in = $request->overtime_in;
            $user->overtime_out = $request->overtime_out;
            $user->type = $request->type;
            $user->status = $request->status;
            $user->manual_attendance = 'Manual';
            $user->save();
        }
        return 'Attendance added';


    }

    public function DbShowAttendance() //zkt-show-attendance-db
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $attendance = HrmEmployeeAttendance::with('user_name')->get();
        //        dd($attendance);

        return view('hr.zkt.ZktShowAttendence', compact('attendance'));
    }


    public function sync()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->CourseTypeService->sync();
    }

    public function syncdb()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->CourseTypeService->syncdb();
    }


    public function employeeGenerated($ids)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        ini_set('max_execution_time', 120000);

        if (isset($ids)) {
            if (is_array($ids)) {
                $employees = Employees::whereIn('id', $ids)->get();
            } else {
                $employees = Employees::where('id', $ids)->get();
            }

            foreach ($employees as $emp) {

                $emp_status = Employees::where('id', $emp->id)->first();
                $emp_status->machine_status = 1;
                $emp_status->save();

                foreach ($emp->Otherbranch as $branch) {
                    try {
                        $branch_ip = optional($branch->branch)->ip_config ?? '202.142.153.118';
                        $branch_port = optional($branch->branch)->port ?? 4370;

                        $zk = new ZKTeco($branch_ip, $branch_port);

                        if ($zk->connect()) {
                            $name = $emp->name;
                            $password = '1234';
                            $role = '0';
                            $cardno = '000000000';

                            $users = $zk->getUser();

                            $total = end($users);
                            $lastId = $total ? $total['uid'] : 0;
                            $newId = $lastId + 1;

                            $zk->setUser($newId, $emp->id, $name, $password, $role, $cardno);
                        } else {
                            // log the issue instead of breaking the loop
                            \Log::warning("Device not connected at {$branch_ip}:{$branch_port}");
                            continue;
                        }
                    } catch (\Throwable $e) {
                        // catch any error/exception and continue to the next branch
                        \Log::error("Error with device at {$branch_ip}:{$branch_port} → " . $e->getMessage());
                        continue;
                    }
                }
            }
        }


    }

    private function generateUniqueId($users)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $total = end($users);
        $lastId = $total['uid'];
        return $lastId + 1;
    }

}
