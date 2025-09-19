<?php
use App\Http\Controllers\PrintableController;
use App\Models\Admin\Ledgers;
use App\Services\LedgerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HR\AssetController;
use App\Http\Controllers\HR\ZktecoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\Admin\MachineController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\DataBankController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Dashbboard\DashboardController;
use App\Http\Controllers\Exam\SkillsController;






use App\Exports\BranchSampleExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/zkt-ShowAttendance', [ZktecoController::class, 'ShowAttendance'])->name('zkt-ShowAttendance');
Route::get('/zkt-show-user', [ZktecoController::class, 'ShowUser'])->name('zkt-show-user');
Route::get('/zkt-add-user', [ZktecoController::class, 'addUser'])->name('zkt-add-user');
Route::get('/zkt-test-voice', [ZktecoController::class, 'textVoice'])->name('zkt-test-voice');
Route::get('/zkt-delete-user', [ZktecoController::class, 'DeleteUser'])->name('zkt-delete-user');
Route::get('/zkt-delete-attendance-machine', [ZktecoController::class, 'DeleteAttendance'])->name('zkt-delete-attendance-machine');
Route::get('/zkt-sync', [ZktecoController::class, 'sync'])->name('zkt-sync');
Route::get('/zkt-sync-db', [ZktecoController::class, 'syncdb'])->name('zkt-syncdb');
Route::get('users-attend', [EmployeeController::class, 'addAttendance']);
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/asset/bulk', [AssetController::class, "bulkShow"])->name('asset-bulk');
    Route::post('/asset/bulk/save', [AssetController::class, "bulkSave"])->name('asset-bulk-save');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('students', StudentController::class);
    Route::get("students/databank/{id}", [DataBankController::class, 'student_databank_create'])->name('students.databank.create');
    Route::post("student_databank_remarks", [DataBankController::class, 'student_databank_remarks'])->name('student.databank.remarks');
    Route::post("student_databank_status", [DataBankController::class, 'student_databank_status'])->name('student.databank.status');
    Route::get("students_view_installemet/{id}", [DataBankController::class, 'students_view_installemet'])->name('students_view_installemet');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/send/sms', [DashboardController::class, 'sms'])->name('send.sms');
    Route::get('/students/create/existing_student_ledger', [StudentController::class, 'existingStudentLedger'])->name('students.existing_student_ledger');


    Route::get('/fetch-section', [EmployeeController::class, 'fetchSection'])->name('fetch-section');
    Route::get('/fetch-students', [StudentController::class, 'fetchStudents'])->name('fetch-students');


});
Route::get('/sendsms', function () {
    $APIKey = '81fe627f8f0f3274301edfc306cc9209';
    $receiver = '923023373699';
    $sender = '8583';
    $textmessage = 'first message  from onezcommerce  ';
});
Route::get('/testsms', function () {
    $APIKey = 'e8785e57ef6d6ebe153f093b0c527b86';
    $receiver = '923164225320';
    $sender = '8583';
    $textmessage = 'Test SMS from VT API';
    $url = "https://api.veevotech.com/sendsms?hash=" . $APIKey . "&receivenum=" . $receiver . "&sendernum=" . urlencode($sender) . "&textmessage=" . urlencode($textmessage);
    #----CURL Request Start
    $ch = curl_init();
    $timeout = 30;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $response = curl_exec($ch);
    curl_close($ch);
    #----CURL Request End, Output Response
    echo $response;
});
Route::get('/generate-pdf', [\App\Http\Controllers\Academic\StudentViewController::class, 'generatePdf'])->name('generate-pdf');
Route::get('/run-queue-worker', function () {
    Artisan::call('queue:work', [
        '--queue' => 'default',
        '--tries' => 3,
    ]);
    $output = Artisan::output();
    return response()->json([
        'message' => 'Queue worker started.',
        'output' => $output,
    ]);
});

Route::get('/temp', function () {
    DB::beginTransaction();
    try {
        $duplicateLedgers = Ledgers::select('number')
            ->groupBy('number')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('number');

        $legders = Ledgers::whereIn('number', $duplicateLedgers)->delete();
        DB::commit();
        return $legders;
    } catch (Exception $es) {
        DB::rollBack();
        return 'Error : ' . $es->getMessage();
    }
});

Route::get('/print-preview/{tableName}',[PrintableController::class,'printPreview'])->name('print-preview');

    Route::get('get-subjects/{classId}', [SkillsController::class, 'getsubject']);
    Route::get('get-components/{subjectId}', [SkillsController::class, 'getComponents']);


