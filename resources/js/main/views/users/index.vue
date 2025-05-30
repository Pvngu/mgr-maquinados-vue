<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.users`)" class="p-0" />
        </template>
        <template #actions>
            <a-space>
                <ExportButton :roles="roles" />
                <template
                    v-if="
                        permsArray.includes('staff_members_create') ||
                        permsArray.includes('admin')
                    "
                >
                    <a-button type="primary" @click="addItem">
                        <PlusOutlined />
                        {{ $t("staff_member.add") }}
                    </a-button>
                </template>
                <a-button
                    v-if="
                        table.selectedRowKeys.length > 0 &&
                        (permsArray.includes('staff_members_delete') ||
                            permsArray.includes('admin'))
                    "
                    type="primary"
                    @click="showSelectedDeleteConfirm"
                    danger
                >
                    <template #icon><DeleteOutlined /></template>
                    {{ $t("common.delete") }}
                </a-button>
            </a-space>
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.users`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-table-content>
        <AddEdit
            :addEditType="addEditType"
            :visible="addEditVisible"
            :url="addEditUrl"
            @addEditSuccess="addEditSuccess"
            @closed="onCloseAddEdit"
            :formData="formData"
            :data="viewData"
            :pageTitle="pageTitle"
            :successMessage="successMessage"
            :roles="roles"
            @roleAdded="roleAdded"
        />

        <a-row class="mt-20">
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :row-selection="{
                            selectedRowKeys: table.selectedRowKeys,
                            onChange: onRowSelectChange,
                            getCheckboxProps: (record) => ({
                                disabled:
                                    (permsArray.includes('users_delete') ||
                                        permsArray.includes('admin')) &&
                                    record.xid != user.xid
                                        ? false
                                        : true,
                                name: record.xid,
                            }),
                        }"
                        :columns="columns"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        @change="handleTableChange"
                        bordered
                        size="middle"
                    >
                        <template #title>
                            <a-row justify="end" align="center" class="table-header">
                                <a-col 
                                    :xs="21"
                                    :sm="16"
                                    :md="16"
                                    :lg="12"
                                    :xl="8"
                                >
                                    <a-input-group compact>
                                        <a-select
                                            style="width: 25%"
                                            v-model:value="table.searchColumn"
                                            :placeholder="$t('common.select_default_text', [''])"
                                        >
                                            <a-select-option
                                                v-for="filterableColumn in filterableColumns"
                                                :key="filterableColumn.key"
                                            >
                                                {{ filterableColumn.value }}
                                            </a-select-option>
                                        </a-select>
                                        <a-input-search
                                            style="width: 75%"
                                            v-model:value="table.searchString"
                                            show-search
                                            @change="onTableSearch"
                                            @search="onTableSearch"
                                            :loading="table.filterLoading"
                                        />
                                    </a-input-group>
                                </a-col>
                            </a-row>
                        </template>
                        <template #bodyCell="{ column, text, record }">
                            <template v-if="column.dataIndex === 'name'">
                                <user-info @click="showViewUserDrawer(record)" :user="record" />
                            </template>
                            <template v-if="column.dataIndex === 'status'">
                                <a-tag :color="statusColors[text]">
                                    {{ $t(`common.${text}`) }}
                                </a-tag>
                            </template>
                            <template v-if="column.dataIndex === 'created_at'">
                                {{ formatDateTime(record.created_at) }}
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('users_edit') ||
                                        permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="editItem(record)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="
                                        (permsArray.includes('users_delete') ||
                                            permsArray.includes('admin')) &&
                                        record.xid != user.xid
                                    "
                                    type="primary"
                                    @click="showDeleteConfirm(record.xid)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><DeleteOutlined /></template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </admin-page-table-content>
    <!-- Global Component -->
    <view-user-details
    :visible="isViewUserDrawerVisible"
    :user="viewUserDrawerData"
    @closed="hideViewUserDrawer"
    />
</template>
<script>
import { onMounted, ref } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined, CloudDownloadOutlined } from "@ant-design/icons-vue";
import fields from "./fields";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import AddEdit from "./AddEdit.vue";
import ExportButton from "./ExportButton.vue";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import ImportUsers from "../../../common/core/ui/Import.vue";
import UserInfo from "../../../common/components/user/UserInfo.vue";
import viewUserDrawer from "../../../common/composable/viewUserDrawer";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        CloudDownloadOutlined,
        AddEdit,
        AdminPageHeader,
        ImportUsers,
        UserInfo,
        ExportButton
    },
    setup() {
        const {
            url,
            addEditUrl,
            initData,
            columns,
            filterableColumns,
            hashableColumns,
        } = fields();
        const crudVariables = crud();
        const { permsArray, statusColors, formatDateTime, user } = common();
        const sampleFileUrl = window.config.staff_member_sample_file;
        const userDrawer = viewUserDrawer();
        const exportVisible = ref(false);
        const roles = ref([]);

        onMounted(() => {
            const rolesPromise = axiosAdmin.get('roles?limit=10000');

            Promise.all([rolesPromise]).then(([rolesResponse]) => {
                roles.value = rolesResponse.data;
            });

            setUrlData();
        });

        const setUrlData = () => {
            crudVariables.tableUrl.value = {
                url: url,
            };
            crudVariables.table.filterableColumns = filterableColumns;

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "user";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];
        };

        const roleAdded = (data) => {
            roles.value = data;
        }

        return {
            user,
            columns,
            filterableColumns,
            permsArray,
            statusColors,
            formatDateTime,
            roles,

            ...crudVariables,
            sampleFileUrl,
            setUrlData,
            ...userDrawer,

            exportVisible,
            roleAdded,
        };
    },
};
</script>
