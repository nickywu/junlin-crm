import request from '@/utils/request'


/**
 * 获取列表
 * @param params
 */
export function getContactsList(params: Recordable) {
  return request.get('contacts', { params })
}


/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  return request({
    url: data.id ? `contacts/${data.id}` : 'contacts',
    method: data.id ? 'put' : 'post',
    data: data
  })
}

/**
 * 删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.post("contacts/delete", { id });
}



/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id: string) {
  return request.get(`contacts/${id}/edit`)
}


/**
 * 获取详情的数据
 * @param id
 */
export function read(id: string | number) {
  return request.get(`contacts/${id}`)
}



/**
 * 更换负责人
 * @param params
 */
export function changeOwnerUserApi(data: Recordable) {
  return request.post("contacts/changeOwnerUserApi", data);
}
