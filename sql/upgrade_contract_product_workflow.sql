SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- 合同产品明细升级为合同子业务明细
-- 说明：本脚本用于在现有 speed_contract_product 表上补充子业务、周期、工单与核算字段。
-- 注意：MySQL 5.7 不支持 ADD COLUMN IF NOT EXISTS，重复执行前请先确认字段是否已存在。
-- ----------------------------
ALTER TABLE `speed_contract_product`
ADD COLUMN `product_name` varchar(255) NOT NULL DEFAULT '' COMMENT '产品名称快照' AFTER `product_id`,
ADD COLUMN `period_unit` varchar(20) NOT NULL DEFAULT 'none' COMMENT '周期单位：none无,month月,year年,stage阶段' AFTER `is_core`,
ADD COLUMN `period_total` int(11) NOT NULL DEFAULT 0 COMMENT '服务周期总数' AFTER `period_unit`,
ADD COLUMN `period_generated` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否已生成服务周期：0否,1是' AFTER `period_total`,
ADD COLUMN `work_order_generated` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否已生成工单：0否,1是' AFTER `period_generated`,
ADD COLUMN `work_manager_id` int(11) NOT NULL DEFAULT 0 COMMENT '工单主管ID' AFTER `owner_user_id`,
ADD COLUMN `worker_commission` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '执行人/会计佣金' AFTER `work_manager_bonus`,
ADD COLUMN `service_start_time` int(11) NOT NULL DEFAULT 0 COMMENT '服务开始时间' AFTER `finish_status`,
ADD COLUMN `service_end_time` int(11) NOT NULL DEFAULT 0 COMMENT '服务结束时间' AFTER `service_start_time`,
ADD COLUMN `finish_time` int(11) NOT NULL DEFAULT 0 COMMENT '完结时间' AFTER `service_end_time`,
ADD COLUMN `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序' AFTER `display_step`,
ADD COLUMN `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注' AFTER `sort`,
ADD COLUMN `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间' AFTER `remark`,
ADD COLUMN `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间' AFTER `create_time`,
ADD COLUMN `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间' AFTER `update_time`;

ALTER TABLE `speed_contract_product`
ADD KEY `idx_product_id` (`product_id`),
ADD KEY `idx_owner_user_id` (`owner_user_id`),
ADD KEY `idx_work_manager_id` (`work_manager_id`),
ADD KEY `idx_is_core` (`is_core`),
ADD KEY `idx_delete_time` (`delete_time`);

-- ----------------------------
-- 产品套餐子业务模板表
-- 说明：用于配置套餐产品默认拆分出的子业务，创建合同时可按模板生成 speed_contract_product 明细。
-- ----------------------------
DROP TABLE IF EXISTS `speed_product_package_item`;
CREATE TABLE `speed_product_package_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `package_product_id` int(11) NOT NULL COMMENT '套餐产品ID',
  `child_product_id` int(11) NOT NULL DEFAULT 0 COMMENT '子业务产品ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '子业务名称',
  `service_type` varchar(20) NOT NULL DEFAULT '' COMMENT '服务类型快照',
  `is_core` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否核心业务',
  `category` tinyint(1) NOT NULL DEFAULT 0 COMMENT '类别',
  `unit` tinyint(1) NOT NULL DEFAULT 0 COMMENT '单位',
  `default_number` int(11) NOT NULL DEFAULT 1 COMMENT '默认数量',
  `default_share_amount` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '默认分摊金额',
  `default_internal_cost` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '默认内部成本',
  `default_fixed_cost` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '默认固定成本',
  `default_commission_cost` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '默认佣金成本',
  `default_other_cost` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '默认其他成本',
  `default_sales_commission` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '默认销售提成',
  `default_sales_manager_commission` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '默认销售主管提成',
  `default_work_manager_bonus` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '默认工单主管奖金',
  `default_worker_commission` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '默认执行人/会计佣金',
  `period_unit` varchar(20) NOT NULL DEFAULT 'none' COMMENT '周期单位：none无,month月,year年,stage阶段',
  `period_total` int(11) NOT NULL DEFAULT 0 COMMENT '默认周期数量',
  `auto_create_work_order` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否自动生成工单',
  `auto_create_period` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否自动生成服务周期',
  `flow_template` varchar(255) NOT NULL DEFAULT '' COMMENT '默认流程节点模板，逗号分隔',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：0禁用,1启用',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_package_product_id` (`package_product_id`),
  KEY `idx_child_product_id` (`child_product_id`),
  KEY `idx_service_type` (`service_type`),
  KEY `idx_is_core` (`is_core`),
  KEY `idx_status` (`status`),
  KEY `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='产品套餐子业务模板表';

-- ----------------------------
-- 服务周期表
-- 说明：周期性子业务的执行周期，例如 5年代账下的第1个月到第60个月。
-- ----------------------------
DROP TABLE IF EXISTS `speed_service_period`;
CREATE TABLE `speed_service_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `contract_id` int(11) NOT NULL COMMENT '合同ID',
  `contract_product_id` int(11) NOT NULL COMMENT '合同子业务ID，对应 speed_contract_product.id',
  `period_no` int(11) NOT NULL DEFAULT 1 COMMENT '周期序号',
  `period_name` varchar(100) NOT NULL DEFAULT '' COMMENT '周期名称',
  `period_unit` varchar(20) NOT NULL DEFAULT 'month' COMMENT '周期单位：month月,year年,stage阶段',
  `period_start_time` int(11) NOT NULL DEFAULT 0 COMMENT '周期开始时间',
  `period_end_time` int(11) NOT NULL DEFAULT 0 COMMENT '周期结束时间',
  `owner_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '负责人ID',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '周期状态：0未开始,1办理中,2已完结,3异常',
  `share_amount` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '周期分摊金额',
  `internal_cost` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '周期内部成本',
  `actual_cost` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '周期实际成本',
  `worker_commission` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '周期执行人/会计佣金',
  `finish_time` int(11) NOT NULL DEFAULT 0 COMMENT '完结时间',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_contract_id` (`contract_id`),
  KEY `idx_contract_product_id` (`contract_product_id`),
  KEY `idx_owner_user_id` (`owner_user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_period_no` (`period_no`),
  KEY `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='服务周期表';

-- ----------------------------
-- 工单表
-- 说明：一次性子业务可直接生成工单，周期性子业务可按服务周期生成工单。
-- ----------------------------
DROP TABLE IF EXISTS `speed_work_order`;
CREATE TABLE `speed_work_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `work_order_no` varchar(50) NOT NULL DEFAULT '' COMMENT '工单编号',
  `contract_id` int(11) NOT NULL COMMENT '合同ID',
  `contract_product_id` int(11) NOT NULL COMMENT '合同子业务ID，对应 speed_contract_product.id',
  `service_period_id` int(11) NOT NULL DEFAULT 0 COMMENT '服务周期ID，非周期工单为0',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '工单标题',
  `work_type` varchar(50) NOT NULL DEFAULT '' COMMENT '工单类型',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '工单状态：0未开始,1办理中,2已完结,3异常,4已取消',
  `priority` tinyint(1) NOT NULL DEFAULT 1 COMMENT '优先级：1普通,2重要,3紧急',
  `owner_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '负责人ID',
  `assist_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '协助人ID',
  `work_manager_id` int(11) NOT NULL DEFAULT 0 COMMENT '工单主管ID',
  `plan_start_time` int(11) NOT NULL DEFAULT 0 COMMENT '计划开始时间',
  `plan_end_time` int(11) NOT NULL DEFAULT 0 COMMENT '计划结束时间',
  `actual_start_time` int(11) NOT NULL DEFAULT 0 COMMENT '实际开始时间',
  `actual_end_time` int(11) NOT NULL DEFAULT 0 COMMENT '实际结束时间',
  `finish_time` int(11) NOT NULL DEFAULT 0 COMMENT '完结时间',
  `progress` tinyint(3) NOT NULL DEFAULT 0 COMMENT '进度百分比',
  `current_node_id` int(11) NOT NULL DEFAULT 0 COMMENT '当前节点ID',
  `last_record` varchar(255) NOT NULL DEFAULT '' COMMENT '最后跟进记录',
  `last_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后跟进时间',
  `last_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '最后跟进人ID',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_work_order_no` (`work_order_no`),
  KEY `idx_contract_id` (`contract_id`),
  KEY `idx_contract_product_id` (`contract_product_id`),
  KEY `idx_service_period_id` (`service_period_id`),
  KEY `idx_owner_user_id` (`owner_user_id`),
  KEY `idx_work_manager_id` (`work_manager_id`),
  KEY `idx_status` (`status`),
  KEY `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='工单表';

-- ----------------------------
-- 工单节点表
-- 说明：记录工单的流程节点，例如资料审核、提交申请、审批跟进、指标下发。
-- ----------------------------
DROP TABLE IF EXISTS `speed_work_order_node`;
CREATE TABLE `speed_work_order_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `work_order_id` int(11) NOT NULL COMMENT '工单ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '节点名称',
  `node_key` varchar(50) NOT NULL DEFAULT '' COMMENT '节点标识',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '节点状态：0未开始,1办理中,2已完成,3异常,4跳过',
  `owner_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '节点负责人ID',
  `plan_finish_time` int(11) NOT NULL DEFAULT 0 COMMENT '计划完成时间',
  `finish_time` int(11) NOT NULL DEFAULT 0 COMMENT '完成时间',
  `result` varchar(255) NOT NULL DEFAULT '' COMMENT '节点结果',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_work_order_id` (`work_order_id`),
  KEY `idx_owner_user_id` (`owner_user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='工单节点表';

SET FOREIGN_KEY_CHECKS = 1;
