<template>
    <div class="table-responsive">
        <a-table
            :columns="columns"
            :row-key="(record) => record.xid"
            :data-source="table.data"
            :pagination="{ pageSize: 8}"
            :loading="table.loading"
            :scroll="{ x: 1000, y: 'calc(100vh - 540px)' }"
        >
            <template #bodyCell="{ column, text, record }">
                <template v-if="column.dataIndex === 'title'">
                    <span style="max-width: 400px; text-wrap: wrap;">
                        {{ record.title }}
                    </span>
                </template>
                <template v-if="column.dataIndex === 'created_at'">
                    {{ formatDateTime(record.created_at) }}
                </template>
                <template v-if="column.dataIndex === 'status' && record.signers">
                    <a-tag color="green" v-if="record.status == 'complete'">
                        {{ $t('docs.signed') }}
                    </a-tag>
                    <a-tag color="volcano" v-if="record.status == 'incomplete'">
                        {{ $t('docs.waiting') }}
                    </a-tag>
                    <a-tag color="default" v-if="record.status == 'canceled'">
                        {{ $t('docs.voided') }}
                    </a-tag>
                    <span style="padding-right: 7px;" v-if="record.status !== 'incomplete'">
                        {{ $t('docs.by') }}
                    </span>
                    <span style="padding-right: 7px;" v-else>
                        {{ $t('docs.for') }}
                    </span>
                    <a-popover :title="$t('docs.signers')" v-if="record.signers">
                        <template #content>
                            <a-row v-for="signer in record.signers" class="mt-10">
                                <a-col :span="3">
                                    <UserOutlined style="font-size: 1.1rem;" :class="signer.status" />
                                </a-col>
                                <a-col :span="21">
                                    <a-row>
                                        <a-col :span="24">
                                            <strong style="padding-right: 8px;">{{ signer.first_name }} {{ signer.last_name }}</strong>
                                            <span v-if="signer.status == 'viewed'">({{ $t('docs.viewed') }})</span>
                                        </a-col>
                                        <a-col>
                                            <a-row>
                                                {{ signer.email }}
                                            </a-row>
                                            <a-row>
                                                <span v-if="signer.status == 'finalized'">
                                                    {{ formatUnixDateTime(signer.last_modified_time) }}
                                                </span>
                                                <span v-if="signer.status == 'not_viewed' || signer.status == 'viewed'">
                                                    {{ $t('docs.waiting_for_signature') }}
                                                </span>
                                            </a-row>
                                        </a-col>
                                    </a-row>
                                </a-col>
                            </a-row>
                        </template>
                        <span v-if="record.status === 'incomplete'">
                            <a v-if="record.signers.filter(signer => signer.status == 'not_viewed').length > 1">{{ record.signers.length }} {{ $t('docs.signers') }}</a>
                            <a v-else>
                                <span v-if="record.signers.find(signer => signer.status !== 'finalized')">
                                    {{ record.signers.find(signer => signer.status !== 'finalized').first_name }} 
                                    {{ record.signers.find(signer => signer.status !== 'finalized').last_name }}
                                </span>
                            </a>
                        </span>
                        <span v-if="record.status === 'complete'">
                            <a v-if="record.signers.filter(signer => signer.status == 'finalized').length > 1">{{ record.signers.length }} {{ $t('docs.signers') }}</a>
                            <a v-else>
                                {{ record.signers[0].first_name }} 
                                {{ record.signers[0].last_name }}
                            </a>
                        </span>
                        <span v-if="record.status === 'canceled'">
                            <a v-if="record.updater">
                                {{ record.updater.name }}
                            </a>
                            <a v-else>
                                {{ $t('common.unknown') }}
                            </a>
                        </span>
                    </a-popover>
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <a-button
                        :href="'/uploads/documents/' + record.file" target="_blank"
                        type="primary"
                        @click="viewItem(record)"
                        style="margin-left: 4px"
                        v-if="tableType !== 'esign'"
                    >
                        <template #icon><EyeOutlined /></template>
                    </a-button>
                    <a-button
                        v-if="
                            permsArray.includes('documents_delete') ||
                            permsArray.includes('admin') && tableType !== 'esign'
                        "
                        type="primary"
                        @click="showDeleteConfirm(record.xid, record.file)"
                        style="margin-left: 4px"
                    >
                        <template #icon><DeleteOutlined /></template>
                    </a-button>
                    <!-- sign easy table -->
                    
                    <!-- only for signed status -->
                    <a-dropdown v-if="tableType == 'esign' && record.status == 'complete'">
                        <a @click.prevent>
                            {{ $t('common.download') }}
                            <DownOutlined />
                        </a>
                        <template #overlay>
                            <a-menu>
                                <a-menu-item @click="downloadEnvelope(record, 'complete', 'true')">
                                    {{ $t('docs.signed_document_with_audit_trail') }}
                                </a-menu-item>
                                <a-menu-item @click="downloadEnvelope(record, 'complete')">
                                    {{ $t('docs.signed_document') }}
                                </a-menu-item>
                                <a-menu-item @click="downloadEnvelope(record, 'audit')">
                                    {{ $t('docs.audit_trail') }}
                                </a-menu-item>
                            </a-menu>
                        </template>
                    </a-dropdown>
                    <!-- only for voided status -->

                    <!-- only for incomplete status -->
                    <div v-if="tableType == 'esign' && record.status == 'incomplete'">
                        <a style="margin-right: 8px" @click="sendReminder(record)">{{ $t('docs.send_reminder') }}</a>
                        <a-dropdown :trigger="['click']">
                            <a-button
                                @click.prevent
                            >
                                <template #icon><EllipsisOutlined /></template>
                            </a-button>
                            <template #overlay>
                                <a-menu >
                                    <a-menu-item key="0" @click="downloadEnvelope(record, 'incomplete')">
                                        {{ $t('common.download') }}
                                    </a-menu-item>
                                    <a-menu-item key="1" @click="voidEnvelope(record)">
                                        {{ $t('docs.void') }}
                                    </a-menu-item>
                                </a-menu>
                            </template>
                        </a-dropdown>
                    </div>
                </template>
                <template v-if="column.dataIndex === 'created_by_id'">
                    {{ record.creator.name }}
                </template>
            </template>
        </a-table>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, CloudUploadOutlined, EditOutlined, DeleteOutlined, EyeOutlined, EllipsisOutlined, ExclamationCircleOutlined, DownOutlined, UserOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import UploadFileBig from "../../../common/core/ui/file/UploadFileBig.vue";
