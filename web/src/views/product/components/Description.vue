<template>
  <div>
    <basic-title title="基本信息" />
    <s-description class="p-[10px]" :schema="schema" :data="data" :bordered="false"></s-description>
    <template v-if="data.cover_image">
      <basic-title title="产品主图" />
      <a-image class="p-[10px]" :src="data.cover_image" :width="200"></a-image>
    </template>
    <template v-if="detailImage.length > 0">
      <basic-title title="产品详情图" />
      <a-image-preview-group class="p-[10px]">
        <a-image :width="200" v-for="item in detailImage" :key="item" :src="item" />
      </a-image-preview-group>
    </template>
  </div>
</template>

<script lang="ts" setup>
import BasicTitle from "./BasicTitle.vue";
const props = defineProps({
  data: {
    type: Object,
    required: true
  }
});

const detailImage = computed(() => {
  if (props?.data?.detail_image) {
    return props.data.detail_image.split(",");
  }
  return [];
});

const schema = [
  { label: "产品名称", field: "name" },
  { label: "产品类别", field: "category_text" },
  { label: "产品编码", field: "code" },
  { label: "单位", field: "unit_text" },
  { label: "产品价格", field: "price" },
  { label: "添加人", field: "realname" },
  { label: "创建时间", field: "create_time" },
  { label: "产品描述", field: "remark" }
];
</script>
