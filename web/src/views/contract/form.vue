<template>
  <s-modal-form v-bind="getBindValue" @loadSuccess="loadSuccess" @register="register">
    <s-input
      label="合同编号"
      name="order_no"
      :disabled="true"
      v-model="form.order_no"
      placeholder="系统自动生成"
    />
    <s-input label="合同名称" name="name" v-model="form.name" placeholder="请输入名称" />
    <a-form-item label="关联客户" name="customer_id">
      <s-table-select
        placeholder="请选择客户"
        api="customer"
        :columns="columns"
        :width="450"
        @change="onCustomerChange"
        v-model:rows="customerData"
        v-model:value="form.customer_id"
      />
    </a-form-item>

    <a-form-item label="关联商机" name="business_id">
      <s-table-select
        placeholder="请选择商机"
        api="business"
        :columns="business_col"
        :width="450"
        :disabled="disabled"
        :immediate="false"
        :alwaysLoad="true"
        :queryParam="{ customer_id: form.customer_id }"
        v-model:rows="businessData"
        v-model:value="form.business_id"
      />
    </a-form-item>

    <s-date-picker
      label="开始时间"
      name="start_time"
      v-model="form.start_time"
      value-format="YYYY-MM-DD"
      placeholder="请选择开始时间"
    />
    <s-date-picker
      label="到期时间"
      name="end_time"
      v-model="form.end_time"
      value-format="YYYY-MM-DD"
      placeholder="请选择到期时间"
    />

    <a-form-item label="客户签约人" name="contacts_id">
      <s-table-select
        placeholder="请选择客户签约人"
        api="contacts"
        :columns="contacts_col"
        :width="450"
        :disabled="disabled"
        :immediate="false"
        :alwaysLoad="true"
        v-model:rows="contactsData"
        :queryParam="{ customer_id: form.customer_id }"
        v-model:value="form.contacts_id"
      />
    </a-form-item>

    <a-form-item label="公司签约人" name="order_user_id">
      <s-select-user v-model="form.order_user_id" placeholder="请选择公司签约人" />
    </a-form-item>

    <s-date-picker
      label="签约时间"
      name="signing_time"
      v-model="form.signing_time"
      value-format="YYYY-MM-DD"
      placeholder="请选择签约时间"
    />
    <s-input-number
      label="合同金额"
      name="money"
      v-model="form.money"
      placeholder="请输入合同金额"
    />
    <s-radio-group
      v-model="form.status"
      dictType="contract_status"
      label="合同状态"
      name="status"
    ></s-radio-group>

    <s-textarea
      label="备注"
      class="col-span-2"
      name="remark"
      v-model="form.remark"
      placeholder="请输入备注"
    />
    <product-select
      class="col-span-2"
      v-model:totalPrice="form.total_price"
      v-model="form.product"
    />
  </s-modal-form>
</template>
<script lang="tsx" setup>
import { save, getEdit } from "@/api/contract";
import { TableColumnProps } from "@/components/Table";
import ProductSelect from "../product/components/ProductSelect.vue";
import dayjs from "dayjs";
const form = reactive({
  id: undefined,
  name: undefined,
  business_id: undefined,
  signing_time: dayjs(),
  start_time: undefined,
  end_time: undefined,
  customer_id: undefined,
  order_user_id: undefined,
  contacts_id: undefined,
  money: undefined,
  remark: undefined,
  order_no: undefined,
  status: 0 as number | undefined,
  total_price: undefined,
  product: []
});

const [register] = useModalInner((id: number) => {
  form.status = id ? undefined : 0;
});

const rules = reactive({
  name: [{ required: true, message: "请输入名称" }],
  signing_time: [{ required: true, message: "请选择签约时间" }],
  start_time: [{ required: true, message: "请选择开始时间" }],
  end_time: [{ required: true, message: "请选择到期时间" }],
  customer_id: [{ required: true, message: "请选择客户" }],
  order_user_id: [{ required: true, message: "请选择公司签约人" }],
  contacts_id: [{ required: true, message: "请选择客户签约人" }],
  money: [{ required: true, message: "请输入合同金额" }],
  status: [{ required: true, message: "请选择合同状态" }]
});

const disabled = ref(true);
const customerData = ref({});
const businessData = ref({});
const contactsData = ref({});

const loadSuccess = (data: Recordable) => {
  customerData.value = data.customer;
  businessData.value = data.business;
  contactsData.value = data.contacts;
  disabled.value = false;
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

const onCustomerChange = (value: any) => {
  disabled.value = value ? false : true;
  form.business_id = undefined;
  form.contacts_id = undefined;
};

const business_col = ref([
  {
    title: "商机名称",
    dataIndex: "name"
  },
  {
    title: "商机金额",
    dataIndex: "money"
  },
  {
    title: "商机阶段",
    dataIndex: "stage_name"
  }
]);

const contacts_col = ref([
  {
    title: "联系人名称",
    dataIndex: "name"
  },
  {
    title: "手机号码",
    dataIndex: "mobile"
  },
  {
    title: "职务",
    dataIndex: "job"
  }
]);

const getBindValue = computed(() => {
  return {
    width: 750,
    title: "合同",
    model: form,
    saveApi: save,
    readApi: getEdit,
    labelWidth: "90px",
    layoutGrid: { col: 2, gutter: 16 },
    bodyStyle: { padding: "20px" },
    rules: rules
  };
});
</script>
