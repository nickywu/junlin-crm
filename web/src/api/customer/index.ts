import request from '@/utils/request'


/**
 * 获取列表
 * @param params
 */
export function getCustomerList(params: Recordable) {
  return request.get('customer', { params })
}


/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  return request({
    url: data.id ? `customer/${data.id}` : 'customer',
    method: data.id ? 'put' : 'post',
    data: data
  })
}


/**
 * 删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.post("customer/delete", { id });
}



/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id: string) {
  return request.get(`customer/${id}/edit`)
}


/**
 * 获取详情的数据
 * @param id
 */
export function read(id: string | number) {
  return request.get(`customer/${id}`)
}



/**
 * 更改成交状态
 * @param params
 */
export function changeDealStatus(data: Recordable) {
  return request.post("customer/changeDealStatus", data);
}



/**
 * 分配
 * @param params
 */
export function allocation(data: Recordable) {
  return request.post("customer/allocation", data);
}
