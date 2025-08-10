<template>
  <page-view>
    <s-table @register="register">
      <template #search-ownerUser="{ data }">
        <s-select-user placeholder="请选择负责人" v-model="data.owner_user_id"></s-select-user>
      </template>
      <template #search-orderUser="{ data }">
        <s-select-user placeholder="请选择公司签约人" v-model="data.order_user_id"></s-select-user>
      </template>
      <template #toolbar>
        <span class="selection-action-button" v-if="selectRowKeysLength">
          已选 {{ selectRowKeysLength }} 条 <a-divider type="vertical" /> 批量
          <s-button icon="retweet-outlined" type="primary" @click="handleChangeUser">
            更换负责人
          </s-button>
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
    <contract-form @register="registerModal" @save-success="handleSuccess" />
    <contract-detail
      @register="registerDrawer"
      @refresh="search"
      :id="currentId"
      ref="detailRef"
      @edit="openModal(true, currentId)"
      @change-owner-user="onChangeOwnerUser"
    />
    <change-owner-user
      title="批量分配"
      @register="registerChangeUser"
      @success="handleSuccess"
      :api="changeOwnerUserApi"
    />
  </page-view>
</template>

<script setup lang="ts">
import { getContractList, destroy, changeOwnerUserApi } from "@/api/contract";
import { getCustomerList } from "@/api/customer";
import { getContactsList } from "@/api/contacts";
import { getBusinessList } from "@/api/business";
import contractForm from "./form.vue";
import contractDetail from "./detail.vue";
import { ChangeOwnerUser } from "../components";
import { TableColumnProps } from "@/components/Table";
const [registerChangeUser, { openModal: openChangeUser }] = useModal();
const [registerDrawer, { openDrawer, getVisible }] = useDrawer();
const { options } = useDict(["contract_status"]);
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
          placeholder: "请输入名称或者合同编号搜索"
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
    title: "关联商机",
    dataIndex: "business_id",
    component: "TableSelect",
    props: {
      width: 400,
      api: getBusinessList,
      multiple: false,
      labelField: "name",
      placeholder: "请选择关联商机",
      columns: [
        {
          title: "商机名称",
          dataIndex: "name"
        },
        {
          title: "商机金额",
          dataIndex: "money"
        },
        {
          title: "商机阶段",
          dataIndex: "stage_name"
        }
      ]
    }
  },
  {
    title: "合同状态",
    dataIndex: "status",
    component: "Select",
    props: {
      placeholder: "请选择合同状态",
      options: toRef(options, "contract_status")
    }
  },
  {
    title: "客户签约人",
    dataIndex: "contacts_id",
    component: "TableSelect",
    props: {
      width: 400,
      api: getContactsList,
      multiple: false,
      labelField: "name",
      placeholder: "请选择客户签约人",
      columns: [
        {
          title: "联系人名称",
          dataIndex: "name"
        },
        {
          title: "手机号码",
          dataIndex: "mobile"
        },
        {
          title: "职务",
          dataIndex: "job_text"
        }
      ]
    }
  },
  {
    title: "公司签约人",
    dataIndex: "order_user_id",
    slot: "orderUser"
  },
  {
    title: "负责人",
    dataIndex: "owner_user_id",
    slot: "ownerUser"
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
          label: "签约时间",
          value: "signing_time",
          placeholder: "请选择签约时间"
        },
        {
          label: "开始时间",
          value: "start_time",
          placeholder: "请选择合同开始时间"
        },
        {
          label: "到期时间",
          value: "end_time",
          placeholder: "请选择合同到期时间"
        }
      ]
    }
  }
]);

const columns: TableColumnProps[] = [
  {
    title: "合同编号",
    dataIndex: "order_no",
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
    title: "合同名称",
    dataIndex: "name"
  },
  {
    title: "客户名称",
    dataIndex: "customer_name"
  },
  {
    title: "商机名称",
    dataIndex: "business_name"
  },
  {
    title: "签约时间",
    dataIndex: "signing_time"
  },
  {
    title: "客户签约人",
    dataIndex: "contacts_name"
  },
  {
    title: "公司签约人",
    dataIndex: "realname"
  },
  {
    title: "合同金额",
    dataIndex: "money"
  },
  {
    title: "合同开始时间",
    dataIndex: "start_time"
  },
  {
    title: "合同到期时间",
    dataIndex: "end_time"
  },
  {
    title: "合同状态",
    dataIndex: "status_text"
  },
  {
    title: "负责人",
    dataIndex: "ownerUser",
    customRender: ({ record }) => record?.ownerUser?.realname
  },
  {
    title: "最后跟进时间",
    dataIndex: "last_time_text"
  },
  {
    title: "最后跟进记录",
    dataIndex: "last_record",
    maxLength: 20
  },
  {
    title: "添加时间",
    dataIndex: "create_time"
  },
  {
    title: "操作",
    dataIndex: "action"
  }
];

const [register, { search, handleDelete, getSelectRowKeys, selectRowKeysLength }] = useTable({
  columns,
  searchForm,
  listApi: getContractList,
  deleteApi: destroy,
  selection: true,
  scroll: { x: "max-content" }
});

const [registerModal, { openModal }] = useModal();

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
</script>
