<template>
  <div class="m-t-10">
    <product-popover @sublimt="handleSelectedData" :selectData="selectData" ref="productRef" />
    <a-table
      size="middle"
      :columns="columns"
      :data-source="selectData"
      :pagination="false"
      rowKey="product_id"
    >
      <template #bodyCell="{ column, record }">
        <template v-if="column.dataIndex === 'discount'">
          <a-input-number
            v-model:value="record.discount"
            :min="0"
            :max="100"
            :formatter="value => `${value}%`"
            :parser="value => value.replace('%', '')"
          />
        </template>
        <template v-else-if="column.key === 'action'">
           <s-confirm-button label="删除" title="确认要删除吗?" @confirm="onDelete(record.product_id)" />
        </template>
      </template>
    </a-table>
    <div class="flex mt-[10px] justify-end" v-if="selectData.length > 0">
      <div class="total-info">
        已选择产品：<span class="text-danger">{{ selectData.length }}</span> ，总金额：
        <span class="text-danger">{{ total }}</span> 元
      </div>
    </div>
  </div>
</template>

<script setup lang="tsx">
import ProductPopover from "./ProductPopover.vue";
import { TableColumnProps } from "@/components/Table";
const props = defineProps({
  modelValue: {
    type: Array as PropType<Recordable[]>,
    required: true
  },
  totalPrice: {
    type: [String, Number]
  }
});

const emit = defineEmits(["update:totalPrice", "update:modelValue"]);

const selectData = ref<Recordable[]>([]);
const productRef = ref();
const columns = ref<TableColumnProps[]>([
  {
    title: "产品名称",
    dataIndex: "name"
  },
  {
    title: "数量",
    dataIndex: "number",
    customRender: ({ record }) => <a-input-number v-model:value={record.number} />
  },
  {
    title: "产品价格",
    dataIndex: "price"
  },
  {
    title: "销售价格",
    dataIndex: "sale_price",
    customRender: ({ record }) => <a-input-number v-model:value={record.sale_price} />
  },
  {
    title: "折扣",
    dataIndex: "discount"
  },
  {
    title: "合计",
    dataIndex: "total_price",
    customRender: ({ record }) => {
      return computedTotalPrice(record);
    }
  },
  {
    title: "操作",
    key: "action"
  }
]);

// 计算属性
const total = computed(() => {
  let sum = 0;
  selectData.value.forEach(item => (sum = sum + Number(item.total_price)));
  return sum.toFixed(2);
});

// 监听器
watch(
  () => props.modelValue,
  val => {
    if (val !== undefined) {
      selectData.value = val;
    }
  },
  { immediate: true, deep: true }
);

const handleSelectedData = (selectedData: Recordable[]) => {
  const selectList: Recordable[] = [];
  selectedData.forEach(element => {
    const obj = selectData.value.find(item => {
      return item.product_id == element.product_id;
    });
    if (obj) {
      selectList.push(obj);
    } else {
      selectList.push(createItem(element));
    }
  });
  selectData.value = selectList;
  emit("update:modelValue", selectData.value);
  emit("update:totalPrice", total.value);
};

const createItem = (selectData: Recordable) => {
  return {
    name: selectData.name, //产品名称
    price: selectData.price, //产品价格
    sale_price: selectData.price, //销售价格
    total_price: 0, //合计
    number: 1, //数量
    unit: selectData.unit, //产品单位
    discount: 0, //折扣
    product_id: selectData.product_id, //产品id
    category: selectData.category //类别
  };
};

// 计算总价
function computedTotalPrice(item: Recordable) {
  const discount = Number(item.discount) ? item.discount / 100 : 1;
  let total_price: any = item.sale_price * item.number * discount;
  total_price = total_price.toFixed(2);
  emit("update:totalPrice", total.value);
  item.total_price = Number(total_price);
  return total_price;
}

const onDelete = (product_id: string | number) => {
  selectData.value = selectData.value.filter(item => item.product_id != product_id);
  productRef.value.remove(product_id);
  emit("update:modelValue", selectData.value);
  emit("update:totalPrice", total.value);
};

watch(
  () => selectData.value,
  () => {
    selectData.value.forEach(item => {
      const discount = Number(item.discount) ? item.discount / 100 : 1;
      const total_price: any = item.sale_price * item.number * discount;
      item.total_price = Number(total_price.toFixed(2));
    });
  },
  { deep: true }
);
</script>
