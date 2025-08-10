<template>
  <div>
    <s-description :schema="schema" :data="data"></s-description>
  </div>
</template>

<script lang="ts" setup>
import { DescItem } from "@/components/Description";
defineProps({
  data: {
    type: Object,
    required: true
  }
});
const schema: DescItem[] = [
  { label: "客户名称", field: "customer_name" },
  { label: "商机组", field: "group_name" },
  {
    label: "商机阶段",
    field: "stage_name",
    render: (val, data) => {
      return data.is_end ? data.end_text : val;
    }
  },
  { label: "商机金额", field: "money" },
  { label: "预计成交日期", field: "deal_time" },
  { label: "备注", field: "remark" },
  { label: "负责人", field: "realname" },
  { label: "创建时间", field: "create_time" },
  { label: "最后跟进时间", field: "last_time_text" },
  { label: "最后跟进内容", field: "last_record" }
];
</script>
