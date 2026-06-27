<template>
  <s-modal-form v-bind="getBindValue" @loadSuccess="loadSuccess">
    <s-input label="企业名称" name="name" v-model="form.name" placeholder="请输入企业名称" />
    <a-form-item label="关联客户" name="customer_id">
      <s-table-select
        placeholder="请选择客户"
        api="customer"
        :columns="customerColumns"
        :width="450"
        v-model:rows="selectData"
        v-model:value="form.customer_id"
      />
    </a-form-item>
    <s-input
      label="法定代表人"
      name="legal_person"
      v-model="form.legal_person"
      placeholder="请输入法定代表人"
    />
    <s-select
      label="纳税性质"
      name="tax_nature"
      v-model="form.tax_nature"
      :options="[
        { value: 0, label: '小规模纳税人' },
        { value: 1, label: '一般纳税人' }
      ]"
      placeholder="请选择纳税性质"
    />
    <s-input
      label="联系人"
      name="contact_person"
      v-model="form.contact_person"
      placeholder="请输入联系人"
    />
    <s-input
      label="联系电话"
      name="contact_phone"
      v-model="form.contact_phone"
      placeholder="请输入联系电话"
    />
    <s-input
      label="联系邮箱"
      name="contact_email"
      v-model="form.contact_email"
      placeholder="请输入联系邮箱"
    />
    <s-input
      label="注册资本"
      name="registered_capital"
      v-model="form.registered_capital"
      placeholder="请输入注册资本"
    />
    <s-input
      label="企业地址"
      class="col-span-2"
      name="address"
      v-model="form.address"
      placeholder="请输入企业地址"
    />
    <s-textarea
      label="经营范围"
      class="col-span-2"
      name="business_scope"
      v-model="form.business_scope"
      placeholder="请输入经营范围"
      :auto-size="{ minRows: 2, maxRows: 4 }"
    />
    <s-textarea
      label="备注"
      name="note"
      class="col-span-2"
      v-model="form.note"
      placeholder="请输入备注"
    />
  </s-modal-form>
</template>
<script lang="tsx" setup>
import { save, getEdit } from "@/api/enterprise";
import { TableColumnProps } from "@/components/Table";

const form = reactive({
  id: undefined,
  name: undefined,
  customer_id: undefined,
  legal_person: undefined,
  tax_nature: undefined,
  contact_person: undefined,
  contact_phone: undefined,
  contact_email: undefined,
  address: undefined,
  business_scope: undefined,
  registered_capital: undefined,
  note: undefined
});

const rules = reactive({
  name: [{ required: true, message: "请输入企业名称" }],
  customer_id: [{ required: true, message: "请选择关联客户" }]
});

/** 表格选择器已选数据，用于编辑回显 */
const selectData = ref({});

/**
 * 编辑模式回显时，恢复客户表格选择器的已选数据
 * @param data 后端返回的编辑数据
 */
const loadSuccess = (data: Recordable) => {
  selectData.value = data.customer;
};

/** 客户表格选择器的列配置 */
const customerColumns = ref<TableColumnProps[]>([
  {
    title: "客户名称",
    dataIndex: "name",
    customRender: ({ text }) => <a>{text}</a>
  },
  {
    title: "手机号码",
    dataIndex: "mobile"
  },
  {
    title: "客户等级",
    dataIndex: "level_text"
  }
]);

const getBindValue = computed(() => {
  return {
    width: 700,
    labelWidth: "90px",
    title: "企业",
    model: form,
    saveApi: save,
    readApi: getEdit,
    layoutGrid: { col: 2, gutter: 16 },
    bodyStyle: { padding: "15px" },
    rules: rules
  };
});
</script>
