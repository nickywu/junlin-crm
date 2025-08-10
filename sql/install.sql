
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for speed_action_log
-- ----------------------------
DROP TABLE IF EXISTS `speed_action_log`;
CREATE TABLE `speed_action_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `action_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作名称',
  `data_id` int(11) NOT NULL COMMENT '操作数据id',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型（1线索、2客户、3联系人、4商机、5合同、6产品）',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 273 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '操作日志表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_action_log
-- ----------------------------
INSERT INTO `speed_action_log` VALUES (264, '新增线索『线索示例数据』', '新增', 18, '1', 1, 1754449688);
INSERT INTO `speed_action_log` VALUES (265, '新增客户『客户示例数据』', '新增', 39, '2', 1, 1754449740);
INSERT INTO `speed_action_log` VALUES (266, '新增联系人『联系人示例数据』', '新增', 7, '3', 1, 1754449941);
INSERT INTO `speed_action_log` VALUES (267, '新增产品『产品示例』', '新增', 25, '6', 1, 1754449970);
INSERT INTO `speed_action_log` VALUES (268, '新增商机『商机示例数据1』', '新增', 13, '4', 1, 1754450071);
INSERT INTO `speed_action_log` VALUES (269, '新增商机『商机示例数据2』', '新增', 14, '4', 1, 1754450093);
INSERT INTO `speed_action_log` VALUES (270, '结束商机『商机示例数据2』至『赢单』状态', '结束商机', 13, '4', 1, 1754450283);
INSERT INTO `speed_action_log` VALUES (271, '新增合同『合同示例数据』', '新增', 15, '5', 1, 1754450342);
INSERT INTO `speed_action_log` VALUES (272, '更改客户『客户示例数据』成交状态为『已成交』', '更改成交状态', 39, '2', 1, 1754489703);

