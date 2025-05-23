<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.orders`)" class="p-0" />
        </template>
        <template #actions>
            <a-space>
                <template
                    v-if="
                        permsArray.includes('orders_create') ||
                        permsArray.includes('admin')
                    "
                >
                    <a-button type="primary" @click="addItem">
                        <PlusOutlined />
                        {{ $t("orders.add") }}
                    </a-button>
                </template>
                <a-button
                    v-if="
                        table.selectedRowKeys.length > 0 &&
                        (permsArray.includes('orders_delete') ||
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
                    {{ $t(`menu.orders`) }}
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
            :customers="customers"
            :users="users"
            :products="products"
        />
        <a-row class="mt-5">
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
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'client_id'">
                                {{ record.client ? record.client.name : '' }}
                            </template>
                            <template v-if="column.dataIndex === 'user_id'">
                                {{ record.user ? record.user.name : '' }}
                            </template>
                            <template v-if="column.dataIndex === 'total_amount'">
                                {{ parseFloat(record.total_amount).toFixed(2) }}
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('orders_edit') ||
                                        permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="editItem(record)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="permsArray.includes('orders_view') || permsArray.includes('admin')"
                                    type="primary"
                                    @click="exportPdfOrder(record.xid)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><FilePdfOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="
                                        permsArray.includes('orders_delete') ||
                                        permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="showDeleteConfirm(record.xid)"
                                    style="margin-left: 4px"
                                    danger
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
import { onMounted } from "vue";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import {
    PlusOutlined,
    DeleteOutlined,
    EditOutlined,
    FilePdfOutlined,
} from "@ant-design/icons-vue";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";

export default {
    components: {
        PlusOutlined,
        DeleteOutlined,
        EditOutlined,
        FilePdfOutlined,
        AdminPageHeader,
        AddEdit,
    },
    setup() {
        const {
            url,
            addEditUrl,
            initData,
            columns,
            filterableColumns,
            hashableColumns,
            getPrefetchData,
            customers,
            users,
            products
        } = fields();
        const { permsArray } = common();
        const crudVariables = crud();

        onMounted(() => {
            crudVariables.table.filterableColumns = filterableColumns;

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "orders";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };

            getPrefetchData().then(() => {
                setUrlData();
            });
        });

        const setUrlData = () => {
            crudVariables.tableUrl.value = {
                url,
            };

            crudVariables.hashableColumns.value = [...hashableColumns];

            crudVariables.fetch({
                page: 1,
            });
        };

        // Override addItem to initialize order_items array
        const addItem = () => {
            crudVariables.addEditType.value = "add";
            crudVariables.formData.value = { ...crudVariables.initData.value, order_items: [] };
            crudVariables.addEditVisible.value = true;
            crudVariables.pageTitle.value = crudVariables.langKey.value
                ? crudVariables.$t(`${crudVariables.langKey.value}.add`)
                : crudVariables.$t("common.add");
            crudVariables.successMessage.value = crudVariables.langKey.value
                ? crudVariables.$t(`${crudVariables.langKey.value}.created`)
                : crudVariables.$t("common.created");
        };

        // PDF export function
        const exportPdfOrder = async (orderXid) => {
            try {
            const response = await axiosAdmin.get(`orders/${orderXid}/export-pdf`, {
                responseType: 'blob'
            });
            const url = window.URL.createObjectURL(new Blob([response], { type: 'application/pdf' }));
            window.open(url, '_blank');
            } catch (error) {
            // handle error if needed
            }
        };

        return {
            ...crudVariables,
            permsArray,
            columns,
            filterableColumns,
            setUrlData,
            customers,
            users,
            products,
            addItem,
            addEditUrl,
            exportPdfOrder
        };
    },
};
</script>
