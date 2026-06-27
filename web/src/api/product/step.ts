import request from '@/utils/request'

/**
 * 获取步骤列表
 * @param params 查询参数（需包含 product_id）
 */
export function getStepList(params: Recordable) {
    return request.get('step', { params })
}

/**
 * 保存步骤（新增/编辑）
 * @param data 步骤数据
 */
export function save(data: Recordable) {
    return request({
        url: data.id ? `step/${data.id}` : 'step',
        method: data.id ? 'put' : 'post',
        data: data
    })
}

/**
 * 批量删除步骤
 * @param id 步骤ID
 */
export function destroy(id: string | number) {
    return request.post("step/delete", { id });
}

/**
 * 获取编辑数据
 * @param id 步骤ID
 */
export function getEdit(id: string) {
    return request.get(`step/${id}/edit`)
}
