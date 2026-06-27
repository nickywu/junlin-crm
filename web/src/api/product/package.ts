import request from '@/utils/request'

/**
 * 获取套餐子业务模板列表
 * @param params 查询参数
 */
export function getPackageItemList(params: Recordable) {
    return request.get('product/packageItem', { params })
}

/**
 * 保存套餐子业务模板
 * @param data 模板数据
 */
export function save(data: Recordable) {
    return request({
        url: data.id ? `product/packageItem/${data.id}` : 'product/packageItem',
        method: data.id ? 'put' : 'post',
        data: data
    })
}

/**
 * 批量删除套餐子业务模板
 * @param id 模板ID
 */
export function destroy(id: string | number) {
    return request.post("product/packageItem/delete", { id });
}

/**
 * 获取套餐子业务模板编辑数据
 * @param id 模板ID
 */
export function getEdit(id: string) {
    return request.get(`product/packageItem/${id}/edit`)
}
