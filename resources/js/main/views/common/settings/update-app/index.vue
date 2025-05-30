<template>
    <a-card class="page-content-container mt-20 mb-20">
        <a-row class="mt-20">
            <a-col :span="24">
                <div class="table-responsive mt-10">
                    <a-table
                        :columns="tableColumns"
                        :row-key="(record) => record.id"
                        :data-source="appDetails"
                        :pagination="false"
                        :showHeader="false"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex == 'name'">
                                <a-typography-title
                                    :level="5"
                                    v-if="record.name == 'app_details'"
                                    strong
                                >
                                    {{ $t(`update_app.app_details`) }}
                                </a-typography-title>
                                <span v-else>
                                    {{ $t(`update_app.${record.name}`) }}
                                </span>
                            </template>
                            <template v-if="column.dataIndex == 'value'">
                                <span v-if="record.name == 'vue_version'">
                                    {{ vueVersion }}
                                </span>
                                <span v-else>{{ record.value }}</span>
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </a-card>
</template>

<script>
import { defineComponent, createVNode, ref, onMounted, version } from "vue";
import { Modal } from "ant-design-vue";
import { SyncOutlined, ExclamationCircleOutlined } from "@ant-design/icons-vue";
import axios from "axios";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { getUrlByAppType } from "../../../../../common/scripts/functions";

export default defineComponent({
    components: {
        SyncOutlined,
    },
    setup() {
        const appDetails = ref([]);
        const vueVersion = version;
        const { t } = useI18n();
        const product = ref([]);
        const store = useStore();

        const tableColumns = [
            {
                title: t("update_app.name"),
                dataIndex: "name",
                width: "40%",
            },
            {
                title: t("update_app.value"),
                dataIndex: "value",
            },
        ];

        const updateApp = () => {
            Modal.confirm({
                title: t("common.install"),
                icon: createVNode(ExclamationCircleOutlined),
                content: t("messages.are_you_sure_install_message"),
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    installUpdateModalTitle.value = t("common.installing") + "...";

                    emit("install", props.module.verified_name);

                    installUpdateModalVisible.value = true;
                },
                onCancel() {},
            });
        };

        return {
            tableColumns,
            appDetails,
            vueVersion,
            product,
            updateApp,
        };
    },
});
</script>
