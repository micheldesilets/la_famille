<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-28
 * Time: 08:39
 */

class MobileSniffer
{
    private $userAgent;
    private $device;
    private $browser;
    private $deviceLength;
    private $browserLength;

    public function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->userAgent = strtolower($this->userAgent);
        $this->device = array('iphone', 'ipad', 'android', 'silk',
            'blackberry', 'touch', 'windows');
        $this->browser = array('firefox', 'chrome', 'opera', 'msie', 'safari',
            'blackberry', 'trident');
        $this->deviceLength = count($this->device);
        $this->browserLength = count($this->browser);
    }

    public function findDevice()
    {
        for ($i = 0; $i < $this->deviceLength; $i++) {
            if (strstr($this->userAgent, $this->device[$i])) {
                return $this->device[$i];
            }
        }
        return 'Current device not in list of devices';
    }

    public function findBrowser()
    {
        for ($i = 0; $i < $this->browserLength; $i++) {
            if (strstr($this->userAgent, $this->browser[$i])) {
                return $this->browser[$i];
            }
        }
        return 'Current browser not in list of browsers';
    }
}