import request from '@/utils/request'

// ===================== 单次服务收款 =====================

/**
 * 获取单次服务收款列表
 * @param params
 */
export function getSingleList(params: Recordable) {
  return request.get('collect/single', { params })
}

/**
 * 单次服务收款保存
 * @param data
 */
export function saveSingle(data: Recordable) {
  return request({
    url: data.id ? `collect/single/${data.id}` : 'collect/single',
    method: data.id ? 'put' : 'post',
    data: data
  })
}

/**
 * 获取单次服务收款编辑数据
 * @param id
 */
export function getSingleEdit(id: string) {
  return request.get(`collect/single/${id}/edit`)
}

/**
 * 删除单次服务收款
 * @param id
 */
export function deleteSingle(id: string | number) {
  return request.post("collect/single/delete", { id });
}

/**
 * 获取单次服务合同产品列表（供下拉选择）
 * @param params
 */
export function getSingleContractProducts(params: Recordable) {
  return request.get('collect/single/getContractProducts', { params })
}

// ===================== 长期服务收款 =====================

/**
 * 获取长期服务收款列表
 * @param params
 */
export function getLongList(params: Recordable) {
  return request.get('collect/long', { params })
}

/**
 * 长期服务收款保存
 * @param data
 */
export function saveLong(data: Recordable) {
  return request({
    url: data.id ? `collect/long/${data.id}` : 'collect/long',
    method: data.id ? 'put' : 'post',
    data: data
  })
}

/**
 * 获取长期服务收款编辑数据
 * @param id
 */
export function getLongEdit(id: string) {
  return request.get(`collect/long/${id}/edit`)
}

/**
 * 删除长期服务收款
 * @param id
 */
export function deleteLong(id: string | number) {
  return request.post("collect/long/delete", { id });
}

/**
 * 获取长期服务合同产品列表（供下拉选择）
 * @param params
 */
export function getLongContractProducts(params: Recordable) {
  return request.get('collect/long/getContractProducts', { params })
}
