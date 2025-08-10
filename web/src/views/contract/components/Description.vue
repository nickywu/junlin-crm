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
  { label: "合同编号", field: "order_no" },
  { label: "合同名称", field: "name" },
  { label: "客户名称", field: "customer_name" },
  { label: "商机名称", field: "business_name" },
  { label: "签约时间", field: "signing_time" },
  { label: "合同金额", field: "money" },
  { label: "合同开始时间", field: "start_time" },
  { label: "合同结束时间", field: "end_time" },
  { label: "客户签约人", field: "contacts_name" },
  { label: "公司签约人", field: "realname" },
  { label: "最后跟进时间", field: "last_time_text" },
  { label: "最后跟进记录", field: "last_record" },
  { label: "添加人", field: "create_user_name" },
  { label: "负责人", field: "owner_user_name" },
  { label: "添加时间", field: "create_time" },
  { label: "合同状态", field: "status_text" },
  { label: "备注", field: "remark", span: 2 }
];
</script>
