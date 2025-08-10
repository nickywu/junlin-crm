<template>
  <page-view>
    <s-table @register="register">
      <template #search-ownerUser="{ data }">
        <s-select-user placeholder="请选择负责人" v-model="data.owner_user_id"></s-select-user>
      </template>
      <template #toolbar>
        <span class="selection-action-button" v-if="selectRowKeysLength">
          已选 {{ selectRowKeysLength }} 条 <a-divider type="vertical" /> 批量
          <s-button icon="check-square-outlined" type="primary" @click="handleChangeUser">
            分配
          </s-button>
          <s-button icon="retweet-outlined" @click="handleChangeStatus"> 更改成交状态 </s-button>
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
    <customer-form @register="registerModal" @save-success="handleSuccess" />
    <customer-detail
      @register="registerDrawer"
      @refresh="search"
      :id="currentId"
      ref="detailRef"
      @edit="openModal(true, currentId)"
      @change-owner-user="onChangeOwnerUser"
    ></customer-detail>
    <change-owner-user
      title="批量分配"
      @register="registerChangeUser"
      @success="handleSuccess"
      :api="allocation"
    />
    <change-status title="批量更改成交状态" @register="registerChangeStatus" @success="search" />
  </page-view>
</template>

<script setup lang="ts">
import { getCustomerList, destroy, allocation } from "@/api/customer";
import customerForm from "./form.vue";
import customerDetail from "./detail.vue";
import { ChangeOwnerUser } from "../components";
import { ChangeStatus } from "./components";
import { TableColumnProps } from "@/components/Table";
const { options } = useDict(["industry", "level", "source", "cust_deal_status"]);
const [registerChangeUser, { openModal: openChangeUser }] = useModal();
const [registerChangeStatus, { openModal: openChangeStatus }] = useModal();
const currentId = ref("");
const title = ref("");
const detailRef = ref();
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
          placeholder: "请输入名称或者手机号码搜索"
        },
        {
          label: "地址",
          value: "addr",
          placeholder: "请输入客户地址搜索"
        },
        {
          label: "最后跟进记录",
          value: "last_record",
          placeholder: "请输入最后跟进记录搜索"
        }
      ]
    }
  },
  {
    title: "客户来源",
    dataIndex: "source",
    component: "Select",
    props: {
      placeholder: "请选择客户来源",
      options: toRef(options, "source")
    }
  },
  {
    title: "客户级别",
    dataIndex: "level",
    component: "Select",
    props: {
      placeholder: "请选择客户级别",
      options: toRef(options, "level")
    }
  },
  {
    title: "客户行业",
    dataIndex: "industry",
    component: "Select",
    props: {
      placeholder: "请选择客户行业",
      options: toRef(options, "industry")
    }
  },
  {
    title: "负责人",
    dataIndex: "owner_user_id",
    slot: "ownerUser"
  },
  {
    title: "成交状态",
    dataIndex: "deal_status",
    component: "Select",
    props: {
      placeholder: "请选择转换状态",
      options: toRef(options, "cust_deal_status")
    }
  },
  {
    dataIndex: "create_time",
    component: "CompactSelect",
    props: {
      defaultField: "create_time",
      type: "date",
      options: [
        {
          label: "添加时间",
          value: "create_time",
          placeholder: "请选择添加时间"
        },
        {
          label: "成交时间",
          value: "deal_time",
          placeholder: "请选择成交时间"
        },
        {
          label: "分配时间",
          value: "receive_time",
          placeholder: "请选择分配时间"
        }
      ]
    }
  }
]);

const columns: TableColumnProps[] = [
  {
    title: "客户名称",
    dataIndex: "name",
    fixed: "left",
    props: {
      type: "link",
      onClick: row => {
        currentId.value = row.id;
        openDrawer(true, row.id);
      }
    }
  },
  {
    title: "成交状态",
    dataIndex: "deal_status_tag",
    props: {
      type: "dict"
    }
  },
  {
    title: "客户级别",
    dataIndex: "level_text"
  },
  {
    title: "客户来源",
    dataIndex: "source_text"
  },
  {
    title: "手机号码",
    dataIndex: "mobile"
  },
  {
    title: "客户行业",
    dataIndex: "industry_text"
  },
  {
    title: "地址",
    dataIndex: "addr",
    maxLength: 20
  },
  {
    title: "负责人",
    dataIndex: "realname"
  },
  {
    title: "电话",
    dataIndex: "telephone"
  },
  {
    title: "邮箱",
    dataIndex: "email"
  },
  {
    title: "最后跟进记录",
    dataIndex: "last_record",
    maxLength: 20
  },
  {
    title: "最后跟进时间",
    dataIndex: "last_time_text"
  },
  {
    title: "添加时间",
    dataIndex: "create_time"
  },
  {
    title: "添加人",
    dataIndex: "creator",
    customRender: ({ record }) => record?.creator?.realname
  },
  {
    title: "操作",
    dataIndex: "action"
  }
];

const [registerDrawer, { openDrawer, getVisible }] = useDrawer();

const [register, { search, handleDelete, selectRowKeysLength, getSelectRowKeys }] = useTable({
  columns,
  searchForm,
  listApi: getCustomerList,
  deleteApi: destroy,
  selection: true,
  scroll: { x: "max-content" }
});

const handleSuccess = () => {
  if (getVisible.value) {
    detailRef.value.refresh();
  }
  search();
};
const onChangeOwnerUser = () => {
  title.value = "分配";
  openChangeUser(true, currentId.value);
};

const handleChangeUser = () => {
  title.value = "批量分配";
  const selectKeys = getSelectRowKeys();
  openChangeUser(true, selectKeys);
};

const handleChangeStatus = () => {
  const selectKeys = getSelectRowKeys();
  openChangeStatus(true, selectKeys);
};

const [registerModal, { openModal }] = useModal();
</script>
