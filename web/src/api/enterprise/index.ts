import request from '@/utils/request'


/**
 * 获取企业列表
 * @param params
 */
export function getEnterpriseList(params: Recordable) {
  return request.get('enterprise', { params })
}


/**
 * 保存（新增/编辑）
 * @param data
 */
export function save(data: Recordable) {
  return request({
    url: data.id ? `enterprise/${data.id}` : 'enterprise',
    method: data.id ? 'put' : 'post',
    data: data
  })
}


/**
 * 删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.post("enterprise/delete", { id });
}


/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id: string) {
  return request.get(`enterprise/${id}/edit`)
}


/**
 * 获取详情的数据
 * @param id
 */
export function read(id: string | number) {
  return request.get(`enterprise/${id}`)
}
