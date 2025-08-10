<template>
  <s-table @register="register" />
</template>

<script setup lang="tsx">
import { getContractList } from "@/api/contract";

const props = defineProps({
  dataId: {
    type: [String, Number],
    required: true
  }
});

const columns = [
  {
    title: "合同编号",
    dataIndex: "order_no",
    props: {
      type: "link"
    }
  },
  {
    title: "合同名称",
    dataIndex: "name"
  },
  {
    title: "客户名称",
    dataIndex: "customer_name"
  },
  {
    title: "商机名称",
    dataIndex: "business_name"
  },
  {
    title: "签约时间",
    dataIndex: "signing_time"
  }
];

const [register] = useTable({
  columns,
  queryParams: { customer_id: props.dataId },
  listApi: getContractList,
  showTableSetting: false,
  rootStyle: { padding: 0 },
  bordered: true,
  showAction: false,
  size: "middle"
});
</script>
