<template>
  <s-modal-form v-bind="getBindValue" @loadSuccess="loadSuccess" @register="register">
    <a-form-item label="合同关联产品" name="contract_product_id">
      <s-table-select
        placeholder="请选择单次服务合同产品"
        :api="getSingleContractProducts"
        :columns="productColumns"
        :width="500"
        @change="onProductChange"
        v-model:rows="productData"
        v-model:value="form.contract_product_id"
      />
    </a-form-item>
    <s-input label="关联合同" name="contract_id" :disabled="true" v-model="form.contract_id" placeholder="自动填充" />
    <s-input label="关联客户" name="customer_id" :disabled="true" v-model="form.customer_id" placeholder="自动填充" />
    <s-select
      label="收款账号"
      name="account"
      v-model="form.account"
      dictType="collect_account"
      placeholder="请选择收款账号"
    />
    <s-upload label="收款凭证" name="voucher" v-model="form.voucher" placeholder="请上传收款凭证" />
    <s-date-picker
      label="收款时间"
      name="collect_time"
      v-model="form.collect_time"
      value-format="YYYY-MM-DD"
      placeholder="请选择收款时间"
    />
    <s-input-number
      label="收款金额"
      name="amount"
      v-model="form.amount"
      :min="0"
      :precision="2"
      placeholder="请输入收款金额"
    />
    <s-textarea
      label="备注"
      class="col-span-2"
      name="remark"
      v-model="form.remark"
      placeholder="请输入备注"
    />
  </s-modal-form>
</template>

<script lang="tsx" setup>
import { saveSingle, getSingleEdit, getSingleContractProducts } from "@/api/collect";
import { getEdit } from "@/api/contract";
import { TableColumnProps } from "@/components/Table";
import dayjs from "dayjs";

const form = reactive({
  id: undefined,
  contract_product_id: undefined,
  contract_id: undefined,
  customer_id: undefined,
  account: undefined,
  voucher: undefined,
  collect_time: dayjs(),
  amount: undefined,
  remark: undefined
});

const [register] = useModalInner();

const rules = reactive({
  contract_product_id: [{ required: true, message: "请选择合同关联产品" }],
  account: [{ required: true, message: "请选择收款账号" }],
  collect_time: [{ required: true, message: "请选择收款时间" }],
  amount: [{ required: true, message: "请输入收款金额" }]
});

const productData = ref<Recordable>({});
const contractProductInfo = ref<Recordable>({});

const productColumns = ref<TableColumnProps[]>([
  {
    title: "产品名称",
    dataIndex: "product_name",
    customRender: ({ text }) => <a>{text}</a>
  },
  {
    title: "产品价格",
    dataIndex: "price"
  },
  {
    title: "销售价格",
    dataIndex: "sale_price"
  },
  {
    title: "数量",
    dataIndex: "number"
  }
]);

/**
 * 编辑回显：恢复合同产品选择
 */
const loadSuccess = (data: Recordable) => {
  if (data.contract_product && data.contract_product.id) {
    productData.value = data.contract_product;
    contractProductInfo.value = data.contract_product;
  }
};

/**
 * 合同产品变更时，自动填充合同ID和客户ID
 */
const onProductChange = (value: any, rows: Recordable[]) => {
  if (rows && rows.length > 0) {
    const selected = rows[0];
    form.contract_id = selected.contract_id;
    // 通过合同ID查询客户ID（由后端edit接口返回）
    contractProductInfo.value = selected;
  } else {
    form.contract_id = undefined;
    form.customer_id = undefined;
    contractProductInfo.value = {};
  }
};

// 当选中合同产品后，异步获取客户信息
watch(
  () => form.contract_id,
  async (val) => {
    if (val && !form.id) {
      // 新增时通过合同ID获取客户ID
      try {
        const { data } = await getEdit(String(val));
        form.customer_id = data?.customer_id || undefined;
      } catch (e) {
        // ignore
      }
    }
  }
);

const getBindValue = computed(() => {
  return {
    width: 750,
    title: "单次服务收款",
    model: form,
    saveApi: saveSingle,
    readApi: getSingleEdit,
    labelWidth: "100px",
    layoutGrid: { col: 2, gutter: 16 },
    bodyStyle: { padding: "20px" },
    rules: rules
  };
});
</script>
