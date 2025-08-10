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
        <a-tab-pane key="details" tab="详情信息" />
        <a-tab-pane key="actionLog" tab="操作日志" />
      </a-tabs>
      <div class="view-main">
        <component :is="useComponent" type="product" :data-id="id" :data="state.data" />
      </div>
    </div>
  </s-view-drawer>
</template>
<script lang="ts" setup>
import { read, destroy } from "@/api/product";
import Description from "./components/Description.vue";
import { ActionLog } from "../components";
import type { Component } from "vue"
const { createConfirm, responseNotice } = useMessage();
const props = defineProps<{ id: string | number }>();
const [registerDrawer, { closeDrawer }] = useDrawerInner();
const state = reactive({
  data: {} as Recordable,
  tabKey: "",
  columns: [
    {
      name: "产品类别",
      key: "category_text"
    },
    {
      name: "产品编码",
      key: "code"
    },
    {
      name: "产品价格",
      key: "price"
    },
    {
      name: "产品单位",
      key: "unit_text"
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
    details: Description,
    actionLog: ActionLog
  };
  return Map[state.tabKey];
});

const visibleChange = (visible: boolean) => {
  if (!visible) return;
  initTabKey();
  loadData();
};
const loadData = () => {
  if (!props.id) return;
  read(props.id).then(res => {
    if (res.code == 1) {
      state.data = res.data;
    }
  });
};

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
const initTabKey = () => {
  state.tabKey = "details";
};
const refresh = () => {
  loadData();
};

defineExpose({
  refresh
});
</script>
