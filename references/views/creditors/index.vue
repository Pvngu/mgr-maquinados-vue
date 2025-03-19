<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.creditors`)" class="p-0" />
        </template>
        <template #actions>
            <a-space>
                <template
                    v-if="
                        permsArray.includes('creditors_create') ||
                        permsArray.includes('admin')
                    "
                >
                    <a-button type="primary" @click="addItem">
                        <PlusOutlined />
                        {{ $t("creditor.add") }}
                    </a-button>
                </template>
                <a-button
                    v-if="
                        table.selectedRowKeys.length > 0 &&
                        (permsArray.includes('creditors_delete') ||
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
                    {{ $t(`menu.creditors`) }}
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
            :states="states"
        />

        <a-row class="mt-20">
            <a-col :span="24">
                <a-tabs
                    v-model:activeKey="filters.status" 
                    @change="setUrlData" 
                    centered
                    type="card"
                    class="table-tab-filters"
                >
                    <a-tab-pane key="">
                        <template #tab>
                            <span>
                                <FileOutlined />
                                {{ $t("common.all") }}
                            </span>
                        </template>
                    </a-tab-pane>
                    <a-tab-pane key="individual">
                        <template #tab>
                            <span>
                                <UserOutlined />
                                {{ $t("creditor.individual") }}
                            </span>
                        </template>
                    </a-tab-pane>
                    <a-tab-pane key="business">
                        <template #tab>
                            <span>
                                <TeamOutlined />
                                {{ $t("creditor.business") }}
                            </span>
                        </template>
                    </a-tab-pane>
                </a-tabs>
            </a-col>
        </a-row>
        <a-row class="mt-20">
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :columns="columns"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        @change="handleTableChange"
                        bordered
                        size="middle"
                    >
                        <!-- Remove this template if there's no filters and search in the prompt -->
                        <template #title>
                            <a-row justify="end" align="center" class="table-header">
                                <!-- Remove if there's no search in the prompt -->
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
                                            :placeholder="$t('common.search')"
                                            show-search
                                            @search="onTableSearch"
                                            @change="onTableSearch"
                                            :loading="table.loading"
                                        />
                                    </a-input-group>
                                </a-col>
                                <!-- Remove if there's no filters in the prompt along with the import and component exportation -->
                                <a-col class="ml-10">
                                    <Filters @onReset="resetFilters">
                                        <a-col :span="24">
                                            <a-form-item :label="$t('lead.state')">
                                                <a-select
                                                    v-model:value="filters.state_id"
                                                    :placeholder="$t('common.select_default_text', [$t('lead.state')])"
                                                    :allowClear="true"
                                                    style="width: 100%"
                                                    optionFilterProp="title"
                                                    show-search
                                                    @change="setUrlData"
                                                >
                                                    <a-select-option
                                                        v-for="state in states"
                                                        :key="state.xid"
                                                        :title="state.name"
                                                        :value="state.xid"
                                                    >
                                                        {{ state.name }}
                                                    </a-select-option>
                                                </a-select>
                                            </a-form-item>
                                        </a-col>
                                    </Filters>
                                </a-col>
                            </a-row>
                        </template>
                        <template #bodyCell="{ column, record }">
                            <!-- if a column is set with an arrow (->) in the prompt it means you need to create the following template and place the left value to the dataIndex and the right value to record.{here} -->
                            <template v-if="column.dataIndex === 'state_id'">
                                {{ record.state.code }}
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('creditors_edit') ||
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
                                        (permsArray.includes('creditors_delete') ||
                                            permsArray.includes('admin')) &&
                                        (!record.children || record.children.length == 0)
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
</template>

<script>
import { ref, onMounted } from "vue";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import { EyeOutlined, PlayCircleOutlined, DownOutlined, PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";
import Filters from "../../../common/components/common/select/Filters.vue";

export default {
    components: {
        EyeOutlined,
        PlayCircleOutlined,
        DownOutlined,
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AdminPageHeader,
        AddEdit,
        Filters,
    },
    setup() {
        const { url, addEditUrl, initData, columns, filterableColumns, states, getPrefetchData, hashableColumns } = fields();
        const { permsArray } = common();
        const crudVariables = crud();
        const filters = ref({
            status: "",
            state_id: undefined,
        });

        onMounted(() => {
            getPrefetchData().then(() => {
                crudVariables.table.filterableColumns = filterableColumns;

                crudVariables.crudUrl.value = addEditUrl;
                crudVariables.langKey.value = "creditor";
                crudVariables.initData.value = { ...initData };
                crudVariables.formData.value = { ...initData };

                setUrlData();
            });
        });

        const setUrlData = () => {
            console.log('setUrlData');
            console.log('filters.value', filters.value);
            crudVariables.tableUrl.value = {
                url,
                filters,
            };

            crudVariables.hashableColumns.value = [...hashableColumns];

            crudVariables.fetch({
                page: 1,
            });
        };

        const resetFilters = () => {
            filters.value = {
                state_id: undefined,
            };
            setUrlData();
        };

        return {
            ...crudVariables,
            permsArray,
            columns,
            filterableColumns,
            states,
            filters,
            setUrlData,
            resetFilters,
        };
    },
}
</script>