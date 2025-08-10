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
    <clues-form @register="registerModal" @save-success="handleSuccess" />
    <clues-detail
      @register="registerDrawer"
      @refresh="search"
      :id="currentId"
      ref="detailRef"
      @edit="openModal(true, currentId)"
      @change-owner-user="onChangeOwnerUser"
    />
    <change-owner-user
      :title="title"
      @register="registerChangeUser"
      @success="handleSuccess"
      :api="allocation"
    />
  </page-view>
</template>

<script setup lang="ts">
import { getCluesList, destroy, allocation } from "@/api/clues";
import cluesForm from "./form.vue";
import cluesDetail from "./detail.vue";
import { ChangeOwnerUser } from "../components";
const { options } = useDict(["industry", "level", "source"]);
const [registerChangeUser, { openModal: openChangeUser }] = useModal();
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
          placeholder: "请输入线索地址搜索"
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
    title: "线索来源",
    dataIndex: "source",
    component: "Select",
    props: {
      placeholder: "请选择线索来源",
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
    title: "线索行业",
    dataIndex: "industry",
    component: "Select",
    props: {
      placeholder: "请选择线索行业",
      options: toRef(options, "industry")
    }
  },
  {
    title: "负责人",
    dataIndex: "owner_user_id",
    slot: "ownerUser"
  },
  {
    title: "转换状态",
    dataIndex: "is_transform",
    component: "Select",
    props: {
      placeholder: "请选择转换状态",
      options: [
        {
          label: "未转换",
          value: 0
        },
        {
          label: "已转换",
          value: 1
        }
      ]
    }
  }
]);
const [registerDrawer, { openDrawer, getVisible }] = useDrawer();
const columns = [
  {
    title: "线索名称",
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
    title: "线索来源",
    dataIndex: "source_text"
  },
  {
    title: "手机号码",
    dataIndex: "mobile"
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
    title: "地址",
    dataIndex: "addr",
    maxLength: 20
  },
  {
    title: "线索行业",
    dataIndex: "industry_text"
  },
  {
    title: "线索级别",
    dataIndex: "level_text"
  },
  {
    title: "负责人",
    dataIndex: "realname"
  },
  {
    title: "备注",
    dataIndex: "remark",
    maxLength: 30
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
  listApi: getCluesList,
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

const [registerModal, { openModal }] = useModal();
</script>
