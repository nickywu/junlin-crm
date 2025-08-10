<template>
  <s-modal-form
    class="change-owner-user"
    @register="register"
    v-bind="getBindValue"
    @finish="onSubmit"
  >
    <a-form-item label="负责人" name="owner_user_id">
      <s-select-user v-model="form.owner_user_id" placeholder="请选择负责人" />
    </a-form-item>
  </s-modal-form>
</template>
<script lang="ts" setup>
const props = defineProps({
  title: {
    type: String,
    default: "更换负责人"
  },
  api: {
    type: Function,
    required: true
  }
});
const emit = defineEmits(["success", "register"]);
const { responseNotice } = useMessage();
const [register, { closeModal, changeOkLoading }] = useModalInner(ids => {
  form.ids = ids;
});

const form = reactive({
  ids: undefined,
  owner_user_id: undefined
});

const rules = reactive({
  owner_user_id: [{ required: true, message: "请选择负责人" }]
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
    const res = await props.api(form);
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
