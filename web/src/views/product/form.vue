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
  delete_time: undefined
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
    width: 700,
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
