import request from '@/utils/request'


/**
 * 获取列表
 * @param params
 */
export function getBusinessList(params: Recordable) {
  return request.get('business', { params })
}


/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  return request({
    url: data.id ? `business/${data.id}` : 'business',
    method: data.id ? 'put' : 'post',
    data: data
  })
}


/**
 * 删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.post("business/delete", { id });
}


/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id: string) {
  return request.get(`business/${id}/edit`)
}


/**
 * 获取详情的数据
 * @param id
 */
export function read(id: string | number) {
  return request.get(`business/${id}`)
}


/**
 * 获取商机组
 * @param id
 */
export function getGroupList() {
  return request.get(`business/getGroupList`)
}


/**
 * 更换负责人
 * @param params
 */
export function changeOwnerUserApi(data: Recordable) {
  return request.post("business/changeOwnerUser", data);
}

/**
 * 获取商机产品
 * @param id
 */
export function getProduct(params: Recordable) {
  return request.get(`business/getProduct`, { params })
}


/**
 * 推进商机
 * @param params
 */
export function changeBusinessStage(data: Recordable) {
  return request.post("business/changeBusinessStage", data);
}

/**
 * 结束商机
 * @param params
 */
export function endStage(data: Recordable) {
  return request.post("business/endStage", data);
}

/**
 * 商机阶段列表
 * @returns {*}
 */
export function getBusinessStage(id: string | number) {
  return request.get("business/getBusinessStage", { params: { id } });
}