import { Modal, notification, Input } from "ant-design-vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import { createVNode } from "vue";
import common from "../../../common/composable/common";

export default defineComponent({
    props: {
        columns: Array,
        table: Object,
        permsArray: Array,
        formatDateTime: Function,
        viewItem: Function,
        showDeleteConfirm: Function,
        type: String,
    },
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        CloudUploadOutlined,
        EditOutlined,
        DeleteOutlined,
        EyeOutlined,
        EllipsisOutlined,
        UploadFileBig,
        DownOutlined,
        UserOutlined
    },
    setup(props, { emit }) {
        const { t } = useI18n();
        const voidReason = ref("");
        const { addEditRequestAdmin, rules } = apiAdmin();
        const { formatUnixDateTime } = common();

        const downloadEnvelope = (record, type, includeCertificated = 'false') => {
            axiosAdmin
                .get(`documents/download-envelope/${record.signeasy_document_id}/${type}/${includeCertificated}`, { responseType: 'blob' })
                .then((res) => {
                    // Create a new Blob object using the response data
                    const blob = new Blob([res], { type: 'application/pdf' });

                    // Create a URL for the blob and open it in a new tab
                    const url = window.URL.createObjectURL(blob);
                    window.open(url, '_blank');
                })
                .catch((error) => {
                    console.error('Error downloading the PDF:', error);
                });
        };

        const voidEnvelope = (record) => {
            let url = '';

            url = 'documents/void-envelope';

            Modal.confirm({
                title: t("docs.voiding_reason"),
                icon: createVNode(ExclamationCircleOutlined),
                content: createVNode('div', null, [
                    createVNode('p', null, t('docs.voiding_description')),
                    createVNode(Input.TextArea, {
                        modelValue: voidReason.value,
                        onInput: (e) => voidReason.value = e.target.value,
                        showCount: true,
                        maxlength: 255,
                        autoSize: { minRows: 6, maxRows: 8 },
                        placeholder: t('docs.add_your_reason', [255]),
                        style: 'height: 100px;'
                    })
                ]),
                width: '550px',
                okText: t("docs.void"),
                okType: "danger",
                cancelText: t("common.cancel"),
                onOk() {
                    emit("refreshEsign");
                    addEditRequestAdmin({
                    url: url,
                    data: { 
                        document_id: record.xid,
                        reason: voidReason.value
                    },
                    success: (res) => {
                        if (res.success === true) {
                            notification.success({
                                message: t("common.success"),
                                description: t("docs.signature_request_voided"),
                                placement: "bottomRight",
                            });
                            emit("refreshEsign");
                        } else {
                            notification.error({
                            message: t("common.error"),
                            description: t("docs.error_voiding_signature_request"),
                            placement: "bottomRight",
                        });
                        }
                    }
                });
                },
                onCancel() { },
            });
        };

        const sendReminder = (record) => {
            axiosAdmin.post('documents/send-reminder', { file_id: record.signeasy_document_id }).
            then((res) => {
                if (res.data.success === true) {
                    notification.success({
                        message: t("common.success"),
                        description: t("docs.reminder_sent"),
                        placement: "bottomRight",
                    });
                } else if (res.data.success === false && res.data.status_code === 429) {
                    notification.error({
                        message: t("common.error"),
                        description: t("docs.reminder_too_many_error"),
                        placement: "bottomRight",
                    });
                } 
                else {
                    notification.error({
                        message: t("common.error"),
                        description: t("docs.error_sending_reminder"),
                        placement: "bottomRight",
                    });
                }
            });
        }
        
        return {
            t,
            downloadEnvelope,
            voidEnvelope,
            sendReminder,
            formatUnixDateTime,
            tableType: props.type ?? "",
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>

<style scoped>
.finalized {
    color: #52c41a;
}

.not_viewed {
    color: rgb(182, 182, 182);
}
.viewed {
    color: #faad14;
}
</style>