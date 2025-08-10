<template>
  <s-table @register="register" />
</template>

<script setup lang="tsx">
import { getProduct } from "@/api/business";
const props = defineProps({
  dataId: {
    type: [String, Number],
    required: true
  }
});

const columns = [
  {
    title: "产品名称",
    dataIndex: "name"
  },
  {
    title: "数量",
    dataIndex: "number"
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
    title: "折扣(%)",
    dataIndex: "discount"
  },
  {
    title: "合计",
    dataIndex: "total_price"
  }
];

const [register] = useTable({
  columns,
  queryParams: { id: props.dataId },
  listApi: getProduct,
  showTableSetting: false,
  rootStyle: { padding: 0 },
  bordered: true,
  showAction: false,
  size: "middle"
});
</script>
