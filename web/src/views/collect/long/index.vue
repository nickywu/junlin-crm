<template>
  <page-view>
    <s-table @register="register">
      <template #toolbar>
        <s-button type="primary" icon="plus-outlined" @click="openModal">添加</s-button>
      </template>
      <template #bodyCell="{ column, record }">
        <template v-if="column?.dataIndex === 'action'">
          <a @click="openModal(true, record.id)">修改</a>
          <a-divider type="vertical" />
          <s-confirm-button label="删除" title="确认要删除吗?" @confirm="handleDelete(record.id)" />
        </template>
      </template>
    </s-table>
    <long-collect-form @register="registerModal" @save-success="handleSuccess" />
  </page-view>
</template>

<script setup lang="ts">
import { getLongList, deleteLong } from "@/api/collect";
import { TableColumnProps } from "@/components/Table";
import longCollectForm from "./form.vue";

const searchForm = ref([
  {
    title: "关键词",
    dataIndex: "key",
    props: {
      placeholder: "请输入收款账号/备注搜索"
    }
  },
  {
    title: "关联合同",
    dataIndex: "contract_id",
    component: "TableSelect",
    props: {
      width: 450,
      api: "contract",
      multiple: false,
      labelField: "name",
      placeholder: "请选择关联合同",
      columns: [
        { title: "合同名称", dataIndex: "name" },
        { title: "合同编号", dataIndex: "order_no" }
      ]
    }
  },
  {
    title: "付款类型",
    dataIndex: "pay_type",
    component: "Select",
    props: {
      placeholder: "请选择付款类型",
      dictType: "pay_type"
    }
  },
  {
    title: "收款时间",
    dataIndex: "collect_time",
    component: "RangePicker"
  },
  {
    title: "添加时间",
    dataIndex: "create_time",
    component: "RangePicker"
  }
]);

const columns = ref<TableColumnProps[]>([
  { title: "合同名称", dataIndex: "contract_name" },
  { title: "客户名称", dataIndex: "customer_name" },
  { title: "收款账号", dataIndex: "account" },
  { title: "收款金额", dataIndex: "amount" },
  { title: "月服务费", dataIndex: "monthly_fee" },
  { title: "收款月数", dataIndex: "collect_months" },
  { title: "付款类型", dataIndex: "pay_type_text" },
  { title: "收款时间", dataIndex: "collect_time" },
  { title: "创建人", dataIndex: "create_realname" },
  { title: "备注", dataIndex: "remark" },
  { title: "添加时间", dataIndex: "create_time" },
  { title: "操作", dataIndex: "action", fixed: "right" }
]);

const [register, { search, handleDelete }] = useTable({
  columns,
  searchForm,
  listApi: getLongList,
  deleteApi: deleteLong,
  selection: true,
  scroll: { x: "max-content" }
});

const [registerModal, { openModal }] = useModal();

const handleSuccess = () => {
  search();
};
</script>
