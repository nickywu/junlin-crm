<template>
  <s-table @register="register" />
</template>

<script setup lang="tsx">
import { getContactsList } from "@/api/contacts";
const props = defineProps({
  dataId: {
    type: [String, Number],
    required: true
  }
});

const columns = [
  {
    title: "名称",
    dataIndex: "name"
  },
  {
    title: "手机号码",
    dataIndex: "mobile"
  },
  {
    title: "关键决策人",
    dataIndex: "decision_text"
  },
  {
    title: "职务",
    dataIndex: "job_text"
  }
];

const [register] = useTable({
  columns,
  queryParams: { customer_id: props.dataId },
  listApi: getContactsList,
  showTableSetting: false,
  rootStyle: { padding: 0 },
  bordered: true,
  showAction: false,
  size: "middle"
});
</script>
