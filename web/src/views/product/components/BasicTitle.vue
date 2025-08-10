<template>
  <div class="basic-info-title">
    <div class="title">{{ title }}</div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  title: {
    type: String,
    default: "基本信息"
  }
});
</script>

<style scoped>
.basic-info-title {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.basic-info-title .title {
  font-weight: bold;
  margin-left: 5px;
  color:var(--ant-color-text);
}
.basic-info-title::before {
  content: "";
  display: inline-block;
  width: 4px;
  height: 16px;
  background-color: var(--ant-color-primary);
  border-radius: 10px;
  margin-right: 5px;
  margin-bottom: 1px;
}
</style>
