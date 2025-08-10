<template>
  <s-modal-form v-bind="getBindValue">
    <s-input label="线索名称" name="name" v-model="form.name" placeholder="请输入线索名称" />
    <s-select
      dictType="source"
      label="线索来源"
      name="source"
      v-model="form.source"
      placeholder="请输入线索来源"
    />
    <s-input label="手机号码" name="mobile" v-model="form.mobile" placeholder="请输入手机号码" />
    <s-input
      label="电话号码"
      name="telephone"
      v-model="form.telephone"
      placeholder="请输入电话号码"
    />
    <s-select
      label="客户行业"
      name="industry"
      v-model="form.industry"
      dictType="industry"
      placeholder="请输入线索行业"
    />
    <s-select
      label="客户级别"
      dictType="level"
      v-model="form.level"
      placeholder="请选择级别"
    />
    <s-input label="地址" name="addr" v-model="form.addr" placeholder="请输入地址" />
    <s-input label="邮箱" v-model="form.email" placeholder="请输入邮箱" />

    <s-textarea
      class="col-span-2"
      label="备注"
      v-model="form.remark"
      placeholder="请输入备注"
      :auto-size="{ minRows: 2, maxRows: 6 }"
    />
  </s-modal-form>
</template>
<script lang="ts" setup>
import { save, getEdit } from "@/api/clues";

const form = reactive({
  id: undefined,
  name: undefined,
  source: undefined,
  mobile: undefined,
  telephone: undefined,
  email: undefined,
  level: undefined,
  industry: undefined,
  addr: undefined,
  remark: undefined,
  status: undefined
});

const rules = reactive({
  name: [{ required: true, message: "请输入线索名称" }],
  source: [{ required: true, message: "请输入线索来源" }]
});

const getBindValue = computed(() => {
  return {
    width: 680,
    title: "线索",
    model: form,
    saveApi: save,
    readApi: getEdit,
    layoutGrid: { col: 2, gutter: 16 },
    bodyStyle: { padding: "5px" },
    rules: rules
  };
});
</script>
