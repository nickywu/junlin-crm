<template>
  <div class="m-t-10">
    <product-popover @sublimt="handleSelectedData" :selectData="selectData" ref="productRef" />
    <a-table
      size="middle"
      :columns="columns"
      :data-source="selectData"
      :pagination="false"
      rowKey="product_id"
      :scroll="{ x: 1800 }"
    >
      <template #bodyCell="{ column, record }">
        <template v-if="column.dataIndex === 'discount'">
          <a-input-number
            v-model:value="record.discount"
            :min="0"
            :max="100"
            :formatter="value => `${value}%`"
            :parser="value => value.replace('%', '')"
          />
        </template>
        <template v-else-if="column.key === 'action'">
           <s-confirm-button label="删除" title="确认要删除吗?" @confirm="onDelete(record.product_id)" />
        </template>
      </template>
    </a-table>
    <div class="flex mt-[10px] justify-end" v-if="selectData.length > 0">
      <div class="total-info">
        已选择产品：<span class="text-danger">{{ selectData.length }}</span> ，总金额：
        <span class="text-danger">{{ total }}</span> 元
      </div>
    </div>
  </div>
</template>

<script setup lang="tsx">
import ProductPopover from "./ProductPopover.vue";
import { TableColumnProps } from "@/components/Table";
const props = defineProps({
  modelValue: {
    type: Array as PropType<Recordable[]>,
    required: true
  },
  totalPrice: {
    type: [String, Number]
  }
});

const emit = defineEmits(["update:totalPrice", "update:modelValue"]);

const selectData = ref<Recordable[]>([]);
const productRef = ref();
const serviceTypeOptions = [
  { label: "单次", value: "single" },
  { label: "长期", value: "long_term" },
  { label: "周期", value: "periodic" },
  { label: "阶段", value: "stage" }
];
const periodUnitOptions = [
  { label: "无", value: "none" },
  { label: "月", value: "month" },
  { label: "年", value: "year" },
  { label: "阶段", value: "stage" }
];
const columns = ref<TableColumnProps[]>([
  {
    title: "子业务名称",
    dataIndex: "product_name",
    width: 160,
    customRender: ({ record }) => <a-input v-model:value={record.product_name} />
  },
  {
    title: "服务类型",
    dataIndex: "service_type",
    width: 120,
    customRender: ({ record }) => <a-select v-model:value={record.service_type} options={serviceTypeOptions} />
  },
  {
    title: "核心",
    dataIndex: "is_core",
    width: 80,
    customRender: ({ record }) => <a-switch v-model:checked={record.is_core} checkedValue={1} unCheckedValue={0} />
  },
  {
    title: "数量",
    dataIndex: "number",
    width: 90,
    customRender: ({ record }) => <a-input-number v-model:value={record.number} />
  },
  {
    title: "产品价格",
    dataIndex: "price",
    width: 100
  },
  {
    title: "销售价格",
    dataIndex: "sale_price",
    width: 120,
    customRender: ({ record }) => <a-input-number v-model:value={record.sale_price} />
  },
  {
    title: "折扣",
    dataIndex: "discount",
    width: 100
  },
  {
    title: "合计",
    dataIndex: "total_price",
    width: 100,
    customRender: ({ record }) => {
      return computedTotalPrice(record);
    }
  },
  {
    title: "分摊金额",
    dataIndex: "share_amount",
    width: 120,
    customRender: ({ record }) => <a-input-number v-model:value={record.share_amount} />
  },
  {
    title: "内部成本",
    dataIndex: "internal_cost",
    width: 120,
    customRender: ({ record }) => <a-input-number v-model:value={record.internal_cost} />
  },
  {
    title: "固定成本",
    dataIndex: "fixed_cost",
    width: 120,
    customRender: ({ record }) => <a-input-number v-model:value={record.fixed_cost} />
  },
  {
    title: "佣金成本",
    dataIndex: "commission_cost",
    width: 120,
    customRender: ({ record }) => <a-input-number v-model:value={record.commission_cost} />
  },
  {
    title: "其他成本",
    dataIndex: "other_cost",
    width: 120,
    customRender: ({ record }) => <a-input-number v-model:value={record.other_cost} />
  },
  {
    title: "实际成本",
    dataIndex: "actual_cost",
    width: 120,
    customRender: ({ record }) => computedActualCost(record)
  },
  {
    title: "毛利",
    dataIndex: "gross_profit",
    width: 100,
    customRender: ({ record }) => computedGrossProfit(record)
  },
  {
    title: "周期单位",
    dataIndex: "period_unit",
    width: 110,
    customRender: ({ record }) => <a-select v-model:value={record.period_unit} options={periodUnitOptions} />
  },
  {
    title: "周期数",
    dataIndex: "period_total",
    width: 100,
    customRender: ({ record }) => <a-input-number v-model:value={record.period_total} min={0} precision={0} />
  },
  {
    title: "操作",
    key: "action",
    width: 80,
    fixed: "right"
  }
]);

// 计算属性
const total = computed(() => {
  let sum = 0;
  selectData.value.forEach(item => (sum = sum + Number(item.total_price)));
  return sum.toFixed(2);
});

// 监听器
watch(
  () => props.modelValue,
  val => {
    if (val !== undefined) {
      selectData.value = val;
    }
  },
  { immediate: true, deep: true }
);

