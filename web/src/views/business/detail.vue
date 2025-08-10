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
      <s-button icon="retweet-outlined" @click="emit('changeOwnerUser')">
        更换负责人
      </s-button>
      <s-button icon="delete-outlined" @click="onDelete"> 删除 </s-button>
    </template>
    <business-stage :id="id" @refresh="refresh" ref="stageRef" />
    <div class="view-wrap">
      <a-tabs :animated="false" v-model:activeKey="state.tabKey" class="pl-[10px]">
        <a-tab-pane key="createRecord" tab="跟进记录" />
        <a-tab-pane key="businessDetails" tab="详情信息" />
        <a-tab-pane key="productList" tab="产品" />
        <a-tab-pane key="actionLog" tab="操作日志" />
      </a-tabs>
      <div class="view-main">
        <component
          :is="useComponent"
          type="business"
          :data-id="id"
          @create="refresh(true)"
          :data="state.data"
        />
      </div>
    </div>
  </s-view-drawer>
</template>
<script lang="ts" setup>
import { read, destroy } from "@/api/business";
import { Description, ProductList, BusinessStage } from "./components";
import { CreateRecord, ActionLog } from "../components";
import type { Component } from "vue";
const { createConfirm, responseNotice } = useMessage();
const props = defineProps<{ id: string | number }>();
const [registerDrawer, { closeDrawer }] = useDrawerInner();
const stageRef = ref();
const state = reactive({
  data: {} as Recordable,
  tabKey: "",
  columns: [
    {
      name: "客户名称",
      key: "customer_name"
    },
    {
      name: "商机金额",
      key: "money"
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

const emit = defineEmits(["refresh", "register", "edit", "changeOwnerUser"]);

const useComponent = computed(() => {
  const Map: Record<string, Component> = {
    businessDetails: Description,
    createRecord: CreateRecord,
    productList: ProductList,
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
  state.tabKey = "createRecord";
};

const refresh = (isEmit = false) => {
  loadData();
  stageRef.value.loadData()
  isEmit && emit("refresh");
};

defineExpose({
  refresh
});
</script>
