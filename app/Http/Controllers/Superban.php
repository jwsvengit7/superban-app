<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Notification\BanNotification;

class Superban
{
    protected $cache; 

    public function __construct()
    {
        $this->cache = app('cache');
    }

    public function trackRequest($userId, $ipAddress, $email)
    {
        $userKey = "user:$userId";
        $ipKey = "ip:$ipAddress";
        $emailKey = "email:$email";

        $this->incrementRequestCount($userKey);
        $this->incrementRequestCount($ipKey);
        $this->incrementRequestCount($emailKey);
    }

    protected function incrementRequestCount($key)
    {
        $count = $this->cache->get($key, 0); 
        $count++;
        $this->cache->put($key, $count); 
    }

   
    public function applyBan($userId, $ipAddress, $email, $limit, $timeInterval, $banDuration)
    {
        $userKey = "user:$userId";
        $ipKey = "ip:$ipAddress";
        $emailKey = "email:$email";

        $userCount = $this->cache->get($userKey, 0);
        $ipCount = $this->cache->get($ipKey, 0);
        $emailCount = $this->cache->get($emailKey, 0);


        if ($userCount > $limit || $ipCount > $limit || $emailCount > $limit) {
      
            $this->banUser($userId, $banDuration);
            $this->banIpAddress($ipAddress, $banDuration);
            $this->banEmail($email, $banDuration);

            $banNotify = new BanNotification($userId, $ipAddress, $email, $banDuration);
            $banNotify->toMail();
        }
    }


protected function banUser($userId, $banDuration)
{
    $userBanKey = "ban:user:$userId";
    $this->cache->put($userBanKey, true, now()->addMinutes($banDuration));
}


protected function banIpAddress($ipAddress, $banDuration)
{
    $ipBanKey = "ban:ip:$ipAddress";
    $this->cache->put($ipBanKey, true, now()->addMinutes($banDuration));
}

protected function banEmail($email, $banDuration)
{
    $emailBanKey = "ban:email:$email";
    $this->cache->put($emailBanKey, true, now()->addMinutes($banDuration));
}

}
