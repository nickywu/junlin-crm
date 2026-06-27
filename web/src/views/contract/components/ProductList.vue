<template>
  <s-table @register="register" />
</template>

<script setup lang="tsx">
import { getProduct } from "@/api/contract";
const props = defineProps({
  dataId: {
    type: [String, Number],
    required: true
  }
});

const columns = [
  {
    title: "子业务名称",
    dataIndex: "product_name",
    width: 160
  },
  {
    title: "服务类型",
    dataIndex: "service_type",
    width: 100,
    customRender: ({ text }) => serviceTypeMap[text] || text || "-"
  },
  {
    title: "核心业务",
    dataIndex: "core_text",
    width: 90
  },
  {
    title: "数量",
    dataIndex: "number",
    width: 80
  },
  {
    title: "销售合计",
    dataIndex: "total_price",
    width: 100
  },
  {
    title: "分摊金额",
    dataIndex: "share_amount",
    width: 100
  },
  {
    title: "实际成本",
    dataIndex: "actual_cost",
    width: 100
  },
  {
    title: "毛利",
    dataIndex: "gross_profit",
    width: 100
  },
  {
    title: "负责人",
    dataIndex: "owner_user_name",
    width: 100
  },
  {
    title: "工单主管",
    dataIndex: "work_manager_name",
    width: 100
  },
  {
    title: "周期",
    dataIndex: "period_total",
    width: 90,
    customRender: ({ record }) => record.period_total ? `${record.period_total}${periodUnitMap[record.period_unit] || ""}` : "-"
  },
  {
    title: "完结状态",
    dataIndex: "finish_status_text",
    width: 100
  },
  {
    title: "当前节点",
    dataIndex: "flow_node",
    width: 120
  }
];

const serviceTypeMap: Record<string, string> = {
  single: "单次",
  long_term: "长期",
  periodic: "周期",
  stage: "阶段"
};

const periodUnitMap: Record<string, string> = {
  month: "个月",
  year: "年",
  stage: "阶段"
};

const [register] = useTable({
  columns,
  queryParams: { id: props.dataId },
  listApi: getProduct,
  scroll: { x: 1400 },
  showTableSetting: false,
  rootStyle: { padding: 0 },
  bordered: true,
  showAction: false,
  size: "middle"
});
</script>
