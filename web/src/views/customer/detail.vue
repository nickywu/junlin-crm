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
      <s-button icon="retweet-outlined" @click="openStatusModal(true, id)"> 更改成交状态 </s-button>
      <s-button icon="check-square-outlined"  @click="emit('changeOwnerUser')"> 分配 </s-button>
      <s-button icon="delete-outlined" @click="onDelete"> 删除 </s-button>
    </template>
    <div class="view-wrap">
      <a-tabs :animated="false" v-model:activeKey="state.tabKey" class="pl-[10px]">
        <a-tab-pane key="createRecord" tab="跟进记录" />
        <a-tab-pane key="customerDetails" tab="详情信息" />
        <a-tab-pane key="contactsList" tab="联系人" />
        <a-tab-pane key="businessList" tab="商机" />
        <a-tab-pane key="contractList" tab="合同" />
        <a-tab-pane key="actionLog" tab="操作日志" />
      </a-tabs>
      <div class="view-main">
        <component
          :is="useComponent"
          type="customer"
          :data-id="id"
          @create="refresh(true)"
          :data="state.data"
        />
      </div>
    </div>
  </s-view-drawer>
  <change-status @register="registerStatusModal" @success="refresh(true)" />
</template>
<script lang="ts" setup>
import { read, destroy } from "@/api/customer";
import Description from "./components/Description.vue";
import { CreateRecord, ActionLog } from "../components";
import { BusinessList, ContactsList, ContractList, ChangeStatus } from "./components";
import type { Component } from "vue";
const props = defineProps<{ id: string | number }>();
const [registerStatusModal, { openModal: openStatusModal }] = useModal();
const { createConfirm, responseNotice } = useMessage();
const [registerDrawer, { closeDrawer }] = useDrawerInner();
const state = reactive({
  data: {} as Recordable,
  tabKey: "createRecord",
  columns: [
    {
      name: "成交状态",
      key: "deal_status_text"
    },
    {
      name: "负责人",
      key: "realname"
    },
    {
      name: "手机号码",
      key: "mobile"
    },
    {
      name: "客户级别",
      key: "level_text"
    },
    {
      name: "客户来源",
      key: "source_text"
    }
  ]
});

const emit = defineEmits(["refresh", "register", "edit", "changeOwnerUser"]);

const useComponent = computed(() => {
  const Map: Record<string, Component> = {
    customerDetails: Description,
    createRecord: CreateRecord,
    actionLog: ActionLog,
    businessList: BusinessList,
    contactsList: ContactsList,
    contractList: ContractList
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