-- ----------------------------
-- Table structure for speed_auth_access
-- ----------------------------
DROP TABLE IF EXISTS `speed_auth_access`;
CREATE TABLE `speed_auth_access`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `menu_id` int(11) NOT NULL DEFAULT 0 COMMENT '菜单id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6114 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限菜单关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_auth_access
-- ----------------------------
INSERT INTO `speed_auth_access` VALUES (5579, 1, 107);
INSERT INTO `speed_auth_access` VALUES (5580, 1, 106);
INSERT INTO `speed_auth_access` VALUES (5581, 1, 119);
INSERT INTO `speed_auth_access` VALUES (5582, 1, 114);
INSERT INTO `speed_auth_access` VALUES (5583, 1, 120);
INSERT INTO `speed_auth_access` VALUES (5584, 1, 116);
INSERT INTO `speed_auth_access` VALUES (5585, 1, 130);
INSERT INTO `speed_auth_access` VALUES (5586, 1, 38);
INSERT INTO `speed_auth_access` VALUES (5587, 1, 108);
INSERT INTO `speed_auth_access` VALUES (5588, 1, 80);
INSERT INTO `speed_auth_access` VALUES (5590, 1, 40);
INSERT INTO `speed_auth_access` VALUES (5592, 1, 81);
INSERT INTO `speed_auth_access` VALUES (5593, 1, 45);
INSERT INTO `speed_auth_access` VALUES (5594, 1, 46);
INSERT INTO `speed_auth_access` VALUES (5595, 1, 47);
INSERT INTO `speed_auth_access` VALUES (5596, 1, 110);
INSERT INTO `speed_auth_access` VALUES (5597, 1, 82);
INSERT INTO `speed_auth_access` VALUES (5598, 1, 53);
INSERT INTO `speed_auth_access` VALUES (5599, 1, 54);
INSERT INTO `speed_auth_access` VALUES (5600, 1, 78);
INSERT INTO `speed_auth_access` VALUES (5601, 1, 36);
INSERT INTO `speed_auth_access` VALUES (5602, 1, 105);
INSERT INTO `speed_auth_access` VALUES (5603, 1, 113);
INSERT INTO `speed_auth_access` VALUES (5604, 1, 32);
INSERT INTO `speed_auth_access` VALUES (5605, 1, 16);
INSERT INTO `speed_auth_access` VALUES (5606, 1, 20);
INSERT INTO `speed_auth_access` VALUES (5607, 1, 21);
INSERT INTO `speed_auth_access` VALUES (5608, 1, 50);
INSERT INTO `speed_auth_access` VALUES (5609, 1, 156);
INSERT INTO `speed_auth_access` VALUES (5611, 1, 14);
INSERT INTO `speed_auth_access` VALUES (5612, 1, 77);
INSERT INTO `speed_auth_access` VALUES (5613, 1, 28);
INSERT INTO `speed_auth_access` VALUES (5614, 1, 29);
INSERT INTO `speed_auth_access` VALUES (5615, 1, 30);
INSERT INTO `speed_auth_access` VALUES (5616, 1, 13);
INSERT INTO `speed_auth_access` VALUES (5630, 1, 184);
INSERT INTO `speed_auth_access` VALUES (5632, 1, 186);
INSERT INTO `speed_auth_access` VALUES (5684, 1, 236);
INSERT INTO `speed_auth_access` VALUES (5716, 1, 238);
INSERT INTO `speed_auth_access` VALUES (5717, 1, 239);
INSERT INTO `speed_auth_access` VALUES (5718, 1, 240);
INSERT INTO `speed_auth_access` VALUES (5719, 1, 241);
INSERT INTO `speed_auth_access` VALUES (5720, 1, 242);
INSERT INTO `speed_auth_access` VALUES (5736, 1, 258);
INSERT INTO `speed_auth_access` VALUES (5737, 1, 259);
INSERT INTO `speed_auth_access` VALUES (5738, 1, 260);
INSERT INTO `speed_auth_access` VALUES (5739, 1, 261);
INSERT INTO `speed_auth_access` VALUES (5740, 1, 262);
INSERT INTO `speed_auth_access` VALUES (5752, 1, 274);
INSERT INTO `speed_auth_access` VALUES (5753, 1, 275);
INSERT INTO `speed_auth_access` VALUES (5754, 1, 276);
INSERT INTO `speed_auth_access` VALUES (5755, 1, 277);
INSERT INTO `speed_auth_access` VALUES (5756, 1, 278);
INSERT INTO `speed_auth_access` VALUES (5763, 1, 285);
INSERT INTO `speed_auth_access` VALUES (5764, 1, 286);
INSERT INTO `speed_auth_access` VALUES (5765, 1, 287);
INSERT INTO `speed_auth_access` VALUES (5766, 1, 288);
INSERT INTO `speed_auth_access` VALUES (5767, 1, 289);
INSERT INTO `speed_auth_access` VALUES (5768, 1, 290);
INSERT INTO `speed_auth_access` VALUES (5769, 1, 291);
INSERT INTO `speed_auth_access` VALUES (5770, 1, 292);
INSERT INTO `speed_auth_access` VALUES (5771, 1, 293);
INSERT INTO `speed_auth_access` VALUES (5772, 1, 294);
INSERT INTO `speed_auth_access` VALUES (5773, 1, 295);
INSERT INTO `speed_auth_access` VALUES (5774, 1, 296);
INSERT INTO `speed_auth_access` VALUES (5775, 1, 297);
INSERT INTO `speed_auth_access` VALUES (5776, 1, 298);
INSERT INTO `speed_auth_access` VALUES (5777, 1, 299);
INSERT INTO `speed_auth_access` VALUES (5778, 1, 300);
INSERT INTO `speed_auth_access` VALUES (5779, 1, 301);
INSERT INTO `speed_auth_access` VALUES (5780, 1, 302);
INSERT INTO `speed_auth_access` VALUES (5781, 1, 303);
INSERT INTO `speed_auth_access` VALUES (5782, 1, 304);
INSERT INTO `speed_auth_access` VALUES (5783, 1, 305);
INSERT INTO `speed_auth_access` VALUES (5784, 1, 306);
INSERT INTO `speed_auth_access` VALUES (5785, 1, 307);
INSERT INTO `speed_auth_access` VALUES (5786, 1, 308);
INSERT INTO `speed_auth_access` VALUES (5964, 5, 242);
INSERT INTO `speed_auth_access` VALUES (5965, 5, 241);
INSERT INTO `speed_auth_access` VALUES (5966, 5, 240);
INSERT INTO `speed_auth_access` VALUES (5967, 5, 239);
INSERT INTO `speed_auth_access` VALUES (5968, 5, 300);
INSERT INTO `speed_auth_access` VALUES (5969, 5, 301);
INSERT INTO `speed_auth_access` VALUES (5970, 5, 261);
INSERT INTO `speed_auth_access` VALUES (5971, 5, 262);
INSERT INTO `speed_auth_access` VALUES (5972, 5, 260);
INSERT INTO `speed_auth_access` VALUES (5973, 5, 259);
INSERT INTO `speed_auth_access` VALUES (5974, 5, 303);
INSERT INTO `speed_auth_access` VALUES (5975, 5, 302);
INSERT INTO `speed_auth_access` VALUES (5976, 5, 278);
INSERT INTO `speed_auth_access` VALUES (5977, 5, 277);
INSERT INTO `speed_auth_access` VALUES (5978, 5, 276);
INSERT INTO `speed_auth_access` VALUES (5979, 5, 275);
INSERT INTO `speed_auth_access` VALUES (5980, 5, 304);
INSERT INTO `speed_auth_access` VALUES (5981, 5, 288);
INSERT INTO `speed_auth_access` VALUES (5982, 5, 287);
INSERT INTO `speed_auth_access` VALUES (5983, 5, 286);
INSERT INTO `speed_auth_access` VALUES (5984, 5, 289);
INSERT INTO `speed_auth_access` VALUES (5985, 5, 307);
INSERT INTO `speed_auth_access` VALUES (5986, 5, 306);
INSERT INTO `speed_auth_access` VALUES (5987, 5, 305);
INSERT INTO `speed_auth_access` VALUES (5988, 5, 294);
INSERT INTO `speed_auth_access` VALUES (5989, 5, 293);
INSERT INTO `speed_auth_access` VALUES (5990, 5, 292);
INSERT INTO `speed_auth_access` VALUES (5991, 5, 291);
INSERT INTO `speed_auth_access` VALUES (5992, 5, 308);
INSERT INTO `speed_auth_access` VALUES (5993, 5, 299);
INSERT INTO `speed_auth_access` VALUES (5994, 5, 298);
INSERT INTO `speed_auth_access` VALUES (5995, 5, 297);
INSERT INTO `speed_auth_access` VALUES (5996, 5, 296);
INSERT INTO `speed_auth_access` VALUES (5997, 5, 238);
INSERT INTO `speed_auth_access` VALUES (5998, 5, 258);
INSERT INTO `speed_auth_access` VALUES (5999, 5, 274);
INSERT INTO `speed_auth_access` VALUES (6000, 5, 285);
INSERT INTO `speed_auth_access` VALUES (6001, 5, 290);
INSERT INTO `speed_auth_access` VALUES (6002, 5, 295);
INSERT INTO `speed_auth_access` VALUES (6003, 3, 242);
INSERT INTO `speed_auth_access` VALUES (6004, 3, 241);
INSERT INTO `speed_auth_access` VALUES (6005, 3, 240);
INSERT INTO `speed_auth_access` VALUES (6006, 3, 239);
INSERT INTO `speed_auth_access` VALUES (6007, 3, 300);
INSERT INTO `speed_auth_access` VALUES (6008, 3, 301);
INSERT INTO `speed_auth_access` VALUES (6009, 3, 261);
INSERT INTO `speed_auth_access` VALUES (6010, 3, 262);
INSERT INTO `speed_auth_access` VALUES (6011, 3, 260);
INSERT INTO `speed_auth_access` VALUES (6012, 3, 259);
INSERT INTO `speed_auth_access` VALUES (6013, 3, 303);
INSERT INTO `speed_auth_access` VALUES (6014, 3, 302);
INSERT INTO `speed_auth_access` VALUES (6015, 3, 278);
INSERT INTO `speed_auth_access` VALUES (6016, 3, 277);
INSERT INTO `speed_auth_access` VALUES (6017, 3, 276);
INSERT INTO `speed_auth_access` VALUES (6018, 3, 275);
INSERT INTO `speed_auth_access` VALUES (6019, 3, 304);
INSERT INTO `speed_auth_access` VALUES (6020, 3, 288);
INSERT INTO `speed_auth_access` VALUES (6021, 3, 287);
INSERT INTO `speed_auth_access` VALUES (6022, 3, 286);
INSERT INTO `speed_auth_access` VALUES (6023, 3, 289);
INSERT INTO `speed_auth_access` VALUES (6024, 3, 307);
INSERT INTO `speed_auth_access` VALUES (6025, 3, 306);
INSERT INTO `speed_auth_access` VALUES (6026, 3, 305);
INSERT INTO `speed_auth_access` VALUES (6027, 3, 294);
INSERT INTO `speed_auth_access` VALUES (6028, 3, 293);
INSERT INTO `speed_auth_access` VALUES (6029, 3, 292);
INSERT INTO `speed_auth_access` VALUES (6030, 3, 291);
INSERT INTO `speed_auth_access` VALUES (6031, 3, 308);
INSERT INTO `speed_auth_access` VALUES (6032, 3, 299);
INSERT INTO `speed_auth_access` VALUES (6033, 3, 298);
INSERT INTO `speed_auth_access` VALUES (6034, 3, 297);
INSERT INTO `speed_auth_access` VALUES (6035, 3, 296);
INSERT INTO `speed_auth_access` VALUES (6036, 3, 114);
INSERT INTO `speed_auth_access` VALUES (6037, 3, 106);
INSERT INTO `speed_auth_access` VALUES (6038, 3, 238);
INSERT INTO `speed_auth_access` VALUES (6039, 3, 258);
INSERT INTO `speed_auth_access` VALUES (6040, 3, 274);
INSERT INTO `speed_auth_access` VALUES (6041, 3, 285);
INSERT INTO `speed_auth_access` VALUES (6042, 3, 290);
INSERT INTO `speed_auth_access` VALUES (6043, 3, 295);
INSERT INTO `speed_auth_access` VALUES (6044, 3, 105);
INSERT INTO `speed_auth_access` VALUES (6045, 3, 113);
INSERT INTO `speed_auth_access` VALUES (6046, 3, 156);
INSERT INTO `speed_auth_access` VALUES (6047, 3, 13);
INSERT INTO `speed_auth_access` VALUES (6048, 2, 242);
INSERT INTO `speed_auth_access` VALUES (6049, 2, 241);
INSERT INTO `speed_auth_access` VALUES (6050, 2, 240);
INSERT INTO `speed_auth_access` VALUES (6051, 2, 239);
INSERT INTO `speed_auth_access` VALUES (6052, 2, 300);
INSERT INTO `speed_auth_access` VALUES (6053, 2, 301);
INSERT INTO `speed_auth_access` VALUES (6054, 2, 261);
INSERT INTO `speed_auth_access` VALUES (6055, 2, 262);
INSERT INTO `speed_auth_access` VALUES (6056, 2, 260);
INSERT INTO `speed_auth_access` VALUES (6057, 2, 259);
INSERT INTO `speed_auth_access` VALUES (6058, 2, 303);
INSERT INTO `speed_auth_access` VALUES (6059, 2, 302);
INSERT INTO `speed_auth_access` VALUES (6060, 2, 278);
INSERT INTO `speed_auth_access` VALUES (6061, 2, 277);
INSERT INTO `speed_auth_access` VALUES (6062, 2, 276);
INSERT INTO `speed_auth_access` VALUES (6063, 2, 275);
INSERT INTO `speed_auth_access` VALUES (6064, 2, 304);
INSERT INTO `speed_auth_access` VALUES (6065, 2, 288);
INSERT INTO `speed_auth_access` VALUES (6066, 2, 287);
INSERT INTO `speed_auth_access` VALUES (6067, 2, 286);
INSERT INTO `speed_auth_access` VALUES (6068, 2, 289);
INSERT INTO `speed_auth_access` VALUES (6069, 2, 307);
INSERT INTO `speed_auth_access` VALUES (6070, 2, 306);
INSERT INTO `speed_auth_access` VALUES (6071, 2, 305);
INSERT INTO `speed_auth_access` VALUES (6072, 2, 294);
INSERT INTO `speed_auth_access` VALUES (6073, 2, 293);
INSERT INTO `speed_auth_access` VALUES (6074, 2, 292);
INSERT INTO `speed_auth_access` VALUES (6075, 2, 291);
INSERT INTO `speed_auth_access` VALUES (6076, 2, 308);
INSERT INTO `speed_auth_access` VALUES (6077, 2, 299);
INSERT INTO `speed_auth_access` VALUES (6078, 2, 298);
INSERT INTO `speed_auth_access` VALUES (6079, 2, 297);
INSERT INTO `speed_auth_access` VALUES (6080, 2, 296);
INSERT INTO `speed_auth_access` VALUES (6081, 2, 114);
INSERT INTO `speed_auth_access` VALUES (6082, 2, 106);
INSERT INTO `speed_auth_access` VALUES (6083, 2, 107);
INSERT INTO `speed_auth_access` VALUES (6084, 2, 119);
INSERT INTO `speed_auth_access` VALUES (6085, 2, 80);
INSERT INTO `speed_auth_access` VALUES (6086, 2, 40);
INSERT INTO `speed_auth_access` VALUES (6087, 2, 105);
INSERT INTO `speed_auth_access` VALUES (6088, 2, 238);
INSERT INTO `speed_auth_access` VALUES (6089, 2, 258);
INSERT INTO `speed_auth_access` VALUES (6090, 2, 274);
INSERT INTO `speed_auth_access` VALUES (6091, 2, 285);
INSERT INTO `speed_auth_access` VALUES (6092, 2, 290);
INSERT INTO `speed_auth_access` VALUES (6093, 2, 295);
INSERT INTO `speed_auth_access` VALUES (6094, 2, 81);
INSERT INTO `speed_auth_access` VALUES (6095, 2, 45);
INSERT INTO `speed_auth_access` VALUES (6096, 2, 50);
INSERT INTO `speed_auth_access` VALUES (6097, 2, 82);
INSERT INTO `speed_auth_access` VALUES (6098, 2, 54);
INSERT INTO `speed_auth_access` VALUES (6099, 2, 53);
INSERT INTO `speed_auth_access` VALUES (6100, 2, 110);
INSERT INTO `speed_auth_access` VALUES (6101, 2, 16);
INSERT INTO `speed_auth_access` VALUES (6102, 2, 38);
INSERT INTO `speed_auth_access` VALUES (6103, 2, 130);
INSERT INTO `speed_auth_access` VALUES (6104, 2, 14);
INSERT INTO `speed_auth_access` VALUES (6105, 2, 77);
INSERT INTO `speed_auth_access` VALUES (6106, 2, 30);
INSERT INTO `speed_auth_access` VALUES (6107, 2, 29);
INSERT INTO `speed_auth_access` VALUES (6108, 2, 28);
INSERT INTO `speed_auth_access` VALUES (6109, 2, 113);
INSERT INTO `speed_auth_access` VALUES (6110, 2, 20);
INSERT INTO `speed_auth_access` VALUES (6111, 2, 21);
INSERT INTO `speed_auth_access` VALUES (6112, 2, 156);
INSERT INTO `speed_auth_access` VALUES (6113, 2, 13);

