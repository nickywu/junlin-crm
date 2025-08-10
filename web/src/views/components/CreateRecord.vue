<template>
  <div class="record-container">
    <a-input class="record-input" v-if="visible" @click="show" placeholder="请输入内容">
      <template #prefix>
        <s-icon type="edit-outlined" />
      </template>
    </a-input>
    <s-note-editor
      v-else
      v-model.trim="form.content"
      placeholder="请输入内容"
      v-model:fileList="form.file"
    >
      <template #extra>
        <s-icon type="close-circle-outlined" class="record-close" @click="close" />
      </template>
      <template #footer>
        <s-button
          :loading="loading"
          style="float: right"
          @click="handleSubmit"
          type="primary"
          size="small"
          >发布</s-button
        >
        <div style="clear: both"></div>
      </template>
    </s-note-editor>
    <record-list :type="type" :data-id="dataId" ref="recordRef" />
  </div>
</template>

<script setup lang="ts">
import { save } from "@/api/record";
import RecordList from "./RecordList.vue";
const { message, responseNotice } = useMessage();
const props = defineProps({
  type: {
    type: [String, Number],
    required: true
  },
  dataId: {
    type: [String, Number],
    required: true
  }
});

const emit = defineEmits(["create"]);

const initialForm = {
  content: "",
  category: 1,
  file: []
};

const visible = ref(true);
const loading = ref(false);
const form = reactive({ ...initialForm });

const recordRef = ref();

const formData = computed(() => {
  const fileIds = form.file.map((file: { id: string }) => file.id);
  return {
    ...form,
    data_id: props.dataId,
    record_type: props.type,
    file: fileIds
  };
});

const show = () => {
  visible.value = false;
};

const close = () => {
  Object.assign(form, initialForm);
  visible.value = true;
};

const validationError = () => {
  if (!form.content) {
    message.error("跟进内容必填", 1.5);
    return true;
  }
  return false;
};

const handleSubmit = async () => {
  if (validationError()) return;
  loading.value = true;
  try {
    const res = await save(formData.value);
    if (res.code === 1) {
      close();
      recordRef.value?.loadData();
      emit("create");
    }
    responseNotice(res);
  } finally {
    loading.value = false;
  }
};
</script>

<style lang="less" scoped>
.record-input {
  :deep(.ant-input) {
    cursor: pointer;
  }
}
.record-close {
  border: 0;
  color: #d9d9d9;
  font-size: 22px;
  cursor: pointer;
  position: absolute;
  top: 0;
  right: 0;
  z-index: 1;
  padding: 4px;
  transform: scale(0.8);
  &:hover {
    color: #40a9ff;
  }
}
</style>
