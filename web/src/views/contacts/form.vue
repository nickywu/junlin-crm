<template>
  <s-modal-form v-bind="getBindValue" @loadSuccess="loadSuccess">
    <s-input label="姓名" name="name" v-model="form.name" placeholder="请输入联系人姓名" />
    <a-form-item label="关联客户" name="customer_id">
      <s-table-select
        placeholder="请选择客户"
        api="customer"
        :columns="columns"
        :width="450"
        v-model:rows="selectData"
        v-model:value="form.customer_id"
      />
    </a-form-item>
    <s-input label="手机号码" name="mobile" v-model="form.mobile" placeholder="请输入手机号码" />
    <s-input
      label="电话号码"
      name="telephone"
      v-model="form.telephone"
      placeholder="请输入电话号码"
    />
    <s-input label="邮箱" name="email" v-model="form.email" placeholder="请输入邮箱" />
    <s-select label="职务" name="job" dictType="job" v-model="form.job" placeholder="请选择职务" />
    <s-select
      label="性别"
      name="gender"
      dictType="gender"
      v-model="form.gender"
      placeholder="请选择性别"
    />
    <s-select
      label="是否决策人"
      name="is_decision"
      :options="[
        { value: 1, label: '是' },
        { value: 0, label: '否' }
      ]"
      v-model="form.is_decision"
      placeholder="请选择是否决策人"
    />
    <s-input
      label="详细地址"
      class="col-span-2"
      name="addr"
      v-model="form.addr"
      placeholder="请输入地址"
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
import { save, getEdit } from "@/api/contacts";
import { TableColumnProps } from "@/components/Table";
const form = reactive({
  id: undefined,
  name: undefined,
  customer_id: undefined,
  mobile: undefined,
  email: undefined,
  job: undefined,
  gender: undefined,
  addr: undefined,
  note: undefined,
  is_decision: undefined,
  telephone: undefined
});

const rules = reactive({
  name: [{ required: true, message: "请输入联系人姓名" }],
  customer_id: [{ required: true, message: "请输入关联客户" }]
});
const selectData = ref({});

const loadSuccess = (data: Recordable) => {
  selectData.value = data.customer;
};

const columns = ref<TableColumnProps[]>([
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
    width: 650,
    labelWidth: "90px",
    title: "联系人",
    model: form,
    saveApi: save,
    readApi: getEdit,
    layoutGrid: { col: 2, gutter: 16 },
    bodyStyle: { padding: "15px" },
    rules: rules
  };
});
</script>