-- ----------------------------
-- Table structure for speed_business
-- ----------------------------
DROP TABLE IF EXISTS `speed_business`;
CREATE TABLE `speed_business`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '商机名称',
  `customer_id` int(11) NOT NULL COMMENT '客户id',
  `group_id` int(11) NOT NULL COMMENT '商机组id',
  `stage_id` int(11) NOT NULL COMMENT '商机阶段id',
  `is_end` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否结束（1赢单2输单3无效）',
  `create_user_id` int(11) NOT NULL COMMENT '创建人',
  `owner_user_id` int(11) NOT NULL COMMENT '负责人',
  `money` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '商机金额',
  `total_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '产品总价',
  `deal_time` int(11) NOT NULL DEFAULT 0 COMMENT '预计成交时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `last_record` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后跟进记录',
  `last_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后跟进时间',
  `last_user_id` int(11) NULL DEFAULT NULL COMMENT '最后跟进人',
  `stage_time` int(11) NOT NULL DEFAULT 0 COMMENT '阶段推进时间',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_business
-- ----------------------------
INSERT INTO `speed_business` VALUES (13, '商机示例数据2', 39, 1, 4, 1, 1, 1, 99.00, 99.00, 1754496000, '', '', 0, NULL, 0, 1754450071, 1754450071, 0);
INSERT INTO `speed_business` VALUES (14, '商机示例数据1', 39, 1, 5, 0, 1, 1, 99.00, 99.00, 1754582400, '', '', 0, NULL, 0, 1754450093, 1754450093, 0);

