import request from '@/utils/request'


/**
 * 获取列表
 * @param params
 */
export function getContractList(params: Recordable) {
  return request.get('contract', { params })
}


/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  return request({
    url: data.id ? `contract/${data.id}` : 'contract',
    method: data.id ? 'put' : 'post',
    data: data
  })
}



/**
 * 删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.post("contract/delete", { id });
}

/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id: string) {
  return request.get(`contract/${id}/edit`)
}


/**
 * 获取详情的数据
 * @param id
 */
export function read(id: string | number) {
  return request.get(`contract/${id}`)
}


/**
 * 更换负责人
 * @param params
 */
export function changeOwnerUserApi(data: Recordable) {
  return request.post("contract/changeOwnerUser", data);
}

/**
 * 获取合同产品
 * @param id
 */
export function getProduct(params: Recordable) {
  return request.get(`contract/getProduct`, { params })
}

/**
 * 重新生成合同服务周期和工单
 * @param data 合同ID参数
 */
export function generateWorkflow(data: Recordable) {
  return request.post("contract/generateWorkflow", data);
}

/**
 * 获取合同服务周期列表
 * @param params 查询参数
 */
export function getServicePeriod(params: Recordable) {
  return request.get("contract/servicePeriod", { params });
}

/**
 * 更新合同服务周期
 * @param id 周期ID
 * @param data 周期数据
 */
export function updateServicePeriod(id: string | number, data: Recordable) {
  return request({
    url: `contract/servicePeriod/${id}`,
    method: "put",
    data
  });
}

/**
 * 获取合同工单列表
 * @param params 查询参数
 */
export function getWorkOrder(params: Recordable) {
  return request.get("contract/workOrder", { params });
}

/**
 * 更新合同工单
 * @param id 工单ID
 * @param data 工单数据
 */
export function updateWorkOrder(id: string | number, data: Recordable) {
  return request({
    url: `contract/workOrder/${id}`,
    method: "put",
    data
  });
}

/**
 * 获取合同工单节点列表
 * @param params 查询参数
 */
export function getWorkOrderNode(params: Recordable) {
  return request.get("contract/workOrderNode", { params });
}

/**
 * 更新合同工单节点
 * @param id 节点ID
 * @param data 节点数据
 */
export function updateWorkOrderNode(id: string | number, data: Recordable) {
  return request({
    url: `contract/workOrderNode/${id}`,
    method: "put",
    data
  });
}

