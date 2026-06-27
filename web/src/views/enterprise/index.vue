<template>
  <page-view>
    <s-table @register="register">
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
    <enterprise-form @register="registerModal" @save-success="handleSuccess" />
    <enterprise-detail
      :id="currentId"
      ref="detailRef"
      @edit="openModal(true, currentId)"
      @register="registerDrawer"
      @refresh="search"
    />
  </page-view>
</template>

<script setup lang="ts">
import { getEnterpriseList, destroy } from "@/api/enterprise";
import { getCustomerList } from "@/api/customer";
import enterpriseForm from "./form.vue";
import enterpriseDetail from "./detail.vue";

const [registerDrawer, { openDrawer }] = useDrawer();
const currentId = ref("");
const detailRef = ref();

/** 搜索表单配置 */
const searchForm = ref([
  {
    dataIndex: "key",
    component: "CompactSelect",
    props: {
      defaultField: "key",
      type: "input",
      options: [
        {
          label: "关键词",
          value: "key",
          placeholder: "请输入企业名称、联系人搜索"
        }
      ]
    }
  },
  {
    title: "关联客户",
    dataIndex: "customer_id",
    component: "TableSelect",
    props: {
      width: 400,
      api: getCustomerList,
      multiple: false,
      labelField: "name",
      placeholder: "请选择关联客户",
      columns: [
        {
          title: "客户名称",
          dataIndex: "name"
        },
        {
          title: "手机号码",
          dataIndex: "mobile"
        },
        {
          title: "客户等级",
          dataIndex: "level_text"
        }
      ]
    }
  },
  {
    title: "纳税性质",
    dataIndex: "tax_nature",
    component: "Select",
    props: {
      placeholder: "请选择纳税性质",
      options: [
        { label: "小规模纳税人", value: 0 },
        { label: "一般纳税人", value: 1 }
      ]
    }
  },
  {
    title: "负责人",
    dataIndex: "owner_user_id",
    slot: "ownerUser"
  },
  {
    title: "创建时间",
    dataIndex: "create_time",
    component: "RangePicker"
  }
]);

/** 表格列配置 */
const columns = [
  {
    title: "企业名称",
    dataIndex: "name",
    fixed: "left",
    props: {
      type: "link",
      onClick: (row: Recordable) => {
        currentId.value = row.id;
        openDrawer(true, row.id);
      }
    }
  },
  {
    title: "关联客户",
    dataIndex: "customer_name"
  },
  {
    title: "法定代表人",
    dataIndex: "legal_person"
  },
  {
    title: "纳税性质",
    dataIndex: "tax_nature_text"
  },
  {
    title: "联系人",
    dataIndex: "contact_person"
  },
  {
    title: "联系电话",
    dataIndex: "contact_phone"
  },
  {
    title: "负责人",
    dataIndex: "realname"
  },
  {
    title: "注册资本",
    dataIndex: "registered_capital"
  },
  {
    title: "创建时间",
    dataIndex: "create_time"
  },
  {
    title: "操作",
    dataIndex: "action"
  }
];

const [register, { search, handleDelete, selectRowKeysLength }] = useTable({
  columns,
  searchForm,
  listApi: getEnterpriseList,
  deleteApi: destroy,
  selection: true,
  scroll: { x: "max-content" }
});

/** 新增/编辑成功后刷新表格 */
const handleSuccess = () => {
  search();
};

const [registerModal, { openModal }] = useModal();
</script>
