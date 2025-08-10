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
  { label: "线索名称", field: "name" },
  { label: "负责人", field: "realname" },
  { label: "手机号码", field: "mobile" },
  { label: "线索来源", field: "source_text" },
  { label: "线索行业", field: "industry_text" },
  { label: "客户级别", field: "level_text" },
  { label: "创建时间", field: "create_time" },
  { label: "线索邮箱", field: "email" },
  { label: "线索电话", field: "telephone" },
  { label: "最后跟进时间", field: "last_time_text" },
  { label: "最后跟进内容", field: "last_record", span: 2 },
  { label: "线索地址", field: "addr", span: 3 }
];
</script>
