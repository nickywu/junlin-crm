<template>
  <div class="select-product">
    <a-popover
      overlayClassName="product-popover"
      :overlayStyle="{ width: '700px' }"
      v-model:open="visible"
      placement="left"
      trigger="click"
    >
      <template #title>
        <span class="text-base">选择产品</span>
        <a-divider style="margin: 14px 0"></a-divider>
      </template>
      <template #content>
        <div class="search d-flex">
          <a-input-search
            style="width: 200px"
            placeholder="请输入商品名称搜索"
            v-model:value="searchValue"
            @search="onSearch"
          />
          <s-button @click="onReset" class="reset" icon="reload-outlined"></s-button>
        </div>
        <s-table @register="register" />
        <a-divider style="margin: 10px 0 14px"></a-divider>
        <a-flex gap="middle" justify="flex-end">
          <a-button @click="cancel">取消</a-button>
          <a-button type="primary" @click="handleSubmit"> 确定 </a-button>
        </a-flex>
      </template>
      <a-button type="primary" class="product-btn" size="small">添加产品</a-button>
    </a-popover>
  </div>
</template>

<script setup lang="ts">
import { getProductList } from "@/api/product";
import { cloneDeep } from "lodash-es";
import type { TableProps } from "ant-design-vue";
import { ComputedRef, PropType } from "vue";
type Key = string | number;

const props = defineProps({
  selectData: {
    type: Array as PropType<Recordable[]>,
    default: () => []
  }
});

const emit = defineEmits(["sublimt"]);

const columns = [
  {
    title: "产品名称",
    dataIndex: "name"
  },
  {
    title: "价格",
    dataIndex: "price"
  },
  {
    title: "类别",
    dataIndex: "category_text"
  },
  {
    title: "单位",
    dataIndex: "unit_text"
  }
];

// 响应式数据
const selectedRowKeys = ref<Key[]>([]);
const selectedRow = ref<Recordable[]>([]);
const visible = ref(false);
const searchValue = ref();

// 计算属性
const rowSelection: ComputedRef<TableProps["rowSelection"]> = computed(() => {
  return {
    selectedRowKeys: selectedRowKeys.value,
    hideSelectAll: true,
    onChange: keys => {
      selectedRowKeys.value = keys;
    },
     onSelect: async (record, selected) => {
      //await nextTick();
      if (selected) {
        selectedRow.value.push(record);
      } else {
        selectedRow.value = selectedRow.value.filter(item => item.id !== record.id);
      }
      selectedRowKeys.value = selectedRow.value.map((item: Recordable) => item.id);
    },
  };
});

const [register, { refresh, search }] = useTable({
  columns,
  size: "middle",
  listApi: getProductList,
  rowSelection: rowSelection,
  scroll: { x: 500, y: 300 },
  rootStyle: { padding: 0 },
  showAction: false,
  showTableSetting: false
});

const onSearch = () => {
  search({ key: searchValue.value });
};

const onReset = () => {
  searchValue.value = undefined;
  refresh();
};

// 监听器
watch(
  () => props.selectData,
  value => {
    if (value.length) {
      selectedRowKeys.value = value.map(item => item.product_id);
      value.forEach(item => (item.id = item.product_id));
      selectedRow.value = cloneDeep(value);
    }
  },
  { immediate: true }
);

// 方法
const cancel = () => {
  visible.value = false;
};

const handleSubmit = () => {
  console.log("selectedRow", selectedRow.value);
  visible.value = false;
  emit("sublimt", cloneDeep(selectedRow.value));
};

const remove = (key: string) => {
  selectedRowKeys.value = selectedRowKeys.value.filter(item => item !== key);
  selectedRow.value = selectedRow.value.filter(item => item.id !== key);
};

defineExpose({
  remove
});
</script>

<style lang="less" scoped>
.select-product {
  overflow: hidden;
}
.product-btn {
  float: right;
  margin-bottom: 10px;
}
.product-popover {
  :deep(.ant-popover-inner-content) {
    padding: 0px;
  }
}
.search {
  margin: 10px 0 20px 0px;
}
.reset {
  padding: 5px;
  margin-left: 10px;
  font-size: 12px;
}
</style>
