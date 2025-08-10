<template>
  <s-table @register="register" />
</template>

<script setup lang="ts">
import { getBusinessList } from "@/api/business";
import { TableColumnProps } from "@/components/Table";
const props = defineProps({
  dataId: {
    type: [String, Number],
    required: true
  }
});

const columns:TableColumnProps[] = [
  {
    title: "商机名称",
    dataIndex: "name",
    props: {
      type: "link"
    }
  },
  {
    title: "客户",
    dataIndex: "customer_name"
  },
  {
    title: "商机金额",
    dataIndex: "money"
  },
  {
    title: "商机组",
    dataIndex: "group_name"
  },
  {
    title: "商机阶段",
    dataIndex: "stage_name",
    customRender: ({ record, text }) => {
      return record.is_end ? record.end_text : text;
    }
  }
];

const [register] = useTable({
  columns,
  queryParams: { customer_id: props.dataId },
  listApi: getBusinessList,
  showTableSetting: false,
  rootStyle: { padding: 0 },
  bordered: true,
  showAction: false,
  size: "middle"
});
</script>
