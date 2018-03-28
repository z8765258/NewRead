<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
//Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
Route::get('api/:ver/getTodayContent/','api/:ver.Content/getTodayContent');
Route::get('api/:ver/getTypeContentNums/','api/:ver.Content/getTypeContentNums');
Route::get('api/:ver/getAllType/','api/:ver.Course/getAllType');
Route::get('api/:ver/getUserTypePay/','api/:ver.Course/getUserTypePay');
Route::get('api/:ver/getPlan', 'api/:ver.Plan/getUpdateUserPlan');
Route::get('api/:ver/getUserPlan', 'api/:ver.Plan/getUserPlan');
Route::get('api/:ver/setTime', 'api/:ver.Plan/setRemindTime');
Route::get('api/:ver/cardAll','api/:ver.Card/getCardList');
Route::get('api/:ver/cardRank','api/:ver.Card/getCardRanking');
Route::get('api/:ver/wxMessage','api/:ver.WxMessage/sendWxMessage');
Route::get('api/:ver/queryWord','api/:ver.QueryWord/queryWord');


Route::post('api/:ver/token/user','api/:ver.Token/getToken');
Route::post('api/:ver/token/verify','api/:ver.Token/verifyToken');

Route::post('api/:ver/order', 'api/:ver.Order/placeOrder');
Route::post('api/:ver/pay/pre_order', 'api/:ver.Pay/getPreOrder');
Route::post('api/:ver/pay/notify', 'api/:ver.Pay/receiveNotify');

Route::post('api/:ver/updateUser', 'api/:ver.User/updateUserInfo');

Route::post('api/:ver/card','api/:ver.Card/createCardRecord');

Route::post('api/:ver/formid','api/:ver.Formid/disposeFormId');
