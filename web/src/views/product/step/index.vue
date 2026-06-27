<template>
  <page-view>
    <s-table @register="register">
      <template #title>
        <div class="flex items-center gap-2">
          <a-button type="link" @click="goBack" class="!p-0">
            <arrow-left-outlined />
            返回产品列表
          </a-button>
          <a-divider type="vertical" />
          <span class="text-base font-medium">
            产品服务步骤管理
            <template v-if="productName"> - {{ productName }}</template>
          </span>
        </div>
      </template>
      <template #toolbar>
        <span class="selection-action-button" v-if="selectRowKeysLength">
          已选 {{ selectRowKeysLength }} 条 <a-divider type="vertical" /> 批量
          <s-button icon="delete-outlined" @click="handleDelete" type="primary" danger>
            删除
          </s-button>
        </span>
        <s-button v-else type="primary" icon="plus-outlined" @click="openModal">添加</s-button>
      </template>
      <template #bodyCell="{ column, record }">
        <template v-if="column?.dataIndex === 'action'">
          <a @click="openModal(true, record.id)">修改</a>
          <a-divider type="vertical" />
          <s-confirm-button label="删除" title="确认要删除吗?" @confirm="handleDelete(record.id)" />
        </template>
      </template>
    </s-table>
    <step-form @register="registerModal" @save-success="handleSuccess" />
  </page-view>
</template>

<script setup lang="ts">
import { ArrowLeftOutlined } from "@ant-design/icons-vue";
import { getStepList, destroy } from "@/api/product/step";
import stepForm from "./form.vue";
import { useRouter } from "vue-router";

const router = useRouter();

/** 从URL参数获取产品信息 */
const productId = computed(() => {
  const route = router.currentRoute.value;
  return (route.query.product_id as string) || "";
});
const productName = computed(() => {
  const route = router.currentRoute.value;
  return (route.query.product_name as string) || "";
});

/** 返回产品列表页 */
const goBack = () => {
  router.push({ name: undefined, path: "/product" });
};

/** 表格列配置 */
const columns = [
  {
    title: "步骤名称",
    dataIndex: "name"
  },
  {
    title: "序号",
    dataIndex: "sort",
    width: 80
  },
  {
    title: "下一步骤名称",
    dataIndex: "next_step_name"
  },
  {
    title: "注意事项",
    dataIndex: "attention"
  },
  {
    title: "操作",
    dataIndex: "action",
    width: 160
  }
];

const [register, { search, handleDelete, selectRowKeysLength }] = useTable({
  columns,
  listApi: getStepList,
  deleteApi: destroy,
  selection: true,
  /** 请求前自动注入 product_id 参数 */
  beforeFetch: (params: Recordable) => {
    params.product_id = productId.value;
  }
});

/** 新增/编辑成功后刷新表格 */
const handleSuccess = () => {
  search();
};

const [registerModal, { openModal }] = useModal();
</script>
