<template>
  <s-table @register="register" />
</template>

<script setup lang="ts">
import { getActionLog } from "@/api/action_log";

const props = defineProps({
  type: {
    type: String,
    required: true
  },
  dataId: {
    type: [String, Number],
    required: true
  }
});

const columns = [
  {
    title: "所属操作",
    dataIndex: "action_name"
  },
  {
    title: "操作内容",
    dataIndex: "content"
  },
  {
    title: "操作人",
    dataIndex: "realname"
  },
  {
    title: "操作时间",
    dataIndex: "create_time"
  }
];
const [register] = useTable({
  columns,
  queryParams: {
    data_id: props.dataId,
    type: props.type
  },
  showTableSetting: false,
  rootStyle: { padding: 0 },
  bordered:true,
  showAction:false,
  size:"middle",
  listApi: getActionLog
});
</script>
