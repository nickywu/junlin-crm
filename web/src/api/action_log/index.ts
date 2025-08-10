import request from '@/utils/request'


/**
 * 获取列表
 * @param params
 */
export function getActionLog(params:Recordable){
    return request.get('action_log', { params })
}