-- ----------------------------
-- Table structure for speed_business_group
-- ----------------------------
DROP TABLE IF EXISTS `speed_business_group`;
CREATE TABLE `speed_business_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `create_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '添加人',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态，0启用，1禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_business_group
-- ----------------------------
INSERT INTO `speed_business_group` VALUES (1, '默认销售商机组', 1, 1540973371, 1540973371, 1540973371, 1);

-- ----------------------------
-- Table structure for speed_business_product
-- ----------------------------
DROP TABLE IF EXISTS `speed_business_product`;
CREATE TABLE `speed_business_product`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `product_id` int(11) NOT NULL COMMENT '产品id',
  `business_id` int(11) NOT NULL COMMENT '商机id',
  `number` int(11) NOT NULL COMMENT '产品数量',
  `price` decimal(10, 2) NOT NULL COMMENT '产品价格',
  `sale_price` decimal(10, 2) NOT NULL COMMENT '销售价格',
  `discount` int(11) NOT NULL DEFAULT 0 COMMENT '折扣',
  `total_price` decimal(10, 2) NOT NULL COMMENT '价格合计',
  `unit` tinyint(4) NULL DEFAULT NULL COMMENT '单位',
  `category` tinyint(4) NULL DEFAULT NULL COMMENT '类别',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_business_product
-- ----------------------------
INSERT INTO `speed_business_product` VALUES (35, 25, 13, 1, 99.00, 99.00, 0, 99.00, 2, 1);
INSERT INTO `speed_business_product` VALUES (36, 25, 14, 1, 99.00, 99.00, 0, 99.00, 2, 1);

-- ----------------------------
-- Table structure for speed_business_stage
-- ----------------------------
DROP TABLE IF EXISTS `speed_business_stage`;
CREATE TABLE `speed_business_stage`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `rate` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '赢单率',
  `sort` tinyint(4) NOT NULL DEFAULT 0 COMMENT '排序',
  `group_id` int(11) NOT NULL COMMENT '商机组id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商机状态' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_business_stage
-- ----------------------------
INSERT INTO `speed_business_stage` VALUES (1, '赢单', '100', 99, 0);
INSERT INTO `speed_business_stage` VALUES (2, '输单', '0', 100, 0);
INSERT INTO `speed_business_stage` VALUES (3, '无效', '0', 101, 0);
INSERT INTO `speed_business_stage` VALUES (4, '验证客户', '20', 1, 1);
INSERT INTO `speed_business_stage` VALUES (5, '方案/报价', '30', 3, 1);
INSERT INTO `speed_business_stage` VALUES (6, '需求分析', '15', 2, 1);
INSERT INTO `speed_business_stage` VALUES (7, '谈判审核', '30', 4, 1);

-- ----------------------------
-- Table structure for speed_clues
-- ----------------------------
DROP TABLE IF EXISTS `speed_clues`;
CREATE TABLE `speed_clues`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '线索ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '线索名称',
  `source` tinyint(4) NULL DEFAULT NULL COMMENT '线索来源',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `telephone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '固定电话',
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `level` tinyint(4) NULL DEFAULT NULL COMMENT '线索等级',
  `industry` tinyint(4) NULL DEFAULT NULL COMMENT '所属行业',
  `addr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  `owner_user_id` int(11) NOT NULL COMMENT '线索负责人',
  `create_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `gps` varchar(28) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'GPS坐标',
  `is_transform` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否转换（0未转换，1已转换）',
  `last_record` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后跟进记录',
  `last_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后跟进时间',
  `last_user_id` int(11) NULL DEFAULT NULL COMMENT '最后跟进人',
  `customer_id` int(11) NULL DEFAULT NULL COMMENT '已转换的客户id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `create_user_id`(`create_user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '销售线索信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_clues
-- ----------------------------
INSERT INTO `speed_clues` VALUES (18, '线索示例数据', 1, '18088886666', '10000', '', 1, 1, '', '', 1754449688, 0, 1, 1, '', 0, '线索数据跟进示例', 1754449714, 1, NULL);

