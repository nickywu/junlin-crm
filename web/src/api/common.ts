import request from "@/utils/request";

/**
 * 获取数据字典
 * @param type
 * @param str
 */
export function getDict(type: string[] | string, str = 0) {
  const params: Recordable = { type };
  if (str) {
    params.str = str;
  }
  return request.get("dict/get", { params });
}

/**
 * 获取所有省份列表（一级地区）
 */
export function getProvinceList() {
  return request.get("region/getProvince");
}

/**
 * 根据父级ID获取子地区列表（二级城市、三级区县）
 * @param parentId 父级地区ID
 */
export function getRegionChildren(parentId: number) {
  return request.get("region/getChildren", { params: { parent_id: parentId } });
}
