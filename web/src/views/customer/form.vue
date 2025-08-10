<template>
  <s-modal-form v-bind="getBindValue">
    <s-input label="客户名称" name="name" v-model="form.name" placeholder="请输入客户名称" />
    <s-select
      label="客户来源"
      name="source"
      dictType="source"
      v-model="form.source"
      placeholder="请选择客户来源"
    />
    <s-input label="手机号码" name="mobile" v-model="form.mobile" placeholder="请输入手机号码" />
    <s-input
      label="电话号码"
      name="telephone"
      v-model="form.telephone"
      placeholder="请输入电话号码"
    />
    <s-input label="邮箱" name="email" v-model="form.email" placeholder="请输入邮箱" />
    <s-select
      label="客户行业"
      name="industry"
      dictType="industry"
      v-model="form.industry"
      placeholder="请选择客户行业"
    />
    <s-select
      label="客户级别"
      name="level"
      dictType="level"
      v-model="form.level"
      placeholder="请选择客户级别"
    />
    <s-date-picker label="下次联系时间" name="next_time" v-model="form.next_time"  value-format="YYYY-MM-DD" />
    <s-input
      class="col-span-2"
      label="地址"
      name="addr"
      v-model="form.addr"
      placeholder="请输入地址"
    />
    <s-textarea
      class="col-span-2"
      label="备注"
      name="note"
      v-model="form.note"
      placeholder="请输入备注"
    />
  </s-modal-form>
</template>
<script lang="ts" setup>
import { save, getEdit } from "@/api/customer";

const form = reactive({
  id: undefined,
  name: undefined,
  mobile: undefined,
  telephone: undefined,
  email: undefined,
  source: undefined,
  level: undefined,
  industry: undefined,
  addr: undefined,
  note: undefined,
  next_time: undefined
});

const rules = reactive({
  name: [{ required: true, message: "请输入客户名称" }]
});

const getBindValue = computed(() => {
  return {
    width: 700,
    title: "客户",
    model: form,
    saveApi: save,
    readApi: getEdit,
    labelWidth: '100px',
    layoutGrid: { col: 2, gutter: 20 },
    bodyStyle: { padding: "5px" },
    rules: rules
  };
});
</script>
