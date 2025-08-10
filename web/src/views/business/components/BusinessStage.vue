<template>
  <div class="view-steps">
    <a-steps :current="current" :status="status" @change="onChange" size="small">
      <a-step
        v-for="(item, index) in businessStage"
        :disabled="index === stageLength"
        :key="item.id"
        :status="index === stageLength && data.is_end != 0 ? status : ''"
      >
        <template v-if="index === stageLength" #title>
          <a-dropdown>
            <span> {{ data.end_text }} <s-icon type="down-outlined" /> </span>
            <template #overlay>
              <a-menu @click="handleEnd">
                <a-menu-item key="1" title="赢单">
                  <a href="javascript:;">赢单 100%</a>
                </a-menu-item>
                <a-menu-item key="2" title="输单">
                  <a href="javascript:;">输单 0%</a>
                </a-menu-item>
                <a-menu-item key="3" title="无效">
                  <a href="javascript:;">无效 0%</a>
                </a-menu-item>
              </a-menu>
            </template>
          </a-dropdown>
        </template>

        <template v-else #title>
          <a-popover placement="bottom">
            <template #content>
              <div>{{ item.name }}</div>
              <div>赢单率：{{ item.rate }}%</div>
            </template>
            <span>{{ item.name }}</span>
          </a-popover>
        </template>
      </a-step>
    </a-steps>
  </div>
</template>

<script setup lang="ts">
import { getBusinessStage, changeBusinessStage, endStage } from "@/api/business";
import type { MenuProps } from "ant-design-vue";
const { responseNotice, createConfirm, message } = useMessage();

// 商机阶段类型
interface BusinessStage {
  id: number | string;
  name: string;
  rate?: string;
  sort?: number;
  group_id?: number;
}

// 商机业务数据类型
interface BusinessData {
  id: number | string;
  stage_id: number | string;
  is_end: 0 | 1 | 2 | 3;
  end_text: string;
  [key: string]: any;
}

const emit = defineEmits<{
  (e: "refresh"): void;
}>();

const props = defineProps<{ id: string | number }>();

const current = ref<number | undefined>(undefined);
const status = ref<"process" | "finish" | "error">("process");
const businessStage = ref<BusinessStage[]>([]);
const data = ref<BusinessData>({
  id: "",
  stage_id: "",
  is_end: 0,
  end_text: ""
});

const stageLength = computed(() => businessStage.value.length - 1);

const getCurrentIndex = (stage_id: string | number) => {
  if (data.value.is_end) {
    if (data.value.is_end === 1) {
      current.value = businessStage.value.length - 1;
      setStepStatus();
      return;
    }
    setStepStatus();
  }
  current.value = businessStage.value.findIndex(item => item.id == stage_id);
};

const loadData = async () => {
  try {
    const res = await getBusinessStage(props.id);
    const { stage_data, business } = res.data;
    businessStage.value = [...stage_data, { name: "结束", id: Date.now() }];
    data.value = business;
    getCurrentIndex(business.stage_id);
  } catch (error) {
    console.error("加载数据失败:", error);
  }
};

const setStepStatus = () => {
  const { is_end } = data.value;
  switch (is_end) {
    case 1:
      status.value = "finish";
      break;
    case 2:
    case 3:
      status.value = "error";
      break;
    default:
      status.value = "process";
  }
};

// 结束商机
const handleEnd: MenuProps["onClick"] = info => {
  const { key, item } = info;
  if (data.value.is_end) {
    message.error("商机已结束无法推进", 1.5);
    return;
  }
  const confirmData = { id: props.id, is_end: key };
  createConfirm({
    title: `确定将当前商机设为『${item.title}』吗？`,
    onOk: async () => {
      const res = await endStage(confirmData);
      emit("refresh");
      responseNotice(res);
      await loadData();
    }
  });
};

// 切换阶段
const onChange = (index: number) => {
  if (data.value.is_end) {
    message.error("商机已结束无法推进", 1.5);
    return;
  }
  const currentItem = businessStage.value[index];
  if (!currentItem) return;
  const requestData = { id: props.id, stage_id: currentItem.id };
  createConfirm({
    title: `确定要推进到『${currentItem.name}』阶段吗？`,
    onOk: async () => {
      const res = await changeBusinessStage(requestData);
      emit("refresh");
      responseNotice(res);
      if (res.code === 1) {
        current.value = index;
      }
    }
  });
};

onMounted(() => {
  loadData();
});

defineExpose({
  loadData
});
</script>

<style lang="less" scoped>
.view-steps {
  margin: 10px 0;
  padding: 10px;
  background: var(--ant-color-bg-container);

  :deep(.ant-steps) {
    padding: 16px;
  }
  :deep(.ant-steps-item-disabled) {
    cursor: pointer;
  }
  :deep(.ant-steps-item-finish .ant-steps-item-icon) {
    background: #00ca9d;
    background-color: #00ca9d;
    border-color: #00ca9d;
    &:hover {
      border-color: #00ca9d !important;
      color: #fff !important;
    }
  }

  :deep(.ant-steps-item-finish .ant-steps-item-icon > .ant-steps-icon) {
    color: #fff;
  }

  :deep(.ant-steps-item-error .ant-steps-item-icon) {
    background: #ff4d4f;
    background-color: #ff4d4f;
    border-color: #ff4d4f;
  }

  :deep(.ant-steps-item-error .ant-steps-item-icon > .ant-steps-icon) {
    color: #fff;
  }
}
</style>
