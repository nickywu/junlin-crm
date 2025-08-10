<template>
  <s-modal-form
    class="change-owner-user"
    @register="register"
    v-bind="getBindValue"
    @finish="onSubmit"
  >
    <s-select
      label="成交状态"
      name="deal_status"
      dictType="cust_deal_status"
      v-model="form.deal_status"
      placeholder="请选择成交状态"
    />
  </s-modal-form>
</template>
<script lang="ts" setup>
import { changeDealStatus } from "@/api/customer";
const props = defineProps({
  title: {
    type: String,
    default: "更改成交状态"
  }
});
const emit = defineEmits(["success", "register"]);
const { responseNotice } = useMessage();
const [register, { closeModal, changeOkLoading }] = useModalInner(ids => {
  form.ids = ids;
});

const form = reactive({
  ids: undefined,
  deal_status: undefined
});

const rules = reactive({
  deal_status: [{ required: true, message: "请选择成交状态" }]
});

const attrs = useAttrs();

const getBindValue = computed(() => {
  return {
    ...attrs,
    width: 450,
    modalTitle: props.title,
    model: form,
    minHeight: 150,
    useContainer: false,
    rules: rules
  };
});

const onSubmit = async () => {
  try {
    changeOkLoading(true);
    const res = await changeDealStatus(form);
    responseNotice(res);
    if (res.code == 1) {
      closeModal();
      emit("success");
    }
  } finally {
    changeOkLoading(false);
  }
};
</script>
