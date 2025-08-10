import request from '@/utils/request'


/**
 * 获取列表
 * @param params
 */
export function getRecordList(params:Recordable){
    return request.get('record', { params })
}


/**
 * 保存
 * @param data
 */
export function save(data:Recordable) {
    return request({
        url: data.id ? `record/${data.id}` : 'record',
        method: data.id ? 'put' : 'post',
        data: data
    })
}


/**
 * 删除
 * @param id
 */
export function destroy(id:string) {
    return request.delete(`record/${id}`)
}


/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id:string) {
    return request.get(`record/${id}/edit`)
}


/**
 * 获取详情的数据
 * @param id
 */
export function read(id:string) {
    return request.get(`record/${id}`)
}
