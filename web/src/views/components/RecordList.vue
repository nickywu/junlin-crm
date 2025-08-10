<template>
  <div class="record-list">
    <a-spin :spinning="loading">
      <a-timeline v-if="data.length > 0">
        <a-timeline-item v-for="item in data" :key="item.id">
          <span class="time bg-[#f1f5fd] dark:bg-black">{{ item.create_time }} </span>
          <template #dot>
            <s-icon type="message-outlined" style="font-size: 16px" />
          </template>
          <div class="record-item">
            <div class="item-header text-[#2b3441] dark:text-slate-200">
              {{ item.realname }}
            </div>
            <div class="item-content">{{ item.content }}</div>
            <div class="preview-image">
              <a-image-preview-group class="d-flex flex-wrap" style="white-space: pre-line">
                <a-image
                  class="item-image"
                  v-for="file in item.imageList"
                  :src="file.url"
                  :key="file.url"
                />
              </a-image-preview-group>
            </div>
            <div class="preview-file">
              <div v-for="file in item.fileList" :key="file.id" class="item-file">
                <s-icon type="link-outlined" />
                <a class="ant-upload-list-item-name" target="_blank" :href="file.url">
                  {{ file.filename }}
                </a>
              </div>
            </div>
          </div>
        </a-timeline-item>
      </a-timeline>
      <div class="spin-content" v-else>
        <a-empty  v-if="!loading" />
      </div>
    </a-spin>
  </div>
</template>

<script setup lang="ts">
import { getRecordList } from "@/api/record";
interface FileItem {
   id: number;
   mime_type:string;
   filename: string;
   url: string;
}

interface DataType {
  id: number;
  content: string;
  create_time: string;
  realname: string;
  files: FileItem[];
  fileList: FileItem[];
  imageList: FileItem[];
}

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


const data = ref<DataType[]>([]);
const loading = ref(false);

const loadData = () => {
  const queryParams = { record_type: props.type, data_id: props.dataId };
  loading.value = true;
  getRecordList(queryParams)
    .then(res => {
      data.value = res.data;
      handleFile();
    })
    .finally(() => {
      loading.value = false;
    });
};

function handleFile() {
  data.value.forEach((item) => {
    item.fileList = [];
    item.imageList = [];
    if (Array.isArray(item.files)) {
      item.files.forEach((file) => {
        if (file.mime_type.startsWith("image")) {
          item.imageList.push(file);
        } else {
          item.fileList.push(file);
        }
      });
    }
  });
}

onMounted(() => {
  loadData();
});

defineExpose({
  loadData
});
</script>

<style lang="less" scoped>
.record-list {
  margin-top: 40px;

  .record-item {
    margin-top: 5px;
    padding: 0 12px;
    background: var(--ant-color-bg-container);
    border-radius: 4px;

    .item-header {
      padding: 10px 0;
      font-weight: 500;
      font-size: 16px;
      line-height: 1.5;
    }

    .item-content {
      padding: 10px 0;
      font-size: 14px;
    }
  }

  .time {
    color: #666;
    font-size: 12px;
    padding: 4px 10px;
    height: 20px;
    border-radius: 15px;
    font-weight: 600;
  }

  .preview-image {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    :deep(.item-image) {
      width: 104px;
      height: 104px;
      position: relative;
      padding: 8px;
      border: 1px solid var(--ant-color-border);
      border-radius: 2px;

      img {
        position: static;
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
  }

  .item-file {
    display: flex;
    margin-top: 5px;

    &:hover {
      background-color: #e6f7ff;
    }

    a {
      padding-left: 6px;
    }
  }

  .record-aciton {
    position: absolute;
    right: 30px;
  }
}
</style>
