<template>
  <s-view-drawer
    v-bind="$attrs"
    :data="state.data"
    :columns="state.columns"
    @visible-change="visibleChange"
    :title="state.data?.name"
    @register="registerDrawer"
  >
    <template #extra>
      <s-button icon="edit-outlined" type="primary" @click="emit('edit')"> 编辑 </s-button>
      <s-button icon="delete-outlined" @click="onDelete"> 删除 </s-button>
    </template>
    <div class="view-wrap">
      <a-tabs :animated="false" v-model:activeKey="state.tabKey" class="pl-[10px]">
        <a-tab-pane key="enterpriseDetails" tab="详情信息" />
        <a-tab-pane key="actionLog" tab="操作日志" />
      </a-tabs>
      <div class="view-main">
        <component
          :is="useComponent"
          type="enterprise"
          :data-id="id"
          @create="refresh(true)"
          :data="state.data"
        />
      </div>
    </div>
  </s-view-drawer>
</template>
<script lang="ts" setup>
import { read, destroy } from "@/api/enterprise";
import Description from "./components/Description.vue";
import { ActionLog } from "../components";
import type { Component } from "vue";
const { createConfirm, responseNotice } = useMessage();
const props = defineProps<{ id: string | number }>();
const [registerDrawer, { closeDrawer }] = useDrawerInner();
const state = reactive({
  data: {} as Recordable,
  tabKey: "",
  columns: [
    {
      name: "关联客户",
      key: "customer_name"
    },
    {
      name: "法定代表人",
      key: "legal_person"
    },
    {
      name: "纳税性质",
      key: "tax_nature_text"
    },
    {
      name: "联系人",
      key: "contact_person"
    },
    {
      name: "负责人",
      key: "realname"
    },
    {
      name: "创建时间",
      key: "create_time"
    }
  ]
});

const emit = defineEmits(["refresh", "register", "edit"]);

const useComponent = computed(() => {
  const Map: Record<string, Component> = {
    enterpriseDetails: Description,
    actionLog: ActionLog
  };
  return Map[state.tabKey];
});

/**
 * 抽屉可见性变更时，重新加载数据
 * @param visible 是否可见
 */
const visibleChange = (visible: boolean) => {
  if (!visible) return;
  initTabKey();
  loadData();
};

/** 加载详情数据 */
const loadData = () => {
  if (!props.id) return;
  read(props.id).then(res => {
    if (res.code == 1) {
      state.data = res.data;
    }
  });
};

/** 初始化Tab为详情信息 */
const initTabKey = () => {
  state.tabKey = "enterpriseDetails";
};

/** 删除 */
const onDelete = () => {
  createConfirm({
    title: "提示",
    content: `确定要将『${state.data.name}』删除吗?`,
    centered: true,
    onOk: () => {
      return destroy(props.id).then(res => {
        responseNotice(res);
        if (res.code == 1) {
          emit("refresh");
          closeDrawer();
        }
      });
    }
  });
};

/**
 * 刷新明细数据
 * @param isEmit 是否触发外部刷新
 */
const refresh = (isEmit = false) => {
  loadData();
  isEmit && emit("refresh");
};

defineExpose({
  refresh
});
</script>
<style lang="less" scoped>
.view-more {
  .ant-dropdown-menu-item {
    padding: 10px 12px !important;
  }
  .anticon {
    font-size: 14px;
  }
}
</style>
