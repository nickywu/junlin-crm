<template>
  <page-view>
    <s-table @register="register">
      <template #search-createUser="{ data }">
        <s-select-user placeholder="请选择创建人" v-model="data.create_user_id"></s-select-user>
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
          <a @click="openStepManage(record)">管理步骤</a>
          <a-divider type="vertical" />
          <a @click="openModal(true, record.id)">修改</a>
          <a-divider type="vertical" />
          <s-confirm-button label="删除" title="确认要删除吗?" @confirm="handleDelete(record.id)" />
        </template>
      </template>
    </s-table>
    <product-form @register="registerModal" @save-success="handleSuccess" />
    <product-detail
      @register="registerDrawer"
      @refresh="search"
      :id="currentId"
      ref="detailRef"
      @edit="openModal(true, currentId)"
    ></product-detail>
  </page-view>
</template>

<script setup lang="ts">
import { getProductList, destroy } from "@/api/product";
import productForm from "./form.vue";
import productDetail from "./detail.vue";
import { useRouter } from "vue-router";

const router = useRouter();
const { options } = useDict(["product_category", "product_unit"]);
const currentId = ref("");
const detailRef = ref();
const searchForm = ref([
  {
    title: "关键词",
    dataIndex: "key",
    component: "Input",
    props: {
      placeholder: "请输入产品名称和产品编码搜索"
    }
  },
  {
    title: "产品类别",
    dataIndex: "category",
    component: "Select",
    props: {
      placeholder: "请选择产品类别",
      options: toRef(options, "product_category")
    }
  },
  {
    title: "产品单位",
    dataIndex: "unit",
    component: "Select",
    props: {
      placeholder: "请选择产品单位",
      options: toRef(options, "product_unit")
    }
  },
  {
    title: "创建人",
    dataIndex: "create_user_id",
    slot: "createUser"
  },
  {
    title: "添加时间",
    dataIndex: "create_time",
    component: "RangePicker"
  }
]);

const columns = [
  {
    title: "产品名称",
    dataIndex: "name",
    props: {
      type: "link",
      onClick: (row: Recordable) => {
        currentId.value = row.id;
        openDrawer(true, row.id);
      }
    }
  },
  {
    title: "产品类别",
    dataIndex: "category_text"
  },
  {
    title: "产品价格",
    dataIndex: "price"
  },
  {
    title: "产品单位",
    dataIndex: "unit_text"
  },
  {
    title: "创建人",
    dataIndex: "realname"
  },
  {
    title: "产品编码",
    dataIndex: "code"
  },
  {
    title: "产品描述",
    dataIndex: "remark"
  },
  {
    title: "添加时间",
    dataIndex: "create_time"
  }
];

const [registerDrawer, { openDrawer, getVisible }] = useDrawer();
const [register, { search, handleDelete, selectRowKeysLength }] = useTable({
  columns,
  searchForm,
  listApi: getProductList,
  deleteApi: destroy,
  selection: true
});
/** 跳转到步骤管理页面 */
const openStepManage = (record: Recordable) => {
  router.push({
    path: "/product/step",
    query: {
      product_id: record.id,
      product_name: record.name
    }
  });
};

const handleSuccess = () => {
  if (getVisible.value) {
    detailRef.value.refresh();
  }
  search();
};
const [registerModal, { openModal }] = useModal();
</script>
