<template>
  <div>
    <s-description :schema="schema" :data="data"></s-description>
  </div>
</template>

<script lang="ts" setup>
defineProps({
  data: {
    type: Object,
    required: true
  }
});
const schema = [
  { label: "客户名称", field: "customer_name" },
  { label: "联系人名称", field: "name" },
  { label: "负责人", field: "realname" },
  { label: "手机号码", field: "mobile" },
  { label: "关键决策人", field: "primary_text" },
  { label: "性别", field: "gender_text" },
  { label: "职务", field: "job_text" },
  { label: "创建时间", field: "create_time" },
  { label: "最后跟进内容", field: "last_record" },
  { label: "最后跟进时间", field: "last_time_text" },
  { label: "地址", field: "addr" },
  { label: "邮箱", field: "email" },
  { label: "电话", field: "telephone" },
  { label: "备注", field: "remark" }
];
</script>
