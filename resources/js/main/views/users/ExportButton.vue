<template>
    <a-button
        v-if="
            (permsArray.includes('reports_view') ||
                permsArray.includes('admin'))
        "
        @click = "visible = true"
    >
        {{ $t('common.export_table', [$t('menu.users')]) }}
    </a-button>
    <a-modal
        v-model:open = "visible"
        :title="$t('user.export_users')"
        centered
        @close="handleClose"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :span="24">
                    <a-form-item
                        :label="$t('common.export_as')"
                        name="export_type"
                        :help="
                            rules.export_type
                                ? rules.export_type.message
                                : null
                        "
                        :validateStatus="
                            rules.export_type ? 'error' : null
                        "
                        class="required"
                    >
                        <a-select
                            v-model:value="exportData.export_type"
                            :placeholder="$t('common.select_default_text', [''])"
                        >
                            <a-select-option value="xlsx">
                                XLSX
                            </a-select-option>
                            <a-select-option value="csv">
                                CSV
                            </a-select-option>
                            <a-select-option value="pdf">
                                PDF
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="24">
                    <a-form-item
                        :label="$t('common.columns')"
                        name="columns"
                        :help="rules.columns ? rules.columns.message : null"
                        :validateStatus="rules.columns ? 'error' : null"
                        class="required"
                    >
                        <a-select
                            v-model:value="exportData.columns"
                            mode="multiple"
                            style="width: 100%"
                            :placeholder="$t('common.select_default_text', [$t('common.columns')])"
                            :options="columnsOptions"
                        />
                    </a-form-item>
                </a-col>
                <a-col :span="24">
                    <a-form-item
                        :label="$t('user.roles') + ' ' + $t('common.include_all_as_default')"
                        name="roles"
                        :help="rules.roles ? rules.roles.message : null"
                        :validateStatus="rules.roles ? 'error' : null"
                    >
                        <a-select
                            v-model:value="exportData.roleXIds"
                            mode="multiple"
                            style="width: 100%"
                            :placeholder="$t('common.select_default_text', [$t('user.roles')])"
                        >
                            <a-select-option
                                v-for="role in roles"
                                :key="role.xid"
                                :value="role.xid"
                            >
                                {{ role.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="24">
                    <a-form-item
                        :label="$t('common.date_range')"
                        name="dates"
                        :help="rules.dates ? rules.dates.message : null"
                        :validateStatus="rules.dates ? 'error' : null"
                    >
                        <DateRangePicker
                            @dateTimeChanged="
                                (changedDateTime) => {
                                    dates = changedDateTime;
                                }
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button key="back" @click="handleClose">
                {{ $t("common.close") }}
            </a-button>
            <a-button 
                @click="exportUsers(exportData)"
                type="primary"
                :disabled="!exportData.columns.length || !exportData.export_type"
            >
                {{ $t("common.export") }}
            </a-button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent, ref } from "vue";
import { CloudDownloadOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../common/composable/apiAdmin";
import common from "../../../common/composable/common";
import DateRangePicker from "../../../common/components/common/calendar/DateRangePicker.vue";

export default defineComponent({
    props: [
        "roles",
    ],
    components: {
        CloudDownloadOutlined,
        DateRangePicker
    },
    setup() {
        const { rules } = apiAdmin();
        const { permsArray, exportItems } = common();
        const exportData = ref({
            export_type: "",
            roleXIds: [],
            columns: [],
        });
        const { t } = useI18n();
        const visible = ref(false);
        const dates = ref([]);

        const exportUsers = () => {
            axiosAdmin.get('/users/export', {
                responseType: 'arraybuffer',
                params: {
                    roleXIds: exportData.value.roleXIds,
                    columns: exportData.value.columns,
                    dates: dates.value,
                    format: exportData.value.export_type,
                    selectedRowKeys: []
                }
            }).then((res) => {
                exportItems(res, exportData.value.export_type, 'staff_members', dates.value);
                visible.value = false;
                resetExportData();
            });
        };

        const handleClose = () => {
            visible.value = false;
            resetExportData();
        }

        const resetExportData = () => {
            exportData.value = {
                export_type: "",
                roleXIds: [],
                columns: [],
            };
        }

        const columnsOptions = ref([
            {
                label: t("user.name"),
                value: "name",
            },
            {
                label: t("user.role"),
                value: "role",
            },
            {
                label: t("user.phone"),
                value: "phone",
            },
            {
                label: t("user.email"),
                value: "email",
            },
            {
                label: t("user.address"),
                value: "address",
            },
            {
                label: t("user.status"),
                value: "status",
            },
        ]);

        return {
            exportData,
            columnsOptions,
            rules,
            exportUsers,
            permsArray,
            exportItems,
            visible,
            handleClose,
            dates,
        };
    }
});
</script>