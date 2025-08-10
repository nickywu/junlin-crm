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

