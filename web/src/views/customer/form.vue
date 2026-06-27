<template>
  <s-modal-form v-bind="getBindValue" @loadSuccess="loadEditRegionOptions">
    <s-input label="客户名称" name="name" v-model="form.name" placeholder="请输入客户名称" />
    <s-select
      label="客户来源"
      name="source"
      dictType="source"
      v-model="form.source"
      placeholder="请选择客户来源"
    />
    <s-input label="手机号码" name="mobile" v-model="form.mobile" placeholder="请输入手机号码" />
    <s-input label="微信号" name="wechat" v-model="form.wechat" placeholder="请输入微信号" />
    <s-input
      label="电话号码"
      name="telephone"
      v-model="form.telephone"
      placeholder="请输入电话号码"
    />
    <s-input label="邮箱" name="email" v-model="form.email" placeholder="请输入邮箱" />
    <s-select
      label="客户行业"
      name="industry"
      dictType="industry"
      v-model="form.industry"
      placeholder="请选择客户行业"
    />
    <s-select
      label="客户级别"
      name="level"
      dictType="level"
      v-model="form.level"
      placeholder="请选择客户级别"
    />
    <s-select
      label="省份"
      name="province_id"
      v-model="form.province_id"
      :options="provinceOptions"
      placeholder="请选择省份"
      @change="onProvinceChange"
    />
    <s-select
      label="城市"
      name="city_id"
      v-model="form.city_id"
      :options="cityOptions"
      placeholder="请选择城市"
      :disabled="!form.province_id"
      @change="onCityChange"
    />
    <s-select
      label="区域"
      name="district_id"
      v-model="form.district_id"
      :options="districtOptions"
      placeholder="请选择区域"
      :disabled="!form.city_id"
    />
    <s-date-picker label="下次联系时间" name="next_time" v-model="form.next_time"  value-format="YYYY-MM-DD" />
    <s-input
      class="col-span-2"
      label="详细地址"
      name="addr"
      v-model="form.addr"
      placeholder="请输入地址"
    />
    <s-textarea
      class="col-span-2"
      label="备注"
      name="note"
      v-model="form.note"
      placeholder="请输入备注"
    />
  </s-modal-form>
</template>
<script lang="ts" setup>
import { save, getEdit } from "@/api/customer";
import { getProvinceList, getRegionChildren } from "@/api/common";

const form = reactive({
  id: undefined,
  name: undefined,
  mobile: undefined,
  wechat: undefined,
  telephone: undefined,
  email: undefined,
  source: undefined,
  level: undefined,
  industry: undefined,
  province_id: undefined,
  city_id: undefined,
  district_id: undefined,
  addr: undefined,
  note: undefined,
  next_time: undefined
});

const rules = reactive({
  name: [{ required: true, message: "请输入客户名称" }]
});

/** 省份下拉选项 */
const provinceOptions = ref<any[]>([]);
/** 城市下拉选项 */
const cityOptions = ref<any[]>([]);
/** 区域下拉选项 */
const districtOptions = ref<any[]>([]);

/**
 * 加载省份列表
 */
function loadProvinceList() {
  getProvinceList().then((res: any) => {
    provinceOptions.value = (res.data || []).map((item: any) => ({
      label: item.name,
      value: item.id
    }));
  });
}

/**
 * 省份变更时，加载对应的城市列表
 * @param value 省份ID
 */
function onProvinceChange(value: any) {
  // 清空城市和区域选择
  form.city_id = undefined;
  form.district_id = undefined;
  cityOptions.value = [];
  districtOptions.value = [];
  if (!value) return;
  getRegionChildren(value).then((res: any) => {
    cityOptions.value = (res.data || []).map((item: any) => ({
      label: item.name,
      value: item.id
    }));
  });
}

/**
 * 城市变更时，加载对应的区县列表
 * @param value 城市ID
 */
function onCityChange(value: any) {
  // 清空区域选择
  form.district_id = undefined;
  districtOptions.value = [];
  if (!value) return;
  getRegionChildren(value).then((res: any) => {
    districtOptions.value = (res.data || []).map((item: any) => ({
      label: item.name,
      value: item.id
    }));
  });
}

/**
 * 编辑回显时，根据已有的省市区ID加载对应的下拉选项
 * 确保编辑模式下城市和区域的下拉列表也有数据
 */
function loadEditRegionOptions() {
  const { province_id, city_id } = form;
  if (province_id) {
    // 加载该省份下的城市列表
    getRegionChildren(province_id).then((res: any) => {
      cityOptions.value = (res.data || []).map((item: any) => ({
        label: item.name,
        value: item.id
      }));
    });
  }
  if (city_id) {
    // 加载该城市下的区县列表
    getRegionChildren(city_id).then((res: any) => {
      districtOptions.value = (res.data || []).map((item: any) => ({
        label: item.name,
        value: item.id
      }));
    });
  }
}

// 页面挂载时加载省份列表
onMounted(() => {
  loadProvinceList();
});

const getBindValue = computed(() => {
  return {
    width: 700,
    title: "客户",
    model: form,
    saveApi: save,
    readApi: getEdit,
    labelWidth: '100px',
    layoutGrid: { col: 2, gutter: 20 },
    bodyStyle: { padding: "5px" },
    rules: rules
  };
});
</script>
