<template>
  <page-view>
    <s-table @register="register">
      <template #search-ownerUser="{ data }">
        <s-select-user placeholder="请选择负责人" v-model="data.owner_user_id"></s-select-user>
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
    <contacts-form @register="registerModal" @save-success="handleSuccess" />
    <contract-detail
      :id="currentId"
      ref="detailRef"
      @edit="openModal(true, currentId)"
      @change-owner-user="onChangeOwnerUser"
      @register="registerDrawer"
      @refresh="search"
    ></contract-detail>
    <change-owner-user
      :title="title"
      @register="registerChangeUser"
      @success="handleSuccess"
      :api="changeOwnerUserApi"
    />
  </page-view>
</template>

<script setup lang="ts">
import { getContactsList, destroy, changeOwnerUserApi } from "@/api/contacts";
import { getCustomerList } from "@/api/customer";
import contactsForm from "./form.vue";
import contractDetail from "./detail.vue";
import { ChangeOwnerUser } from "../components";
const [registerDrawer, { openDrawer, getVisible }] = useDrawer();
const [registerChangeUser, { openModal: openChangeUser }] = useModal();
const currentId = ref("");
const detailRef = ref();
const title = ref("");
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
          placeholder: "请输入联系人地址搜索"
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
    title: "是否决策人",
    dataIndex: "is_decision",
    component: "Select",
    props: {
      options: [
        {
          label: "是",
          value: 1
        },
        {
          label: "否",
          value: 0
        }
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

const columns = [
  {
    title: "名称",
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
    title: "手机号码",
    dataIndex: "mobile"
  },
  {
    title: "电话号码",
    dataIndex: "telephone"
  },
  {
    title: "邮箱",
    dataIndex: "email"
  },
  {
    title: "职务",
    dataIndex: "job_text"
  },
  {
    title: "性别",
    dataIndex: "gender_text"
  },
  {
    title: "负责人",
    dataIndex: "realname"
  },
  {
    title: "是否决策人",
    dataIndex: "decision_text"
  },
  {
    title: "地址",
    dataIndex: "addr",
    maxLength: 30
  },
  {
    title: "备注",
    dataIndex: "note",
    maxLength: 40
  },
  {
    title: "创建时间",
    dataIndex: "create_time"
  },
  {
    title: "最后跟进时间",
    dataIndex: "last_time_text"
  },
  {
    title: "最后跟进记录",
    dataIndex: "last_record"
  },
  {
    title: "操作",
    dataIndex: "action"
  }
];

const [register, { search, handleDelete, selectRowKeysLength, getSelectRowKeys }] = useTable({
  columns,
  searchForm,
  listApi: getContactsList,
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
  title.value = "更换负责人";
  openChangeUser(true, currentId.value);
};

const handleChangeUser = () => {
  title.value = "批量更换负责人";
  const selectKeys = getSelectRowKeys();
  openChangeUser(true, selectKeys);
};

const [registerModal, { openModal }] = useModal();
</script>
