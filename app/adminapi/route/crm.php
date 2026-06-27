<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::group(function () {

    //客户模块
    Route::group('customer', function () {
        //更改成功状态
        Route::post('changeDealStatus', 'customer.customer/changeDealStatus');
        //删除客户
        Route::post('delete', 'customer.customer/delete');
        //客户分配 
        Route::post('allocation', 'customer.customer/allocation');
    });


    //线索模块
    Route::group('clues', function () {
        //线索分配 
        Route::post('allocation', 'clues.clues/allocation');
        //转换客户 
        Route::post('transform', 'clues.clues/transform');
        //删除线索
        Route::post('delete', 'clues.clues/delete');
    });


    //联系人模块
    Route::group('contacts', function () {
        //更换负责人 
        Route::post('changeOwnerUser', 'contacts.contacts/changeOwnerUser');
        //删除线索
        Route::post('delete', 'contacts.contacts/delete');
    });

    //产品模块
    Route::group('product', function () {
        //删除产品
        Route::post('delete', 'product.product/delete');
        //删除套餐子业务模板
        Route::post('packageItem/delete', 'product.productPackageItem/delete');
    });


    //产品服务步骤模块
    Route::group('step', function () {
        //删除步骤
        Route::post('delete', 'product.productStep/delete');
    });


    //合同模块
    Route::group('contract', function () {
        //合同更换负责人 
        Route::post('changeOwnerUser', 'contract.contract/changeOwnerUser');
        //合同产品列表
        Route::get('getProduct', 'contract.contract/getProduct');
        //重新生成服务周期和工单
        Route::post('generateWorkflow', 'contract.contract/generateWorkflow');
        //合同删除
        Route::post('delete', 'contract.contract/delete');
    });


    //收款模块
    Route::group('collect', function () {
        //单次服务收款
        Route::group('single', function () {
            Route::get('getContractProducts', 'collect.singleCollect/getContractProducts');
            Route::post('delete', 'collect.singleCollect/delete');
        });
        //长期服务收款
        Route::group('long', function () {
            Route::get('getContractProducts', 'collect.longCollect/getContractProducts');
            Route::post('delete', 'collect.longCollect/delete');
        });
    });


    //企业模块
    Route::group('enterprise', function () {
        //删除企业
        Route::post('delete', 'enterprise.enterprise/delete');
    });


    //商机模块
    Route::group('business', function () {
        //获取商机组
        Route::get('getGroupList', 'business.business/getGroupList');
        //获取商机产品
        Route::get('getProduct', 'business.business/getProduct');
        //获取商机阶段
        Route::get('getBusinessStage', 'business.business/getBusinessStage');
        //更换负责人 
        Route::post('changeOwnerUser', 'business.business/changeOwnerUser');
        //删除
        Route::post('delete', 'business.business/delete');
        //推进商机
        Route::post('changeBusinessStage', 'business.business/changeBusinessStage');
        //结束商机
        Route::post('endStage', 'business.business/endStage');
    });


    //资源路由
    Route::group(function () {
        //线索
        Route::resource('clues', 'clues.clues');
        //客户
        Route::resource('customer', 'customer.customer');
        //合同
        Route::resource('contract', 'contract.contract');
        //跟进记录
        Route::resource('record', 'record.record');
        //联系人
        Route::resource('contacts', 'contacts.contacts');
        //产品
        Route::resource('product', 'product.product');
        //产品套餐子业务模板
        Route::resource('product/packageItem', 'product.productPackageItem');
        //合同
        Route::resource('contract', 'contract.contract');
        //合同服务周期
        Route::resource('contract/servicePeriod', 'contract.servicePeriod')->only(['index', 'update']);
        //合同工单
        Route::resource('contract/workOrder', 'contract.workOrder')->only(['index', 'update']);
        //合同工单节点
        Route::resource('contract/workOrderNode', 'contract.workOrderNode')->only(['index', 'update']);
        //操作日志
        Route::resource('action_log', 'action_log.actionLog');
        //商机
        Route::resource('business', 'business.business');
        //企业
        Route::resource('enterprise', 'enterprise.enterprise');
        //产品服务步骤
        Route::resource('step', 'product.productStep');
        //单次服务收款
        Route::resource('collect/single', 'collect.singleCollect');
        //长期服务收款
        Route::resource('collect/long', 'collect.longCollect');
    });
})->middleware('auth');
