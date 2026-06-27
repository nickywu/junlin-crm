<template>
  <s-modal-form v-bind="getBindValue">
    <s-input label="名称" name="name" v-model="form.name" placeholder="请输入名称" />
    <s-select
      label="类别"
      name="category"
      v-model="form.category"
      placeholder="请输入类别"
      dict-type="product_category"
    />
    <s-input-number label="价格" name="price" v-model="form.price" placeholder="请输入价格" />
    <s-select
      dict-type="product_unit"
      label="单位"
      name="unit"
      v-model="form.unit"
      placeholder="请输入单位"
    />
    <s-input label="编码" name="code" v-model="form.code" placeholder="请输入编码" />
    <s-radio-group
      label="服务类型"
      name="service_type"
      v-model="form.service_type"
      :options="[
        { label: '单次', value: 'single' },
        { label: '长期', value: 'long_term' }
      ]"
    />
    <s-input-number
      label="内部成本"
      name="internal_cost"
      v-model="form.internal_cost"
      :min="0"
      :precision="2"
      placeholder="请输入内部成本"
    />
    <s-input-number
      label="固定成本"
      name="fixed_cost"
      v-model="form.fixed_cost"
      :min="0"
      :precision="2"
      placeholder="请输入固定成本"
    />
    <s-input-number
      label="佣金成本"
      name="commission_cost"
      v-model="form.commission_cost"
      :min="0"
      :precision="2"
      placeholder="请输入佣金成本"
    />
    <s-input-number
      label="其他成本"
      name="other_cost"
      v-model="form.other_cost"
      :min="0"
      :precision="2"
      placeholder="请输入其他成本"
    />
    <s-input-number
      label="销售提成"
      name="sales_commission"
      v-model="form.sales_commission"
      :min="0"
      :precision="2"
      placeholder="请输入销售提成"
    />
    <s-input-number
      label="销售主管提成"
      name="sales_manager_commission"
      v-model="form.sales_manager_commission"
      :min="0"
      :precision="2"
      placeholder="请输入销售主管提成"
    />
    <s-input-number
      label="工单主管奖金"
      name="work_order_manager_bonus"
      v-model="form.work_order_manager_bonus"
      :min="0"
      :precision="2"
      placeholder="请输入工单主管奖金"
    />
    <s-input-number
      v-if="form.service_type === 'long_term'"
      label="服务周期"
      name="service_period"
      v-model="form.service_period"
      :min="1"
      :precision="0"
      placeholder="请输入服务周期"
    >
      <template #addonAfter>月</template>
    </s-input-number>

    <s-textarea
      label="描述"
      name="remark"
      v-model="form.remark"
      placeholder="请输入描述"
    />
    <a-form-item label="主图" name="cover_image">
      <s-upload show-type="image" :multiple="false" v-model:fileUrl="form.cover_image"></s-upload>
    </a-form-item>

    <a-form-item class="col-span-2" label="详情图" name="detail_image">
      <s-upload show-type="image" v-model:fileUrl="form.detail_image" :maxCount="5"></s-upload>
    </a-form-item>
  </s-modal-form>
</template>
<script lang="ts" setup>
import { save, getEdit } from "@/api/product";

const form = reactive({
  id: undefined,
  name: undefined,
  category: undefined,
  price: undefined,
  unit: undefined,
  code: undefined,
  remark: undefined,
  cover_image: undefined,
  detail_image: undefined,
  status: undefined,
  delete_time: undefined,
  service_type: undefined,
  internal_cost: undefined,
  fixed_cost: undefined,
  commission_cost: undefined,
  other_cost: undefined,
  sales_commission: undefined,
  sales_manager_commission: undefined,
  work_order_manager_bonus: undefined,
  service_period: undefined
});

const rules = reactive({
  name: [{ required: true, message: "请输入名称" }],
  category: [{ required: true, message: "请输入类别" }],
  price: [{ required: true, message: "请输入价格" }],
  unit: [{ required: true, message: "请输入单位" }],
  code: [{ required: true, message: "请输入编码" }]
});


const getBindValue = computed(() => {
  return {
    width: 800,
    title: "产品",
    model: form,
    saveApi: save,
    readApi: getEdit,
    layoutGrid: { col: 2, gutter: 20 },
    bodyStyle: { padding: "5px" },
    rules: rules
  };
});
</script>
