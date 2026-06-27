<template>
  <s-table @register="register" />
</template>

<script setup lang="tsx">
import { getServicePeriod, updateServicePeriod } from "@/api/contract";

const props = defineProps({
  dataId: {
    type: [String, Number],
    required: true
  }
});

const { responseNotice } = useMessage();
const statusOptions = [
  { label: "未开始", value: 0 },
  { label: "办理中", value: 1 },
  { label: "已完结", value: 2 },
  { label: "异常", value: 3 }
];
const periodUnitMap: Record<string, string> = {
  month: "月",
  year: "年",
  stage: "阶段"
};

/**
 * 更新服务周期状态
 * @param record 当前周期行
 * @param status 新状态
 */
const changeStatus = (record: Recordable, status: number) => {
  return updateServicePeriod(record.id, { status }).then(res => {
    responseNotice(res);
    search();
  });
};

const columns = [
  {
    title: "子业务",
    dataIndex: "product_name",
    width: 160
  },
  {
    title: "周期",
    dataIndex: "period_name",
    width: 120
  },
  {
    title: "周期单位",
    dataIndex: "period_unit",
    width: 90,
    customRender: ({ text }) => periodUnitMap[text] || text || "-"
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
    title: "负责人",
    dataIndex: "owner_user_name",
    width: 100
  },
  {
    title: "状态",
    dataIndex: "status",
    width: 120,
    customRender: ({ record }) => (
      <a-select
        value={record.status}
        options={statusOptions}
        style={{ width: "110px" }}
        onChange={(value: number) => changeStatus(record, value)}
      />
    )
  },
  {
    title: "完结时间",
    dataIndex: "finish_time",
    width: 120
  },
  {
    title: "备注",
    dataIndex: "remark",
    width: 160
  }
];

const [register, { search }] = useTable({
  columns,
  queryParams: { contract_id: props.dataId },
  listApi: getServicePeriod,
  scroll: { x: 1100 },
  showTableSetting: false,
  rootStyle: { padding: 0 },
  bordered: true,
  showAction: false,
  size: "middle"
});
</script>
