import request from '@/utils/request'


/**
 * 获取列表
 * @param params
 */
export function getProductList(params:Recordable){
    return request.get('product', { params })
}


/**
 * 保存
 * @param data
 */
export function save(data:Recordable) {
    return request({
        url: data.id ? `product/${data.id}` : 'product',
        method: data.id ? 'put' : 'post',
        data: data
    })
}


/**
 * 删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.post("product/delete", { id });
}



/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id:string) {
    return request.get(`product/${id}/edit`)
}


/**
 * 获取详情的数据
 * @param id
 */
export function read(id:string | number) {
    return request.get(`product/${id}`)
}