/**
 * 合并产品选择结果
 * @param selectedData 本次弹窗选择的产品列表
 */
const handleSelectedData = (selectedData: Recordable[]) => {
  const selectList: Recordable[] = [];
  selectedData.forEach(element => {
    const obj = selectData.value.find(item => {
      return item.product_id == element.product_id;
    });
    if (obj) {
      selectList.push(obj);
    } else {
      selectList.push(createItem(element));
    }
  });
  selectData.value = selectList;
  emit("update:modelValue", selectData.value);
  emit("update:totalPrice", total.value);
};

/**
 * 根据产品数据创建合同子业务默认行
 * @param selectData 产品选择器返回的产品数据
 */
const createItem = (selectData: Recordable) => {
  const salePrice = Number(selectData.price || 0);
  const internalCost = Number(selectData.internal_cost || 0);
  const fixedCost = Number(selectData.fixed_cost || 0);
  const commissionCost = Number(selectData.commission_cost || 0);
  const otherCost = Number(selectData.other_cost || 0);
  const actualCost = internalCost + fixedCost + commissionCost + otherCost;
  return {
    name: selectData.name, //产品名称
    product_name: selectData.name, //合同子业务名称快照
    service_type: selectData.service_type || "single", //服务类型
    price: selectData.price, //产品价格
    sale_price: salePrice, //销售价格
    total_price: 0, //合计
    number: 1, //数量
    unit: selectData.unit, //产品单位
    discount: 0, //折扣
    product_id: selectData.product_id, //产品id
    category: selectData.category, //类别
    share_amount: salePrice, //分摊金额
    internal_cost: internalCost, //内部成本
    fixed_cost: fixedCost, //固定成本
    commission_cost: commissionCost, //佣金成本
    other_cost: otherCost, //其他成本
    actual_cost: actualCost, //实际成本
    gross_profit: salePrice - actualCost, //毛利
    backend_profit: salePrice - actualCost, //后端产值利润
    sales_commission: Number(selectData.sales_commission || 0), //销售提成
    sales_manager_commission: Number(selectData.sales_manager_commission || 0), //销售主管提成
    work_manager_bonus: Number(selectData.work_order_manager_bonus || 0), //工单主管奖金
    worker_commission: 0, //执行人佣金
    period_unit: selectData.service_type === "long_term" ? "month" : "none", //周期单位
    period_total: Number(selectData.service_period || 0), //周期数量
    is_core: 0, //是否核心业务
    finish_status: 0, //完结状态
    display_step: "" //流程模板
  };
};

/**
 * 计算销售总价
 * @param item 子业务行数据
 */
function computedTotalPrice(item: Recordable) {
  const discount = Number(item.discount) ? item.discount / 100 : 1;
  let total_price: any = item.sale_price * item.number * discount;
  total_price = total_price.toFixed(2);
  emit("update:totalPrice", total.value);
  item.total_price = Number(total_price);
  if (!Number(item.share_amount)) {
    // 分摊金额为空时默认跟随销售总价，允许用户后续手工拆分套餐金额。
    item.share_amount = Number(total_price);
  }
  return total_price;
}

/**
 * 计算实际成本
 * @param item 子业务行数据
 */
function computedActualCost(item: Recordable) {
  const actualCost =
    Number(item.internal_cost || 0) +
    Number(item.fixed_cost || 0) +
    Number(item.commission_cost || 0) +
    Number(item.other_cost || 0);
  item.actual_cost = Number(actualCost.toFixed(2));
  return item.actual_cost;
}

/**
 * 计算毛利和后端产值利润
 * @param item 子业务行数据
 */
function computedGrossProfit(item: Recordable) {
  const grossProfit = Number(item.share_amount || item.total_price || 0) - Number(item.actual_cost || 0);
  item.gross_profit = Number(grossProfit.toFixed(2));
  item.backend_profit = Number(
    (
      grossProfit -
      Number(item.sales_commission || 0) -
      Number(item.sales_manager_commission || 0) -
      Number(item.work_manager_bonus || 0) -
      Number(item.worker_commission || 0)
    ).toFixed(2)
  );
  return item.gross_profit;
}

/**
 * 删除已选子业务
 * @param product_id 产品ID
 */
const onDelete = (product_id: string | number) => {
  selectData.value = selectData.value.filter(item => item.product_id != product_id);
  productRef.value.remove(product_id);
  emit("update:modelValue", selectData.value);
  emit("update:totalPrice", total.value);
};

watch(
  () => selectData.value,
  () => {
    selectData.value.forEach(item => {
      const discount = Number(item.discount) ? item.discount / 100 : 1;
      const total_price: any = item.sale_price * item.number * discount;
      item.total_price = Number(total_price.toFixed(2));
      item.actual_cost =
        Number(item.internal_cost || 0) +
        Number(item.fixed_cost || 0) +
        Number(item.commission_cost || 0) +
        Number(item.other_cost || 0);
      item.gross_profit = Number(item.share_amount || item.total_price || 0) - Number(item.actual_cost || 0);
      item.backend_profit =
        Number(item.gross_profit || 0) -
        Number(item.sales_commission || 0) -
        Number(item.sales_manager_commission || 0) -
        Number(item.work_manager_bonus || 0) -
        Number(item.worker_commission || 0);
    });
  },
  { deep: true }
);
</script>
