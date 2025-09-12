<?php
namespace App\Helpers;


use App\Models\HRM\Employees;

/**
 * Class to store the entire group tree
 */
class Marketing
{
    var $id = 0;
    var $name = '';
    var $code = '';
    var $number = '';

    var $children_groups = array();
    var $children_ledgers = array();

    var $counter = 0;

    var $current_id = -1;

    var $restriction_bankcash = 1;

    var $default_text = 'Please select...';

    /**
     * Initializer
     */
    public static function getMrkTreeOf($user_id)
    {
        $user_ids = array();
        array_push($user_ids, $user_id);
        if ($user_id != 0) {
            while ($user_id != 0) {
                $user_id = Employees::pluckParent($user_id);
                $user_id = $user_id[0];
                if ($user_id != 0) {
                    array_push($user_ids, $user_id);
                }

            }

        }

        return $user_ids;
    }

    public static function user_emails($user_id)
    {

        $emails = Employees::pluckEmailsByIds($user_id);
        return $emails;
    }

    static public function pluckDownTree($user_id)
    {
        $sub_users = true;
        $user_ids = array();
        $temp_user_ids = array($user_id);
        while ($sub_users) {
            $temp_user_ids = Employees::pluckActiveChildsOnly($temp_user_ids);
            if (count($temp_user_ids) > 0) {
                foreach ($temp_user_ids as $val) {
                    array_push($user_ids, $val);
                }
            } else {
                $sub_users = false;
            }
        }
        return $user_ids;
    }
}
