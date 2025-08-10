<template>
  <s-modal-form v-bind="getBindValue" @loadSuccess="loadSuccess">
    <s-input label="商机名称" name="name" v-model="form.name" placeholder="请输入商机名称" />
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

    <s-select
      label="商机组"
      name="group_id"
      v-model="form.group_id"
      @change="handleGroupChange"
      :options="businessGroup"
      placeholder="请选择商机组"
    />

    <s-select
      label="商机阶段"
      name="stage_id"
      v-model="form.stage_id"
      :options="businessStage"
      placeholder="请选择商机阶段"
    />

    <s-input label="商机金额" name="money" v-model="form.money" placeholder="请输入商机金额" />
    <s-date-picker
      label="预计成交时间"
      name="deal_time"
      v-model="form.deal_time"
      value-format="YYYY-MM-DD"
      placeholder="请输入预计成交时间"
    />
    <s-textarea
      class="col-span-2"
      label="备注"
      name="remark"
      v-model="form.remark"
      placeholder="请输入"
    />
    <product-select
      class="col-span-2"
      v-model:totalPrice="form.total_price"
      v-model="form.product"
    />
  </s-modal-form>
</template>
<script lang="tsx" setup>
import { save, getEdit, getGroupList } from "@/api/business";
import { TableColumnProps } from "@/components/Table";
import ProductSelect from "../product/components/ProductSelect.vue";
import { toOptions } from "@/utils";
import type { SelectValue } from 'ant-design-vue/es/select';
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

const selectData = ref({});

const loadSuccess = (data: Recordable) => {
  selectData.value = data.customer;
};

const businessGroup = ref<Array<Recordable>>([]);
const businessStage = ref<Array<Recordable>>([]);

onMounted(async () => {
  await getGroupData();
  handleGroupChange(form.group_id);
});

function getGroupData() {
  return getGroupList().then(res => {
    if (res.code == 1) {
      businessGroup.value = toOptions(res.data);
    }
  });
}
//商机流程组变化
function handleGroupChange(value: SelectValue) {
  if (value === undefined) {
    businessStage.value = [];
    return;
  }
  const currentitem = businessGroup.value.find((item: Recordable) => item.id == value);
  if (currentitem) {
    businessStage.value = toOptions(currentitem.stage);
  } else {
    businessStage.value = [];
  }
}

const form = reactive({
  id: undefined,
  name: undefined,
  customer_id: undefined,
  group_id: 1,
  stage_id: undefined,
  money: undefined,
  deal_time: undefined,
  remark: undefined,
  total_price: undefined,
  product: []
});

const rules = reactive({
  name: [{ required: true, message: "请输入商机名称" }],
  customer_id: [{ required: true, message: "请选择客户" }],
  group_id: [{ required: true, message: "请选择商机组" }],
  stage_id: [{ required: true, message: "请选择商机阶段" }],
  money: [{ required: true, message: "请输入商机金额" }],
  deal_time: [{ required: true, message: "请选择预计成交时间" }]
});

const getBindValue = computed(() => {
  return {
    width: 700,
    title: "商机",
    model: form,
    saveApi: save,
    readApi: getEdit,
    labelWidth: "110px",
    layoutGrid: { col: 2, gutter: 16 },
    bodyStyle: { padding: "15px" },
    rules: rules
  };
});
</script>
