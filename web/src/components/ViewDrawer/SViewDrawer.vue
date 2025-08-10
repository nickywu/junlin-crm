<template>
  <s-drawer
    width="70%"
    class="s-view-drawer"
    @register="register"
    v-bind="$attrs"
    :closable="false"
    :wrapStyle="{ padding: 0,background: 'var(--ant-color-bg-container)' }"
    destroyOnClose
  >
    <div class="view-container bg-[#f0f2f5] dark:bg-black">
      <div class="custom-close-btn flex-center" @click="closeDrawer">
        <s-icon type="close-outlined" />
      </div>
      <div class="view-header">
        <div class="view-title flex items-center">
          <s-icon class="view-icon" :type="icon" />
          <div class="view-section-text flex-1">
            <span class="title text-[#333] dark:text-gray-200">{{ title }}</span>
          </div>
          <div class="view-section-aciton">
            <slot name="extra"></slot>
          </div>
        </div>

        <div class="view-descriptions" v-if="columns.length">
          <div class="view-descriptions-item" v-for="item in columns" :key="item.key">
            <div class="view-descriptions-item-title">{{ item.name }}</div>
            <div class="view-descriptions-item-content dark:text-gray-200">
              {{ data[item.key] || "-" }}
            </div>
          </div>
        </div>
        <div v-else class="view-descriptions">
          <slot name="view"></slot>
        </div>
      </div>

      <div class="view-content">
        <slot></slot>
      </div>
    </div>
  </s-drawer>
</template>

<script setup lang="ts">
import { useRoute } from "vue-router";
const [register, { closeDrawer }] = useDrawerInner();
defineProps({
  title: {
    type: String
  },
  columns: {
    type: Array as PropType<Array<{ key: string; name: string }>>,
    default: () => []
  },
  icon: {
    type: String,
    default: () => {
      const route = useRoute();
      return route.meta.icon || "appstore-outlined";
    }
  },
  data: {
    type: Object,
    default: () => ({})
  }
});

defineEmits(["register"]);
</script>

<style lang="less" scoped>
.s-view-drawer {
  :deep(.ant-drawer-body .scrollbar__wrap) {
    padding: 0;
  }
  .view-header {
    background: var(--ant-color-bg-container);
    padding: 10px;
    .view-section-aciton {
      margin-right: 50px;
      :deep(.ant-btn) {
        margin-right: 10px;
      }
    }
    .view-icon {
      width: 36px;
      height: 36px;
      line-height: 40px;
      color: #1890ff;
      background-color: #e6f7ff;
      display: inline-block;
      text-align: center;
      border-radius: 2px;
      margin-right: 10px;
    }
    .view-text-subtitle {
      color: #999;
      font-size: 12px;
    }
  }
  .title {
    font-weight: 700;
    font-size: 18px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .view-descriptions {
    display: flex;
    margin-top: 20px;
  }
  .view-descriptions-item {
    width: 150px;
    margin: 0px 10px 10px 0px;
  }
  .view-descriptions-item-title {
    color: #959ea6;
    font-size: 12px;
  }
  .view-descriptions-item-content {
    font-size: 13px;
  }

  .custom-close-btn {
    position: absolute;
    width: 25px;
    height: 25px;
    right: 14px;
    top: 15px;
    font-size: 14px;
    cursor: pointer;
    z-index: 10;
    padding: 14px;
  }
}
</style>
