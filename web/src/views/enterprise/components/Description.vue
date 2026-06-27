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
  { label: "企业名称", field: "name" },
  { label: "关联客户", field: "customer_name" },
  { label: "法定代表人", field: "legal_person" },
  { label: "负责人", field: "realname" },
  { label: "纳税性质", field: "tax_nature_text" },
  { label: "联系人", field: "contact_person" },
  { label: "联系电话", field: "contact_phone" },
  { label: "联系邮箱", field: "contact_email" },
  { label: "注册资本", field: "registered_capital" },
  { label: "企业地址", field: "address", span: 3 },
  { label: "经营范围", field: "business_scope", span: 3 },
  { label: "创建时间", field: "create_time" },
  { label: "备注", field: "note", span: 3 }
];
</script>