-- ----------------------------
-- Table structure for speed_contacts
-- ----------------------------
DROP TABLE IF EXISTS `speed_contacts`;
CREATE TABLE `speed_contacts`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '联系人ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `customer_id` int(11) NOT NULL DEFAULT 0 COMMENT '所属客户ID',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号码',
  `telephone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电话号码',
  `job` tinyint(1) NULL DEFAULT NULL COMMENT '职位',
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `gender` tinyint(4) NULL DEFAULT NULL COMMENT '性别',
  `addr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址',
  `gps` varchar(28) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'GPS坐标',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `create_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `owner_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '负责人ID',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `last_time` int(11) NULL DEFAULT NULL COMMENT '最后跟进时间',
  `last_record` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后跟进记录',
  `last_user_id` int(11) NULL DEFAULT NULL COMMENT '最后跟进人',
  `is_decision` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否决策人',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `customer_id`(`customer_id`) USING BTREE,
  INDEX `create_user_id`(`create_user_id`) USING BTREE,
  INDEX `respon_user_id`(`owner_user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '客户联系人信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_contacts
-- ----------------------------
INSERT INTO `speed_contacts` VALUES (7, '联系人示例数据', 39, '18077778888', '10010', 3, '', 1, '', '', '', 1, 1, 1754449941, 0, 1754449941, NULL, '', NULL, 1);

-- ----------------------------
-- Table structure for speed_contract
-- ----------------------------
DROP TABLE IF EXISTS `speed_contract`;
CREATE TABLE `speed_contract`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '合同名称',
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '合同编号',
  `business_id` int(11) NOT NULL COMMENT '商机id',
  `signing_time` int(11) NOT NULL COMMENT '签约时间',
  `start_time` int(11) NOT NULL COMMENT '合同开始时间',
  `end_time` int(11) NOT NULL COMMENT '合同结束时间',
  `status` tinyint(1) NOT NULL COMMENT '合同状态（0草稿、1通过、2无效）',
  `customer_id` int(11) NOT NULL COMMENT '客户id',
  `owner_user_id` int(11) NOT NULL COMMENT '负责人id',
  `order_user_id` int(11) NOT NULL COMMENT '公司签约人',
  `contacts_id` int(11) NOT NULL COMMENT '客户签约人（联系人id）',
  `money` decimal(10, 2) NOT NULL COMMENT '合同金额',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '合同备注',
  `last_record` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后跟进记录',
  `last_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后跟进时间',
  `last_user_id` int(11) NULL DEFAULT NULL COMMENT '最后跟进人',
  `total_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '产品总价',
  `delete_time` int(11) NULL DEFAULT 0 COMMENT '删除时间',
  `create_user_id` int(11) NOT NULL COMMENT '创建人ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `编号唯一索引`(`order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '合同' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_contract
-- ----------------------------
INSERT INTO `speed_contract` VALUES (15, '合同示例数据', 'H250806001', 14, 1754409600, 1754409600, 1785945600, 1, 39, 1, 1, 7, 99.00, '', '', 0, NULL, 99.00, 0, 1, 1754450342, 1754450342);

-- ----------------------------
-- Table structure for speed_contract_product
-- ----------------------------
DROP TABLE IF EXISTS `speed_contract_product`;
CREATE TABLE `speed_contract_product`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL COMMENT '合同ID',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `price` decimal(18, 2) NOT NULL DEFAULT 0.00 COMMENT '产品单价',
  `sale_price` decimal(18, 2) NOT NULL DEFAULT 0.00 COMMENT '销售价格',
  `number` int(11) NOT NULL DEFAULT 0 COMMENT '数量',
  `discount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '折扣',
  `unit` tinyint(1) NOT NULL COMMENT '单位',
  `total_price` decimal(18, 2) NOT NULL DEFAULT 0.00 COMMENT '总价',
  `category` tinyint(1) NOT NULL COMMENT '类别',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_contract_product
-- ----------------------------
INSERT INTO `speed_contract_product` VALUES (24, 15, 25, 99.00, 99.00, 1, 0.00, 2, 99.00, 1);

-- ----------------------------
-- Table structure for speed_customer
-- ----------------------------
DROP TABLE IF EXISTS `speed_customer`;
CREATE TABLE `speed_customer`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '客户ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '客户名称',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号码',
  `telephone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电话号码',
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `source` tinyint(4) NULL DEFAULT NULL COMMENT '客户来源',
  `level` tinyint(4) NULL DEFAULT NULL COMMENT '客户等级',
  `industry` tinyint(4) NULL DEFAULT NULL COMMENT '所属行业',
  `addr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '客户地址',
  `gps` varchar(28) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'GPS坐标',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `create_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `owner_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '负责人ID',
  `last_record` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后跟进记录',
  `last_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后跟进时间',
  `last_user_id` int(11) NULL DEFAULT NULL COMMENT '最后跟进人',
  `transfer_count` int(11) NOT NULL DEFAULT 0 COMMENT '转移次数',
  `deal_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '成交状态(0:未成交 1:已成交 2:已流失)',
  `is_receive` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否分配(0:否 1:是)',
  `receive_time` int(11) NOT NULL DEFAULT 0 COMMENT '分配时间/领取时间',
  `deal_time` int(11) NULL DEFAULT NULL COMMENT '成交时间',
  `next_time` int(11) NULL DEFAULT NULL COMMENT '下次联系时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `create_user_id`(`create_user_id`) USING BTREE COMMENT '创建人索引',
  INDEX `respon_user_id`(`owner_user_id`) USING BTREE COMMENT '负责人索引'
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '客户信息主表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_customer
-- ----------------------------
INSERT INTO `speed_customer` VALUES (39, '客户示例数据', '18088886666', '10086', '', 1, 1, 1, '', '', '', 1754449740, 0, 1754449740, 1, 1, '客户跟进示例', 1754449876, 1, 0, 1, 1, 1754449740, 1754489703, NULL);

-- ----------------------------
-- Table structure for speed_department
-- ----------------------------
DROP TABLE IF EXISTS `speed_department`;
CREATE TABLE `speed_department`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '部门名称',
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父级部门',
  `sort` smallint(6) NOT NULL DEFAULT 8 COMMENT '部门排序',
  `leader_id` int(11) NOT NULL DEFAULT 0 COMMENT '部门负责人',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '部门表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_department
-- ----------------------------
INSERT INTO `speed_department` VALUES (1, '人事部', 4, 8, 9);
INSERT INTO `speed_department` VALUES (2, '财务部', 4, 8, 22);
INSERT INTO `speed_department` VALUES (3, '技术部', 4, 8, 11);
INSERT INTO `speed_department` VALUES (4, '总公司', 0, 8, 2);
INSERT INTO `speed_department` VALUES (5, '市场部', 4, 8, 1);
INSERT INTO `speed_department` VALUES (7, '上海分公司', 0, 8, 1);
INSERT INTO `speed_department` VALUES (8, '广东分公司', 0, 8, 1);
INSERT INTO `speed_department` VALUES (13, '北京分公司', 0, 8, 0);
INSERT INTO `speed_department` VALUES (14, '人事部', 7, 8, 0);
INSERT INTO `speed_department` VALUES (15, '研发部', 4, 8, 0);
INSERT INTO `speed_department` VALUES (16, '技术部', 7, 8, 0);
INSERT INTO `speed_department` VALUES (17, '开发部', 8, 8, 0);
INSERT INTO `speed_department` VALUES (18, '人事部', 8, 8, 22);
INSERT INTO `speed_department` VALUES (19, '人事部', 13, 8, 20);

-- ----------------------------
-- Table structure for speed_dict
-- ----------------------------
DROP TABLE IF EXISTS `speed_dict`;
CREATE TABLE `speed_dict`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典类型',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '中文名称',
  `value` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典属性值',
  `sort` smallint(6) NOT NULL DEFAULT 8 COMMENT '排序',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `color` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典组件颜色',
  `widget_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典组件类型',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除，0未删除，1已删除',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态，1启用，2禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 592 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_dict
-- ----------------------------
INSERT INTO `speed_dict` VALUES (75, 'dict_type', '字典类型', 'dict_type', 8, '', '', '', 0, 1);
INSERT INTO `speed_dict` VALUES (250, 'dict_type', '菜单类型', 'menu_type', 8, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` VALUES (251, 'menu_type', '目录', '0', 2, '', 'green', 'tag', 0, 1);
INSERT INTO `speed_dict` VALUES (252, 'menu_type', '菜单', '1', 1, '', 'blue', 'tag', 0, 1);
INSERT INTO `speed_dict` VALUES (253, 'menu_type', '权限', '2', 3, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` VALUES (429, 'gender', '未知', '0', 3, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (431, 'gender', '男', '1', 1, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (432, 'gender', '女', '2', 2, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (495, 'dict_type', '职务', 'job', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (498, 'dict_type', '性别', 'gender', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (537, 'job', '部门经理', '2', 2, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (538, 'job', '‌总经理', '3', 1, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (539, 'job', '‌主管', '4', 3, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (545, 'dict_type', '来源', 'source', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (546, 'source', '搜索引擎', '1', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (547, 'source', '广告引流', '2', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (548, 'dict_type', '客户级别', 'level', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (549, 'level', 'VIP客户', '1', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (550, 'level', '重点客户', '2', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (551, 'level', '普通客户', '3', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (552, 'dict_type', '客户行业', 'industry', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (553, 'industry', '科技/互联网', '1', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (554, 'industry', '通信', '2', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (555, 'industry', '汽车行业', '3', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (556, 'dict_type', '跟进沟通类型', 'recoed_category', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (557, 'recoed_category', '打电话', '1', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (558, 'recoed_category', '微信联系', '2', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (559, 'recoed_category', '当面拜访', '3', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (564, 'dict_type', '客户成交状态', 'cust_deal_status', 8, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` VALUES (565, 'cust_deal_status', '未成交', '0', 2, '', 'error', 'tag', 0, 1);
INSERT INTO `speed_dict` VALUES (566, 'cust_deal_status', '已成交', '1', 1, '', 'success', 'tag', 0, 1);
INSERT INTO `speed_dict` VALUES (567, 'cust_deal_status', '已流失', '2', 3, '', '', 'tag', 0, 1);
INSERT INTO `speed_dict` VALUES (569, 'dict_type', '产品分类', 'product_category', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (570, 'product_category', '默认', '1', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (572, 'dict_type', '产品单位', 'product_unit', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (573, 'product_unit', '件', '1', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (574, 'product_unit', '个', '2', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (575, 'dict_type', '合同状态', 'contract_status', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (576, 'contract_status', '草稿', '0', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (577, 'contract_status', '通过', '1', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (579, 'contract_status', '无效', '2', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (581, 'source', '个人资源', '4', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (582, 'source', '公司资源', '5', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (583, 'source', '线上注册', '6', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (584, 'industry', '房地产', '4', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (585, 'industry', '金融业', '5', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (588, 'level', '潜在客户', '4', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (589, 'product_unit', '款', '3', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (590, 'product_unit', '只', '4', 8, '', '', 'text', 0, 1);
INSERT INTO `speed_dict` VALUES (591, 'product_unit', '把', '6', 8, '', '', 'text', 0, 1);

-- ----------------------------
-- Table structure for speed_file
-- ----------------------------
DROP TABLE IF EXISTS `speed_file`;
CREATE TABLE `speed_file`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文件地址',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文件路径',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'mime类型',
  `file_ext` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文件扩展名',
  `file_size` int(11) NOT NULL DEFAULT 0 COMMENT '文件大小',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文件名称',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '上传时间',
  `user_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '上传用户id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 44 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for speed_generate_field
-- ----------------------------
DROP TABLE IF EXISTS `speed_generate_field`;
CREATE TABLE `speed_generate_field`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `table_id` int(11) NOT NULL DEFAULT 0 COMMENT '表id',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段名称',
  `comment` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段描述',
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段类型',
  `is_required` tinyint(1) NULL DEFAULT 0 COMMENT '是否必填 0-非必填 1-必填',
  `is_pk` tinyint(1) NULL DEFAULT 0 COMMENT '是否为主键 0-不是 1-是',
  `is_insert` tinyint(1) NULL DEFAULT 0 COMMENT '是否为插入字段 0-不是 1-是',
  `is_list` tinyint(1) NULL DEFAULT 0 COMMENT '是否为列表字段 0-不是 1-是',
  `is_search` tinyint(1) NULL DEFAULT 0 COMMENT '是否为查询字段 0-不是 1-是',
  `search_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '=' COMMENT '查询类型',
  `show_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'input' COMMENT '显示类型',
  `dict_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '字典类型',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 368 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '代码生成表字段信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for speed_generate_table
-- ----------------------------
DROP TABLE IF EXISTS `speed_generate_table`;
CREATE TABLE `speed_generate_table`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `table_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表名称',
  `table_comment` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表描述',
  `generate_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '生成方式  0-压缩包下载 1-生成到模块',
  `module_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '模块名',
  `class_dir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '类目录名',
  `create__userid` int(11) NULL DEFAULT 0 COMMENT '创建人id',
  `menu_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `menu_type` tinyint(1) NULL DEFAULT NULL COMMENT '生成菜单类型。0自动构建，1手动添加',
  `menu_pid` int(11) NULL DEFAULT NULL COMMENT '上级菜单id',
  `delete_type` tinyint(1) NOT NULL COMMENT '删除类型，0物理删除，1软删除',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 58 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '代码生成表信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for speed_login_log
-- ----------------------------
DROP TABLE IF EXISTS `speed_login_log`;
CREATE TABLE `speed_login_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录账号',
  `login_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录ip',
  `browser` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '浏览器',
  `os` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '操作系统',
  `login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间',
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '登录日志表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_login_log
-- ----------------------------

-- ----------------------------
-- Table structure for speed_menu
-- ----------------------------
DROP TABLE IF EXISTS `speed_menu`;
CREATE TABLE `speed_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由路径',
  `component` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由组件',
  `hidden` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否隐藏',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
  `rules` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限节点',
  `sort` smallint(6) NOT NULL DEFAULT 1 COMMENT '排序',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 目录，1菜单，2权限',
  `hide_children` tinyint(1) NOT NULL DEFAULT 0 COMMENT '隐藏子菜单，并且强制渲染为菜单项',
  `active_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单高亮key',
  `open_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '打开方式 0组件，1内链，2外链',
  `link_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '内链地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 309 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '菜单表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_menu
-- ----------------------------
INSERT INTO `speed_menu` VALUES (13, 0, 'system', 'Layout', 0, '系统管理', 'setting-outlined', '', 9, 0, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (14, 13, 'menu', 'system/menu/index', 0, '菜单管理', 'menu-outlined', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (16, 13, 'department', 'system/department/index', 0, '部门管理', 'apartment-outlined', '', 2, 1, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (20, 13, 'user', 'system/user/index', 0, '用户管理', 'team-outlined', '', 3, 1, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (21, 13, 'role', 'system/role/index', 0, '角色管理', 'user-outlined', '', 4, 1, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (28, 14, '', '', 0, '添加菜单', '', 'system:menu:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (29, 14, '', '', 0, '修改菜单', '', 'system:menu:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (30, 14, '', '', 0, '删除菜单', '', 'system:menu:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (32, 13, 'auth/:id', 'system/role/auth', 1, '权限设置', 'insurance-outlined', '', 10, 1, 0, 'system/role', 0, '');
INSERT INTO `speed_menu` VALUES (36, 32, '', '', 1, '保存权限', '', 'system:authAccess:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (38, 16, '', '', 0, '删除部门', '', 'system:department:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (40, 20, '', '', 0, '更新用户', '', 'system:user:update', 2, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (45, 21, '', '', 0, '添加角色', '', 'system:role:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (46, 21, '', '', 0, '修改角色', '', 'system:role:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (47, 21, '', '', 0, '删除角色', '', 'system:role:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (50, 13, 'dict', 'system/dict/index', 0, '字典管理', 'deployment-unit-outlined', '', 5, 1, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (53, 50, '', '', 0, '修改字典', '', 'system:dict:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (54, 50, '', '', 0, '删除字典', '', 'system:dict:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (77, 14, '', '', 0, '查看列表', '', 'system:menu:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (78, 32, '', '', 1, '查看权限', '', 'system:authAccess:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (80, 20, '', '', 0, '查看列表', '', 'system:user:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (81, 21, '', '', 0, '查看列表', '', 'system:role:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (82, 50, '', '', 0, '查看列表', '', 'system:dict:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (105, 156, 'operate', 'system/logs/operate-log', 0, '操作日志', 'profile-outlined', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (106, 105, '', '', 0, '查看列表', '', 'system:operateLog:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (107, 105, '', '', 0, '清空日志', '', 'system:operateLog:clear', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (108, 20, '', '', 0, '修改状态', '', 'system:user:changeStatus', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (110, 50, '', '', 0, '添加字典', '', 'system:dict:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (113, 156, 'login', 'system/logs/login-log', 0, '登录日志', 'diff-outlined', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (114, 113, '', '', 0, '查看列表', '', 'system:loginLog:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (116, 113, '', '', 0, '清空日志', '', 'system:loginLog:clear', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (119, 105, '', '', 0, '删除日志', '', 'system:operateLog:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (120, 113, '', '', 0, '删除日志', '', 'system:loginLog:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (130, 16, '', '', 0, '添加部门', '', 'system:department:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (156, 13, 'log', 'RouteView', 0, '日志管理', 'file-text-outlined', '', 1, 0, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (167, 165, 'filetype', 'system/login_log/index', 0, '附件类型', '', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (184, 13, 'generator', 'system/generator/index', 0, '代码生成器', 'snippets-outlined', '', 1, 1, 1, '', 0, '');
INSERT INTO `speed_menu` VALUES (186, 13, 'generator/:id', 'system/generator/edit', 1, '基础配置', '', '', 1, 1, 0, 'system/generator', 0, '');
INSERT INTO `speed_menu` VALUES (236, 20, '', '', 0, '重置密码', '', 'system:user:resetPassword', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (238, 0, 'clues', 'clues/index', 0, '线索管理', 'nodeIndex-outlined', '', 1, 1, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (239, 238, '', '', 0, '列表', '', 'clues:clues:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (240, 238, '', '', 0, '添加', '', 'clues:clues:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (241, 238, '', '', 0, '编辑', '', 'clues:clues:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (242, 238, '', '', 0, '删除', '', 'clues:clues:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (258, 0, 'customer', 'customer/index', 0, '客户管理', 'team-outlined', '', 2, 0, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (259, 258, '', '', 0, '列表', '', 'customer:customer:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (260, 258, '', '', 0, '添加', '', 'customer:customer:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (261, 258, '', '', 0, '编辑', '', 'customer:customer:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (262, 258, '', '', 0, '删除', '', 'customer:customer:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (274, 0, 'contacts', 'contacts/index', 0, '联系人', 'solution-outlined', '', 3, 0, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (275, 274, '', '', 0, '列表', '', 'contacts:contacts:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (276, 274, '', '', 0, '添加', '', 'contacts:contacts:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (277, 274, '', '', 0, '编辑', '', 'contacts:contacts:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (278, 274, '', '', 0, '删除', '', 'contacts:contacts:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (285, 0, 'product', 'product/index', 0, '产品管理', 'inbox-outlined', '', 4, 0, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (286, 285, '', '', 0, '列表', '', 'product:product:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (287, 285, '', '', 0, '添加', '', 'product:product:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (288, 285, '', '', 0, '编辑', '', 'product:product:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (289, 285, '', '', 0, '删除', '', 'product:product:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (290, 0, 'business', 'business/index', 0, '商机管理', 'pay-circle-outlined', '', 6, 0, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (291, 290, '', '', 0, '列表', '', 'business:business:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (292, 290, '', '', 0, '添加', '', 'business:business:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (293, 290, '', '', 0, '编辑', '', 'business:business:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (294, 290, '', '', 0, '删除', '', 'business:business:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (295, 0, 'contract', 'contract/index', 0, '合同管理', 'audit-outlined', '', 7, 0, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (296, 295, '', '', 0, '列表', '', 'contract:contract:index', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (297, 295, '', '', 0, '添加', '', 'contract:contract:save', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (298, 295, '', '', 0, '编辑', '', 'contract:contract:update', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (299, 295, '', '', 0, '删除', '', 'contract:contract:delete', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (300, 238, '', '', 0, '线索分配', '', 'clues:clues:allocation', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (301, 238, '', '', 0, '转换客户', '', 'clues:clues:transform', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (302, 258, '', '', 0, '更改成功状态', '', 'customer:customer:changeDealStatus', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (303, 258, '', '', 0, '客户分配', '', 'customer:customer:allocation', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (304, 274, '', '', 0, '更换负责人', '', 'contacts:contacts:changeOwnerUser', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (305, 290, '', '', 0, '更换负责人', '', 'business:business:changeOwnerUser', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (306, 290, '', '', 0, '推进商机', '', 'business:business:changeBusinessStage', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (307, 290, '', '', 0, '结束商机', '', 'business:business:endStage', 1, 2, 0, '', 0, '');
INSERT INTO `speed_menu` VALUES (308, 295, '', '', 0, '更换负责人', '', 'contract:contract:changeOwnerUser', 1, 2, 0, '', 0, '');

-- ----------------------------
-- Table structure for speed_operate_log
-- ----------------------------
DROP TABLE IF EXISTS `speed_operate_log`;
CREATE TABLE `speed_operate_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '模块名称',
  `operate` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '操作模块',
  `route` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由',
  `params` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '参数',
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `user_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '操作用户',
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '操作日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for speed_product
-- ----------------------------
DROP TABLE IF EXISTS `speed_product`;
CREATE TABLE `speed_product`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '产品名称',
  `category` tinyint(1) NOT NULL COMMENT '产品类别',
  `price` decimal(10, 2) NOT NULL COMMENT '产品价格',
  `unit` tinyint(1) NOT NULL COMMENT '产品单位',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '产品编码',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '产品描述',
  `cover_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '产品主图',
  `detail_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '产品详情图',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '上架状态，0下架，1上架',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `create_user_id` int(11) NOT NULL COMMENT '创建人',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_product
-- ----------------------------
INSERT INTO `speed_product` VALUES (25, '产品示例', 1, 99.00, 2, 'test', '', '', '', 1, 0, 1754449970, 1);

-- ----------------------------
-- Table structure for speed_record
-- ----------------------------
DROP TABLE IF EXISTS `speed_record`;
CREATE TABLE `speed_record`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `content` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '跟进内容',
  `record_type` tinyint(1) NOT NULL COMMENT '跟进类型(1线索、2客户、3联系人、4商机、5合同)',
  `data_id` int(11) NOT NULL COMMENT '跟进类型的数据主键',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `create_user_id` int(11) NOT NULL COMMENT '创建人ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '跟进记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_record
-- ----------------------------
INSERT INTO `speed_record` VALUES (51, '线索数据跟进示例', 1, 18, 1754449714, 0, 1);
INSERT INTO `speed_record` VALUES (52, '客户跟进示例', 2, 39, 1754449876, 0, 1);

-- ----------------------------
-- Table structure for speed_record_file
-- ----------------------------
DROP TABLE IF EXISTS `speed_record_file`;
CREATE TABLE `speed_record_file`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for speed_role
-- ----------------------------
DROP TABLE IF EXISTS `speed_role`;
CREATE TABLE `speed_role`  (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `note` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `role_key` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限标识',
  `data_range` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 全部数据 2 自定义数据 3 仅本人数据 4 部门数据 5 部门及以下数据',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `role_key`(`role_key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_role
-- ----------------------------
INSERT INTO `speed_role` VALUES (1, 1, '超级管理员', '内置角色，不可维护', 'super_admin', 1, 0);
INSERT INTO `speed_role` VALUES (2, 1, '管理员', '业务权限', 'admin', 1, 0);
INSERT INTO `speed_role` VALUES (3, 1, '总经理', '', 'general', 1, 0);
INSERT INTO `speed_role` VALUES (5, 1, '部门经理', '', 'manager', 3, 0);

-- ----------------------------
-- Table structure for speed_role_department
-- ----------------------------
DROP TABLE IF EXISTS `speed_role_department`;
CREATE TABLE `speed_role_department`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci COMMENT = '部门角色关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_role_department
-- ----------------------------

-- ----------------------------
-- Table structure for speed_user
-- ----------------------------
DROP TABLE IF EXISTS `speed_user`;
CREATE TABLE `speed_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码',
  `realname` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `pinyin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '拼音',
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `dept_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '部门id',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1启用，2禁用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `last_login_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `last_login_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后登录的IP',
  `is_admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否系统管理员，0否，1是',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE COMMENT '用户名唯一'
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_user
-- ----------------------------
INSERT INTO `speed_user` VALUES (1, 'admin', '$2y$10$mH5jYh4WxS8HjqTN9Q1tu.SUyMMezQthe6.LDkZjPu7sABJTyprn6', 'super', '', '18899996666', '', 4, 1, 1748939339, 'https://gw.alipayobjects.com/zos/rmsportal/BiazfanxmamNRoxxVxka.png', 1754644213, '', 1);
INSERT INTO `speed_user` VALUES (2, 'demo', '$2y$10$mH5jYh4WxS8HjqTN9Q1tu.SUyMMezQthe6.LDkZjPu7sABJTyprn6', 'demo', '', '', '', 4, 1, 1748939339, '', 1754538781, '', 0);
INSERT INTO `speed_user` VALUES (9, 'test', '$2y$10$KZnWAJFpo/d4XXPLgzSuDOBv2Y7SQLcKwYbFZKQggCnawytQ4DSLK', '测试', 'cs', '', '', 15, 1, 1615864955, 'https://zos.alipayobjects.com/rmsportal/ODTLcjxAfvqbxHnVXCYX.png', 0, '', 0);
INSERT INTO `speed_user` VALUES (10, 'test2', '$2y$10$89LxsExrBliqqCW/PrOBgOeubhHJUI5tmQkZJ2dBht8ltF/puI6a.', '测试2', 'cs', '', '', 16, 1, 1615865516, '', 0, '', 0);
INSERT INTO `speed_user` VALUES (11, 'yunweu', '$2y$10$Zvbnq8TC7TavN/t/CriXC.g0d89XK4UaBiOYHOSctlwmjD2HLmdYu', '运维管理', 'ywgl', '', '', 17, 1, 1616059337, 'https://gw.alipayobjects.com/zos/antfincdn/XAosXuNZyF/BiazfanxmamNRoxxVxka.png', 0, '', 0);
INSERT INTO `speed_user` VALUES (19, 'www', '$2y$10$SFlICzdZZMZy/2VxS4tMIODzQxcoYV40TkCbS48eTYwnvZEJJAd8u', '王五', 'ww', '', '', 7, 1, 1617182546, '', 0, '', 0);
INSERT INTO `speed_user` VALUES (20, 'yang', '$2y$10$g1oVhKY1SXZmAl20SDo0xOAivYFaB4GbtDzzEjw..AcC0iNEQ/Yp2', '杨六', 'yl', '', '', 4, 1, 1645673069, '', 0, '', 0);
INSERT INTO `speed_user` VALUES (21, 'lishi', '$2y$10$e9cdcQEFEb7k9sdixmwXnuO/GD1bRN8C1xw6b1nfbPjuXxEfvP2FS', '李四', 'ls', '', '', 4, 1, 1645673088, '', 0, '', 0);
INSERT INTO `speed_user` VALUES (22, 'zhangs', '$2y$10$cl6yeliFDXHnfQ7accy4fOO3l7Jelcao9k3IdAka2hewcoZwiKAb2', '张三', 'zs', '', '', 8, 1, 1651917072, '', 0, '', 0);
INSERT INTO `speed_user` VALUES (23, 'wang', '$2y$10$ec8Ln1cnCH0wOGkeWJnJm.68.eue7d/0c2oGWQ25yynxvkOHL6SLK', '王安', 'wa', '', '123@qq.com', 7, 1, 1748939339, '', 0, '', 0);

-- ----------------------------
-- Table structure for speed_user_role
-- ----------------------------
DROP TABLE IF EXISTS `speed_user_role`;
CREATE TABLE `speed_user_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 161 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户角色关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of speed_user_role
-- ----------------------------
INSERT INTO `speed_user_role` VALUES (27, 14, 3);
INSERT INTO `speed_user_role` VALUES (28, 13, 3);
INSERT INTO `speed_user_role` VALUES (46, 12, 3);
INSERT INTO `speed_user_role` VALUES (83, 15, 3);
INSERT INTO `speed_user_role` VALUES (108, 1, 1);
INSERT INTO `speed_user_role` VALUES (126, 19, 2);
INSERT INTO `speed_user_role` VALUES (130, 24, 1);
INSERT INTO `speed_user_role` VALUES (131, 24, 3);
INSERT INTO `speed_user_role` VALUES (132, 18, 1);
INSERT INTO `speed_user_role` VALUES (133, 18, 2);
INSERT INTO `speed_user_role` VALUES (134, 18, 3);
INSERT INTO `speed_user_role` VALUES (137, 22, 3);
INSERT INTO `speed_user_role` VALUES (138, 23, 2);
INSERT INTO `speed_user_role` VALUES (140, 10, 3);
INSERT INTO `speed_user_role` VALUES (153, 11, 2);
INSERT INTO `speed_user_role` VALUES (154, 11, 5);
INSERT INTO `speed_user_role` VALUES (155, 9, 2);
INSERT INTO `speed_user_role` VALUES (156, 20, 2);
INSERT INTO `speed_user_role` VALUES (157, 20, 3);
INSERT INTO `speed_user_role` VALUES (158, 21, 2);
INSERT INTO `speed_user_role` VALUES (159, 21, 3);
INSERT INTO `speed_user_role` VALUES (160, 2, 1);

SET FOREIGN_KEY_CHECKS = 1;
