<template>
  <s-table @register="register" />
</template>

<script setup lang="tsx">
import { getWorkOrderNode, updateWorkOrderNode } from "@/api/contract";

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
  { label: "已完成", value: 2 },
  { label: "异常", value: 3 },
  { label: "跳过", value: 4 }
];

/**
 * 更新工单节点状态
 * @param record 当前节点行
 * @param status 新状态
 */
const changeStatus = (record: Recordable, status: number) => {
  return updateWorkOrderNode(record.id, { status }).then(res => {
    responseNotice(res);
    search();
  });
};

const columns = [
  {
    title: "工单编号",
    dataIndex: "work_order_no",
    width: 160
  },
  {
    title: "工单标题",
    dataIndex: "work_order_title",
    width: 180
  },
  {
    title: "节点名称",
    dataIndex: "name",
    width: 150
  },
  {
    title: "排序",
    dataIndex: "sort",
    width: 80
  },
  {
    title: "负责人",
    dataIndex: "owner_user_id",
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
    title: "完成时间",
    dataIndex: "finish_time",
    width: 120
  },
  {
    title: "结果",
    dataIndex: "result",
    width: 160
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
  listApi: getWorkOrderNode,
  scroll: { x: 1200 },
  showTableSetting: false,
  rootStyle: { padding: 0 },
  bordered: true,
  showAction: false,
  size: "middle"
});
</script>
