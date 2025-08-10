<template>
  <s-view-drawer
    v-bind="$attrs"
    @visible-change="visibleChange"
    :data="state.data"
    :columns="state.columns"
    :title="state.data.name"
    @register="registerDrawer"
  >
    <template #extra>
      <s-button icon="edit-outlined" type="primary" @click="emit('edit')"> 编辑 </s-button>
      <s-button icon="retweet-outlined" @click="emit('changeOwnerUser')"> 更换负责人 </s-button>
      <s-button icon="delete-outlined" @click="onDelete"> 删除 </s-button>
    </template>
    <div class="view-wrap">
      <a-tabs :animated="false" v-model:activeKey="state.tabKey" class="pl-[10px]">
        <a-tab-pane key="createRecord" tab="跟进记录" />
        <a-tab-pane key="contractDetails" tab="详情信息" />
        <a-tab-pane key="productList" tab="产品" />
        <a-tab-pane key="actionLog" tab="操作日志" />
      </a-tabs>
      <div class="view-main">
        <component
          :is="useComponent"
          type="contract"
          :data-id="id"
          @create="refresh(true)"
          :data="state.data"
        />
      </div>
    </div>
  </s-view-drawer>
</template>
<script lang="ts" setup>
import { read, destroy } from "@/api/contract";
import { CreateRecord, ActionLog } from "../components";
import { Description, ProductList } from "./components";
import type { Component } from "vue";
const props = defineProps<{ id: string | number }>();
const { createConfirm, responseNotice } = useMessage();
const [registerDrawer, { closeDrawer }] = useDrawerInner();
const state = reactive({
  data: {} as Recordable,
  tabKey: "createRecord",
  columns: [
    {
      name: "合同编号",
      key: "order_no"
    },
    {
      name: "客户名称",
      key: "customer_name"
    },
    {
      name: "合同金额",
      key: "money"
    },
    {
      name: "签约时间",
      key: "signing_time"
    },
    {
      name: "负责人",
      key: "owner_user_name"
    },
    {
      name: "合同状态",
      key: "status_text"
    }
  ]
});

const emit = defineEmits(["refresh", "register", "edit", "changeOwnerUser"]);

const useComponent = computed(() => {
  const Map: Record<string, Component> = {
    contractDetails: Description,
    createRecord: CreateRecord,
    actionLog: ActionLog,
    productList: ProductList
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

const initTabKey = () => {
  state.tabKey = "createRecord";
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
