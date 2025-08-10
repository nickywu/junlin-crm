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
    <div class="view-wrap">
      <a-tabs :animated="false" v-model:activeKey="state.tabKey" class="pl-[10px]">
        <a-tab-pane key="createRecord" tab="跟进记录" />
        <a-tab-pane key="contactsDetails" tab="详情信息" />
        <a-tab-pane key="actionLog" tab="操作日志" />
      </a-tabs>
      <div class="view-main">
        <component
          :is="useComponent"
          type="contacts"
          :data-id="id"
          @create="refresh(true)"
          :data="state.data"
        />
      </div>
    </div>
  </s-view-drawer>
</template>
<script lang="ts" setup>
import { read, destroy } from "@/api/contacts";
import Description from "./components/Description.vue";
import { CreateRecord, ActionLog } from "../components";
import type { Component } from "vue";
const { createConfirm, responseNotice } = useMessage();
const props = defineProps<{ id: string | number }>();
const [registerDrawer, { closeDrawer }] = useDrawerInner();
const state = reactive({
  data: {} as Recordable,
  tabKey: "",
  columns: [
    {
      name: "客户名称",
      key: "customer_name"
    },
    {
      name: "手机号码",
      key: "mobile"
    },
    {
      name: "负责人",
      key: "realname"
    },
    {
      name: "职务",
      key: "job_text"
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
    contactsDetails: Description,
    createRecord: CreateRecord,
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
  isEmit && emit("refresh");
};

defineExpose({
  refresh
});
</script>
