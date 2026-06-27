<template>
  <s-modal-form v-bind="getBindValue" @loadSuccess="loadSuccess">
    <a-form-item label="关联产品" name="product_id">
      <s-table-select
        placeholder="请选择关联产品"
        api="product"
        :columns="productColumns"
        :width="450"
        v-model:rows="selectData"
        v-model:value="form.product_id"
      />
    </a-form-item>
    <s-input
      label="步骤名称"
      name="name"
      v-model="form.name"
      placeholder="请输入步骤名称"
    />
    <s-input-number
      label="序号"
      name="sort"
      v-model="form.sort"
      :min="0"
      :precision="0"
      placeholder="请输入序号"
    />
    <s-input
      label="下一步骤名称"
      name="next_step_name"
      v-model="form.next_step_name"
      placeholder="请输入下一步骤名称"
    />
    <s-textarea
      label="注意事项"
      class="col-span-2"
      name="attention"
      v-model="form.attention"
      placeholder="请输入注意事项"
      :auto-size="{ minRows: 2, maxRows: 4 }"
    />
  </s-modal-form>
</template>
<script lang="tsx" setup>
import { save, getEdit } from "@/api/product/step";
import { TableColumnProps } from "@/components/Table";

/** 表格选择器已选数据，用于编辑回显 */
const selectData = ref<Recordable>({});

/** 从URL参数获取产品ID和名称 */
const productId = computed(() => {
  const query = new URLSearchParams(window.location.search);
  return query.get("product_id") || "";
});
const productName = computed(() => {
  const query = new URLSearchParams(window.location.search);
  return query.get("product_name") || "";
});

const form = reactive<Recordable>({
  id: undefined,
  product_id: undefined,
  name: undefined,
  attention: undefined,
  next_step_name: undefined,
  sort: undefined
});

const rules = reactive({
  product_id: [{ required: true, message: "请选择关联产品" }],
  name: [{ required: true, message: "请输入步骤名称" }]
});

/**
 * 编辑模式回显时，恢复产品表格选择器的已选数据
 * @param data 后端返回的编辑数据
 */
const loadSuccess = (data: Recordable) => {
  if (data.product) {
    selectData.value = data.product;
  }
};

/** 产品表格选择器的列配置 */
const productColumns = ref<TableColumnProps[]>([
  {
    title: "产品名称",
    dataIndex: "name",
    customRender: ({ text }) => <a>{text}</a>
  },
  {
    title: "产品编码",
    dataIndex: "code"
  },
  {
    title: "产品价格",
    dataIndex: "price"
  }
]);

const getBindValue = computed(() => {
  return {
    width: 600,
    title: "服务步骤",
    model: form,
    saveApi: save,
    readApi: getEdit,
    layoutGrid: { col: 2, gutter: 16 },
    bodyStyle: { padding: "15px" },
    rules: rules,
    /** 打开新增窗口时自动填充产品 */
    beforeOpen: (isEdit: boolean) => {
      if (!isEdit) {
        form.product_id = productId.value;
        // 从URL参数构建选中数据，自动填充产品选择器
        if (productId.value) {
          selectData.value = { id: productId.value, name: productName.value };
        }
      }
    }
  };
});
</script>
