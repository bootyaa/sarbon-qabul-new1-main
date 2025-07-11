<?php

use common\models\AuthItem;
use common\models\Consulting;
use common\models\Branch;
use common\models\Actions;
use common\models\Permission;
use Detection\MobileDetect;

function current_user()
{
    return \Yii::$app->user->identity;
}

// Get current user id
function current_user_id()
{
    return (int)Yii::$app->user->id;
}
function isRole($string)
{
    $user = Yii::$app->user->identity;
    if ($user->user_role == $string) {
        return true;
    }
    return false;
}


function custom_shuffle($my_array = array())
{
    $copy = array();
    while (count($my_array)) {
        // takes a rand array elements by its key
        $element = array_rand($my_array);
        // assign the array and its value to an another array
        $copy[$element] = $my_array[$element];
        //delete the element from source array
        unset($my_array[$element]);
    }
    return $copy;
}



function current_education_id()
{
    $user = Yii::$app->user->identity;
    return $user->id;
}



function tt($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    die;
}

function formatPhoneNumber($number)
{
    $normalizedPhoneNumber = preg_replace('/[^\d+]/', '', $number);
    return $normalizedPhoneNumber;
}

function getDomainFromURL($url)
{
    // URL dan domen nomini ajratib olish
    $parsedUrl = parse_url($url);
    $domain = $parsedUrl['host'];

    return $domain;
}

function getIpAddress()
{
    return \Yii::$app->request->getUserIP();
}

// Get browser
function getBrowser()
{
    $mob_detect = new MobileDetect();
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser_name = 'Unknown Browser';
    $platform = 'Unknown OS';
    $version = "";
    $ub = "";

    // First get the platform
    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile',
    );

    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $platform = $value;
        }
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
        $browser_name = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $user_agent)) {
        $browser_name = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/OPR/i', $user_agent)) {
        $browser_name = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Chrome/i', $user_agent) && !preg_match('/Edge/i', $user_agent)) {
        $browser_name = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $user_agent) && !preg_match('/Edge/i', $user_agent)) {
        $browser_name = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Netscape/i', $user_agent)) {
        $browser_name = 'Netscape';
        $ub = "Netscape";
    } elseif (preg_match('/Edge/i', $user_agent)) {
        $browser_name = 'Edge';
        $ub = "Edge";
    } elseif (preg_match('/Trident/i', $user_agent)) {
        $browser_name = 'Internet Explorer';
        $ub = "MSIE";
    }

    // Finally get the correct browser version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    preg_match_all($pattern, $user_agent, $matches);

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($user_agent, "Version") < strripos($user_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }

    // check device type
    $device = 'desktop';

    if ($mob_detect->isMobile()) {
        $device = 'mobile';
    } elseif ($mob_detect->isTablet()) {
        $device = 'tablet';
    }

    return [
        'ip' => getIpMK(),
        'user_agent' => $user_agent,
        'browser_name' => $browser_name,
        'browser_version' => $version,
        'platform' => $platform,
        'device' => $device,
        'session' => "{$browser_name} {$version} / {$platform}",
    ];
}

function getIpMK()
{
    $mainIp = '';
    if (getenv('HTTP_CLIENT_IP'))
        $mainIp = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $mainIp = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_X_CLUSTER_CLIENT_IP'))
        $mainIp = getenv('HTTP_X_CLUSTER_CLIENT_IP');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $mainIp = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $mainIp = getenv('REMOTE_ADDR');
    else
        $mainIp = 'UNKNOWN';
    return $mainIp;

    $mainIp = '';
    if (getenv('HTTP_CLIENT_IP'))
        $mainIp = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $mainIp = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_X_CLUSTER_CLIENT_IP'))
        $mainIp = getenv('HTTP_X_CLUSTER_CLIENT_IP');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $mainIp = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $mainIp = getenv('REMOTE_ADDR');
    else
        $mainIp = 'UNKNOWN';
    return $mainIp;
}


// Is IP in allowed  List
function checkAllowedIP()
{
    // return true;
    $userIp = getIpMK();
    $sam = '10.0';

    $allowedIps = [
        '45.150.24.183',
        '89.104.102.200',
    ];

    if (in_array($userIp, $allowedIps)) {
        return true;
    } elseif (str_starts_with($userIp, $sam)) {
        return true;
    } elseif ($userIp == '127.0.0.1') {
        return true;
    }
    return false;
}

function getConsIk()
{
    $user = Yii::$app->user->identity;
    $role = $user->user_role;
    $authItem = AuthItem::findOne(['name' => $role]);

    $data = [
        's.branch_id' => null,
        'u.cons_id' => null,
    ];

    if ($authItem) {
        $cons = Consulting::find()
            ->select('id')
            ->column();

        $branch = Branch::find()
            ->select('id')
            ->column();

        if ($authItem->type == 1) {
            $data = [
                's.branch_id' => $branch,
                'u.cons_id' => $cons,
            ];
        } elseif ($authItem->type == 2) {
            $data = [
                's.branch_id' => $authItem->branch_id,
                'u.cons_id' => $cons,
            ];
        } elseif ($authItem->type == 3) {
            $data = [
                's.branch_id' => $branch,
                'u.cons_id' => $user->cons_id,
            ];
        } elseif ($authItem->type == 4) {
            $data = [
                's.branch_id' => $authItem->branch_id,
                'u.cons_id' => $user->cons_id,
            ];
        }
    }

    return $data;
}

function getBranchOneIk()
{
    $user = Yii::$app->user->identity;
    $role = $user->user_role;
    $authItem = AuthItem::findOne(['name' => $role]);

    $data = null;

    if ($authItem) {
        $branch = Branch::find()
            ->select('id')
            ->column();

        if ($authItem->type == 1) {
            $data = $branch;
        } elseif ($authItem->type == 2) {
            $data = $authItem->branch_id;
        } elseif ($authItem->type == 3) {
            $data = $branch;
        } elseif ($authItem->type == 4) {
            $data = $authItem->branch_id;
        }
    }

    return $data;
}

function getConsOneIk()
{
    $user = Yii::$app->user->identity;
    $role = $user->user_role;
    $authItem = AuthItem::findOne(['name' => $role]);

    $data = null;

    if ($authItem) {
        $cons = Consulting::find()
            ->select('id')
            ->column();

        if ($authItem->type == 1) {
            $data = $cons;
        } elseif ($authItem->type == 2) {
            $data = $cons;
        } elseif ($authItem->type == 3) {
            $data = $user->cons_id;
        } elseif ($authItem->type == 4) {
            $data = $user->cons_id;
        }
    }

    return $data;
}

function permission($controller, $action)
{
    $act = Actions::findOne([
        'controller' => $controller,
        'action' => $action,
        'status' => 0
    ]);

    if ($act) {
        $userRole = Yii::$app->user->identity->user_role;
        return Permission::find()
            ->where([
                'role_name' => $userRole,
                'action_id' => $act->id,
                'status' => 1
            ])
            ->exists();
    }

    return false;
}
