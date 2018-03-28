<?php
namespace Qiniu;

use Qiniu\Http\Client;
use Qiniu\Http\Error;

final class Zone
{

    //源站上传域名
    public $srcUpHosts;
    //CDN加速上传域名
    public $cdnUpHosts;
    //资源管理域名
    public $rsHost;
    //资源列举域名
    public $rsfHost;
    //资源处理域名
    public $apiHost;
    //IOVIP域名
    public $iovipHost;

    //构造一个Zone对象
    public function __construct(
        $srcUpHosts = array(),
        $cdnUpHosts = array(),
        $rsHost = "rs.Qiniu.com",
        $rsfHost = "rsf.Qiniu.com",
        $apiHost = "api.Qiniu.com",
        $iovipHost = null
    ) {

        $this->srcUpHosts = $srcUpHosts;
        $this->cdnUpHosts = $cdnUpHosts;
        $this->rsHost = $rsHost;
        $this->rsfHost = $rsfHost;
        $this->apiHost = $apiHost;
        $this->iovipHost = $iovipHost;
    }

    //华东机房
    public static function zone0()
    {
        $Zone_z0 = new Zone(
            array("up.qiniup.com", 'up-nb.qiniup.com', 'up-xs.qiniup.com'),
            array('upload.qiniup.com', 'upload-nb.qiniup.com', 'upload-xs.qiniup.com'),
            'rs.Qiniu.com',
            'rsf.Qiniu.com',
            'api.Qiniu.com',
            'iovip.qbox.me'
        );
        return $Zone_z0;
    }

    //华北机房
    public static function zone1()
    {
        $Zone_z1 = new Zone(
            array('up-z1.qiniup.com'),
            array('upload-z1.qiniup.com'),
            "rs-z1.Qiniu.com",
            "rsf-z1.Qiniu.com",
            "api-z1.Qiniu.com",
            "iovip-z1.qbox.me"
        );

        return $Zone_z1;
    }

    //华南机房
    public static function zone2()
    {
        $Zone_z2 = new Zone(
            array('up-z2.qiniup.com', 'up-gz.qiniup.com', 'up-fs.qiniup.com'),
            array('upload-z2.qiniup.com', 'upload-gz.qiniup.com', 'upload-fs.qiniup.com'),
            "rs-z2.Qiniu.com",
            "rsf-z2.Qiniu.com",
            "api-z2.Qiniu.com",
            "iovip-z2.qbox.me"
        );
        return $Zone_z2;
    }

    //北美机房
    public static function zoneNa0()
    {
        //北美机房
        $Zone_na0 = new Zone(
            array('up-na0.qiniup.com'),
            array('upload-na0.qiniup.com'),
            "rs-na0.Qiniu.com",
            "rsf-na0.Qiniu.com",
            "api-na0.Qiniu.com",
            "iovip-na0.qbox.me"
        );
        return $Zone_na0;
    }

    /*
     * GET /v2/query?ak=<ak>&&bucket=<bucket>
     **/
    public static function queryZone($ak, $bucket)
    {
        $zone = new Zone();
        $url = Config::UC_HOST . '/v2/query' . "?ak=$ak&bucket=$bucket";
        $ret = Client::Get($url);
        if (!$ret->ok()) {
            return array(null, new Error($url, $ret));
        }
        $r = ($ret->body === null) ? array() : $ret->json();
        //parse zone;

        $iovipHost = $r['io']['src']['main'][0];
        $zone->iovipHost = $iovipHost;
        $accMain = $r['up']['acc']['main'][0];
        array_push($zone->cdnUpHosts, $accMain);
        if (isset($r['up']['acc']['backup'])) {
            foreach ($r['up']['acc']['backup'] as $key => $value) {
                array_push($zone->cdnUpHosts, $value);
            }
        }
        $srcMain = $r['up']['src']['main'][0];
        array_push($zone->srcUpHosts, $srcMain);
        if (isset($r['up']['src']['backup'])) {
            foreach ($r['up']['src']['backup'] as $key => $value) {
                array_push($zone->srcUpHosts, $value);
            }
        }

        //set specific hosts
        if (strstr($zone->iovipHost, "z1") !== false) {
            $zone->rsHost = "rs-z1.Qiniu.com";
            $zone->rsfHost = "rsf-z1.Qiniu.com";
            $zone->apiHost = "api-z1.Qiniu.com";
        } elseif (strstr($zone->iovipHost, "z2") !== false) {
            $zone->rsHost = "rs-z2.Qiniu.com";
            $zone->rsfHost = "rsf-z2.Qiniu.com";
            $zone->apiHost = "api-z2.Qiniu.com";
        } elseif (strstr($zone->iovipHost, "na0") !== false) {
            $zone->rsHost = "rs-na0.Qiniu.com";
            $zone->rsfHost = "rsf-na0.Qiniu.com";
            $zone->apiHost = "api-na0.Qiniu.com";
        } else {
            $zone->rsHost = "rs.Qiniu.com";
            $zone->rsfHost = "rsf.Qiniu.com";
            $zone->apiHost = "api.Qiniu.com";
        }

        return $zone;
    }
}
