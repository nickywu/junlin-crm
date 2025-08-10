<template>
  <page-view>
    <s-table @register="register">
      <template #search-ownerUser="{ data }">
        <s-select-user placeholder="请选择负责人" v-model="data.owner_user_id"></s-select-user>
      </template>
      <template #search-stage="{ data }">
        <a-cascader
          :field-names="{ label: 'name', value: 'id', children: 'stage' }"
          v-model:value="data.stage_id"
          :options="businessGroup"
          expand-trigger="hover"
          placeholder="请选择商机状态组"
        />
      </template>
      <template #toolbar>
        <span class="selection-action-button" v-if="selectRowKeysLength">
          已选 {{ selectRowKeysLength }} 条 <a-divider type="vertical" /> 批量
          <s-button icon="retweet-outlined" type="primary" @click="onBatchChangeUser">
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
    <business-form @register="registerModal" @save-success="handleSuccess" />
    <business-detail
      ref="detailRef"
      @register="registerDrawer"
      @refresh="search"
      :id="currentId"
      @edit="openModal(true, currentId)"
      @change-owner-user="onChangeOwnerUser"
    />
    <change-owner-user
      :title="title"
      @register="registerChangeUser"
      @success="handleSuccess"
      :api="changeOwnerUserApi"
    />
  </page-view>
</template>

<script setup lang="ts">
import { getBusinessList, destroy, changeOwnerUserApi, getGroupList } from "@/api/business";
import { getCustomerList } from "@/api/customer";
import businessForm from "./form.vue";
import businessDetail from "./detail.vue";
import { ChangeOwnerUser } from "../components";
import { TableColumnProps } from "@/components/Table";
const [registerDrawer, { openDrawer, getVisible }] = useDrawer();
const [registerModal, { openModal }] = useModal();
const [registerChangeUser, { openModal: openChangeUser }] = useModal();
const currentId = ref("");
const detailRef = ref();
const businessGroup = ref<Array<Recordable>>([]);
const title = ref("");
const searchForm = ref([
  {
    title: "商机名称",
    dataIndex: "name",
    component: "Input",
    props: {
      placeholder: "请输入商机名称搜索"
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
    title: "负责人",
    dataIndex: "owner_user_id",
    slot: "ownerUser"
  },
  {
    title: "商机状态组",
    dataIndex: "stage_id",
    slot: "stage"
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
        }
      ]
    }
  }
]);

const columns: TableColumnProps[] = [
  {
    title: "商机名称",
    dataIndex: "name",
    props: {
      type: "link",
      onClick: (row: Recordable) => {
        currentId.value = row.id;
        openDrawer(true, row.id);
      }
    }
  },
  {
    title: "客户",
    dataIndex: "customer_name"
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
  },
  {
    title: "商机金额",
    dataIndex: "money"
  },
  {
    title: "预计成交日期",
    dataIndex: "deal_time"
  },
  {
    title: "备注",
    dataIndex: "remark",
    maxLength: 40
  },
  {
    title: "负责人",
    dataIndex: "realname"
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
  }
];

const [register, { search, handleDelete, selectRowKeysLength, getSelectRowKeys }] = useTable({
  columns,
  searchForm,
  listApi: getBusinessList,
  deleteApi: destroy,
  selection: true
});

const getGroupData = () => {
  getGroupList().then(res => {
    businessGroup.value = res.data;
    const newStages = [
      { id: 1, name: "赢单" },
      { id: 2, name: "输单" },
      { id: 3, name: "无效" }
    ];
    if (res.data && res.data.length > 0) {
      res.data.forEach((item:Recordable) => {
        item.stage.unshift(...newStages);
      });
    }
  });
};

onMounted(() => {
  getGroupData();
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

const onBatchChangeUser = () => {
  title.value = "批量更换负责人";
  const selectKeys = getSelectRowKeys();
  openChangeUser(true, selectKeys);
};
</script>
