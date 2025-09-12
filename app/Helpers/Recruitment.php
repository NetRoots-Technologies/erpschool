<?php
namespace App\Helpers;

use App\Models\Admin\Groups;
use App\Models\Admin\Ledgers;
use App\Models\HRM\CardIssuance;
use App\Models\HRM\JobApplication;
use Config;
use Illuminate\Support\Facades\Mail;

/**
 * Class to store the entire group tree
 */
class Recruitment
{
    /**
     * Initializer
     */
    function Recruitment()
    {
        return;
    }

    public static function changeStatus($id, $value)
    {

        $jobApplication = JobApplication::findOrFail($id);

        // if()
        // dd($jobApplication);
        if ($jobApplication) {
            $jobApplication->update(['status' => $value]);
            return $jobApplication->status;
        } else {
            return 'fail';
        }
    }
    public static function getKeyByValue($value)
    {
        $statusArray = Config::get('hrm.applicant_status_array');
        $arrayKeyValue = array_search($value, $statusArray);
        return $arrayKeyValue;
    }
    public static function getValueByKey($key)
    {
        $value = Config::get('hrm.applicant_status_array.' . $key);
        return $value;
    }
    public static function lineManagerFeedbackArray()
    {
        $statusArray = Config::get('hrm.applicant_status_array');
        //        $lmArray=array_slice($statusArray,4,3);
        foreach ($statusArray as $key => $value) {
            if ((self::getKeyByValue('Shortlisted by Line Manager') <= $key) && ($key < self::getKeyByValue('Hold by Line Manager'))) {
                $lmArray[$key] = $value;
            }
        }
        return $lmArray;
    }
    public static function lineManagerAfterInterviewFeedbackArray()
    {
        $statusArray = Config::get('hrm.applicant_status_array');
        //        $lmArray=array_slice($statusArray,4,3);
        foreach ($statusArray as $key => $value) {
            if ((self::getKeyByValue('After Interview Accepted by Line Manager') <= $key) && ($key <= self::getKeyByValue('After Interview Hold by Line Manager'))) {
                $lmArray[$key] = $value;
            }
        }
        return $lmArray;
    }
    public static function hodFeedbackArray()
    {
        $statusArray = Config::get('hrm.applicant_status_array');
        //        $lmArray=array_slice($statusArray,4,3);
        foreach ($statusArray as $key => $value) {
            if ((self::getKeyByValue('After Interview Accepted by HOD') <= $key) && ($key <= self::getKeyByValue('After Interview Hold by HOD'))) {
                $lmArray[$key] = $value;
            }
        }
        return $lmArray;
    }

    public static function hodFeedbackArray_()
    {
        $statusArray = Config::get('hrm.applicant_status_array');
        //        $lmArray=array_slice($statusArray,4,3);
        foreach ($statusArray as $key => $value) {
            if ((self::getKeyByValue('shortlisted by HOD') <= $key) && ($key <= self::getKeyByValue('Hold by HOD'))) {
                $lmArray[$key] = $value;
            }
        }
        return $lmArray;
    }

    public static function ceoFeedbackArray()
    {
        $statusArray = Config::get('hrm.applicant_status_array');
        //        $lmArray=array_slice($statusArray,4,3);
        foreach ($statusArray as $key => $value) {
            if ((self::getKeyByValue('After Interview Accepted by CEO') <= $key) && ($key <= self::getKeyByValue('After Interview Hold by CEO'))) {
                $lmArray[$key] = $value;
            }
        }
        return $lmArray;
    }

    public static function ceoFeedbackArray_()
    {
        $statusArray = Config::get('hrm.applicant_status_array');
        //        $lmArray=array_slice($statusArray,4,3);
        foreach ($statusArray as $key => $value) {
            if ((self::getKeyByValue('shortlisted by CEO') <= $key) && ($key <= self::getKeyByValue('Hold by CEO'))) {
                $lmArray[$key] = $value;
            }
        }
        return $lmArray;
    }

    public static function getKeyByValueCardIssuance($value)
    {
        $statusArray = Config::get('hrm.card_issuance_status_array');
        $arrayKeyValue = array_search($value, $statusArray);
        return $arrayKeyValue;
    }
    public static function getValueByKeyCardIssuance($key)
    {
        $value = Config::get('hrm.card_issuance_status_array.' . $key);
        return $value;
    }
    public static function changeStatusCardIssuance($id, $value)
    {

        $cardIssuance = CardIssuance::findOrFail($id);
        if ($cardIssuance) {
            $cardIssuance->update(['status' => $value]);
            return $cardIssuance->status;
        } else {
            return 'fail';
        }
    }

    public static function getKeyByValueForEmploymentType($value)
    {
        $statusArray = Config::get('hrm.employment_type');
        $arrayKeyValue = array_search($value, $statusArray);
        return $arrayKeyValue;
    }

    public static function sendMail($from = null, $to = null, $cc = null, $bcc = null, $viewPath, $dataArray)
    {
        Mail::send($viewPath, ['mailData' => $dataArray], function ($m) use ($to, $from) {
            $m->from($from, 'HR');

            $m->to($to)->subject('Notification');
            //            $m->to('ahsan.ullah@netrootstech.com')->subject('Notification');

            return true;
        });
        //        Mail::to($to)->cc($cc)->bcc($bcc)->send($mailObject);

    }
    public static function newHiring($from = null, $to = null, $cc = null, $bcc = null, $viewPath, $dataArray)
    {
        Mail::send($viewPath, ['mailData' => $dataArray], function ($m) use ($to, $from) {
            $m->from($from, 'HR');

            $m->to($to)->subject('Notification');
            //            $m->to('ahsan.ullah@netrootstech.com')->subject('Notification');

            return true;
        });
        //        Mail::to($to)->cc($cc)->bcc($bcc)->send($mailObject);

    }
    public static function sendPayrollMail($from = null, $to = null, $cc = null, $bcc = null, $viewPath, $dataArray)
    {
        $PayRoll = $dataArray['PayRoll'];
        $Employee = $dataArray['Employee'];
        $Quotta = $dataArray['Quotta'];
        Mail::send($viewPath, compact('PayRoll', 'Employee', 'Quotta'), function ($m) use ($to, $from) {
            $m->from($from, 'HR');

            $m->to($to)->subject('Notification');
            return true;
        });
        //        Mail::to($to)->cc($cc)->bcc($bcc)->send($mailObject);

    }

    public static function getKeyByValueResign($value)
    {
        $statusArray = Config::get('hrm.resign_status_array');
        $arrayKeyValue = array_search($value, $statusArray);
        return $arrayKeyValue;
    }
    public static function getValueByKeyResign($key)
    {
        $value = Config::get('hrm.resign_status_array.' . $key);
        return $value;
    }
    public static function getDocumentNameByKey($key)
    {
        return $value = EmployeeDocumentTypes::where('id', $key)->get()->pluck('name')->first();

    }

    public static function getDocumentLocationByKey($key)
    {
        return $value = EmployeeDocuments::where('id', $key)->get()->pluck('name')->first();
    }
}
