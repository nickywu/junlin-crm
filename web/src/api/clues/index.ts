import request from '@/utils/request'


/**
 * 获取列表
 */
export function getCluesList(params:Recordable){
    return request.get('clues', { params })
}


/**
 * 保存
 * @param data
 */
export function save(data:Recordable) {
    return request({
        url: data.id ? `clues/${data.id}` : 'clues',
        method: data.id ? 'put' : 'post',
        data: data
    })
}

/**
 * 删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.post("clues/delete", { id });
}


/**
 * 获取修改的数据
 * @param params
 */
export function getEdit(id:string) {
    return request.get(`clues/${id}/edit`)
}

/**
 * 获取详情的数据
 * @param params
 */
export function read(id:string | number) {
    return request.get(`clues/${id}`)
}


/**
 * 分配
 * @param params
 */
export function allocation(data: Recordable) {
  return request.post("clues/allocation", data);
}


/**
 * 转换客户
 * @returns {*}
 */
export function transform(id:string | number) {
  return request.post("clues/transform", { id: id });
}
