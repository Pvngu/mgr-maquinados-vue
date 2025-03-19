<template>
    <AddEdit
        :addEditType="addEditType"
        :visible="addEditVisible"
        :url="addEditUrl"
        @addEditSuccess="onAddEditSuccess"
        @closed="onCloseAddEdit"
        :formData="formData"
        :data="viewData"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
    />

    <a-modal
        v-model:open="openGenerateDoc"
        :title="$t('docs.generate_documents')"
        centered
        :confirm-loading="confirmLoading"
        width="700px"
        @cancel="onClose"
    >
        <template #footer>
            <a-button key="back">{{ $t("docs.preview") }}</a-button>
            <a-button
                v-if="selectedOption === 'esign'"
                @click="sendEnvelope"
                key="submit"
                :loading="esignDocLoading"
                type="primary"
                :disabled="isEsignDisabled"
                >{{ $t("docs.send_for_signature") }}</a-button
            >
            <a-button
                v-if="selectedOption === 'save_doc'"
                @click="generateDoc"
                :loading="generateDocLoading"
                key="submit"
                type="primary"
                :disabled="!formDataDoc.template_id"
                >{{ $t("docs.generate_save") }}</a-button
            >
        </template>
        <!-- action -->
        <a-card>
            <a-row class="mb-10">
                <a-typography-text>
                    {{ $t("common.action") }}
                </a-typography-text>
            </a-row>
            <a-row :gutter="16">
                <a-col :span="12">
                    <a-button
                        @click="selectedOption = 'esign'"
                        :type="
                            selectedOption === 'esign' ? 'primary' : 'default'
                        "
                        block
                    >
                        <HighlightOutlined />
                        {{ $t("docs.send_doc_esign") }}
                    </a-button>
                </a-col>
                <a-col :span="12">
                    <a-button
                        @click="
                            () => {
                                selectedOption = 'save_doc';
                            }
                        "
                        :type="
                            selectedOption === 'save_doc'
                                ? 'primary'
                                : 'default'
                        "
                        block
                    >
                        <FileAddOutlined />
                        {{ $t("docs.generate_save_doc") }}
                    </a-button>
                </a-col>
            </a-row>
        </a-card>
        <!-- Document configuration -->
        <a-card :title="$t('docs.configuration')" class="mt-10">
            <a-form layout="vertical">
                <a-row>
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-form-item :label="$t('docs.select_template')">
                            <a-select
                                v-model:value="formDataDoc.template_id"
                                show-search
                                :placeholder="
                                    $t('common.select_default_text', [
                                        $t('docs.template'),
                                    ])
                                "
                            >
                            <a-select-option
                                v-for="item in allEsignDocTemplates"
                                :key="item.xid"
                                :value="item.xid"
                            >
                                {{ item.name }}
                            </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-form>
        </a-card>
        <!-- Assign Document Signers -->
        <a-card :title="$t('docs.assign_doc_signers')" class="mt-10" v-if="selectedOption === 'esign'">
            <a-form layout="vertical">
                <a-checkbox v-model:checked="formDataDoc.signOrder"
                    >{{ $t("docs.enforce_signing_order") }}</a-checkbox
                >
                <a-typography-title class="mt-10" :level="5">{{$t("docs.signers")}}</a-typography-title>
                <a-row>
                    <a-col :span="24">
                        <a-space
                            v-for="(signer, index) in signers"
                            align="baseline"
                            style="display: flex; align-items: center; margin-bottom: 8px;"
                        >
                            <div class="mr-5">
                                {{ index + 1 }}
                            </div>
                            <a-form-item
                                :label="$t('docs.signer_type')"
                                :name="['signers', index, 'type']"
                                class="required"
                                style="min-width: 140px;"
                            >
                                <a-select
                                    v-model:value="signer.type"
                                    :placeholder="
                                        $t(
                                            'common.select_default_text',
                                            [$t('docs.signer_type')]
                                        )
                                    "
                                    :options="signerTypes"
                                    @change="signerTypeChanged(signer)"
                                />
                            </a-form-item>
                            <a-form-item
                                :label="$t('docs.signer_name')"
                                :name="['signers', index, 'name']"
                                class="required"
                            >
                            <a-tooltip color="#108ee9">
                                <template v-if="signer.full_name" #title>{{ signer.full_name }}</template>
                                <a-input
                                    v-model:value="signer.full_name"
                                    :placeholder="
                                        $t(
                                            'common.placeholder_default_text',
                                            [$t('docs.signer_name')]
                                        )
                                    "
                                    :disabled="signer.type !== 'other'"
                                />
                            </a-tooltip>
                            </a-form-item>
                            <a-form-item
                                :label="$t('docs.signer_email')"
                                :name="['signers', index, 'email']"
                                class="required"
                            >
                                <a-tooltip color="#108ee9">
                                    <template v-if="signer.email" #title>{{ signer.email }}</template>
                                    <a-input
                                        v-model:value="signer.email"
                                        :placeholder="
                                            $t(
                                                'common.placeholder_default_text',
                                                [$t('docs.signer_email')]
                                            )
                                        "
                                        :disabled="signer.type !== 'other'"
                                    />
                                </a-tooltip>
                            </a-form-item>
                            <a-form-item
                                :label="$t('docs.signer_phone')"
                                :name="['signers', index, 'phone']"
                                class="required"
                            >
                                <a-tooltip color="#108ee9">
                                    <template v-if="signer.phone" #title>{{ signer.phone }}</template>
                                    <a-input
                                        v-model:value="signer.phone"
                                        :placeholder="
                                            $t(
                                                'common.placeholder_default_text',
                                                [$t('docs.signer_phone')]
                                            )
                                        "
                                        :disabled="signer.type !== 'other'"
                                    />
                                </a-tooltip>
                            </a-form-item>
                            <MinusCircleOutlined :class="{ 'opacity-0 cursor-default': index === 0 }" @click="removeSigner(signer)" />
                        </a-space>
                        <a-form-item v-if="signers.length < 45">
                            <a-button
                                @click="addSigner"
                                type="dashed"
                                block
                            >
                                <PlusOutlined />
                                {{ $t("docs.add_signer") }}
                            </a-button>
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-form>
        </a-card>
        <!-- Send via -->
        <a-card
            :title="$t('docs.delivery_expiration')"
            v-if="selectedOption === 'esign'"
            class="mt-10"
        >
            <a-form layout="vertical">
                <a-row>
                    <a-col :xs="24" :sm="24" :md="24" :lg="24">
                        <a-typography-title :level="5">{{
                            $t("docs.send_via")
                        }}</a-typography-title>
                        <a-checkbox v-model:checked="emailCheck"
                            >Email</a-checkbox
                        >
                        <a-row>
                            <a-row v-if="emailCheck">
                                <a-col span="24">
                                    <a-form-item
                                        :label="$t('campaign.email_template')"
                                        class="mt-10"
                                    >
                                        <a-select
                                            v-model:value="
                                                formDataDoc.email_template_id
                                            "
                                            show-search
                                            :placeholder="
                                                $t(
                                                    'common.select_default_text',
                                                    [
                                                        $t(
                                                            'campaign.email_template'
                                                        ),
                                                    ]
                                                )
                                            "
                                            @change="emailTemplateChanged"
                                        >
                                            <a-select-option
                                                v-for="item in allEsignEmailTemplates"
                                                :key="item.xid"
                                                :value="item.xid"
                                            >
                                                {{ item.name }}
                                            </a-select-option>
                                        </a-select>
                                    </a-form-item>
                                </a-col>
                                <a-col span="24">
                                    <a-form-item
                                        :label="$t('email_template.subject')"
                                        name="subject"
                                        class="required"
                                    >
                                        <a-input
                                            v-model:value="formDataDoc.subject"
                                            :placeholder="
                                                $t(
                                                    'common.placeholder_default_text',
                                                    [
                                                        $t(
                                                            'email_template.subject'
                                                        ),
                                                    ]
                                                )
                                            "
                                        />
                                    </a-form-item>
                                </a-col>
                                <a-col span="24">
                                    <a-textarea
                                        v-model:value="formDataDoc.message"
                                        show-count
                                        :maxlength="255"
                                        :placeholder="
                                            $t(
                                                'common.placeholder_default_text',
                                                [$t('email_template.message')]
                                            )
                                        "
                                        :rows="4"
                                        style="resize: none"
                                    />
                                    <!-- <QuillEditor
                                        ref="textEditor"
                                        v-model:content="formDataDoc.message"
                                        :content="formDataDoc.message"
                                        contentType="delta"
                                        :placeholder="
                                            $t(
                                                'common.placeholder_default_text',
                                                [$t('email_template.message')]
                                            )
                                        "
                                        style="height: 100px"
                                    /> -->
                                </a-col>
                            </a-row>
                        </a-row>
                        <a-row class="mt-10">
                            <a-col span="24">
                                <a-checkbox v-model:checked="smsCheck"
                                    >SMS</a-checkbox
                                >
                                <a-row v-if="smsCheck">
                                    <a-col span="24" class="mt-10">
                                        <a-form-item
                                            :label="$t('docs.sms_templates')"
                                        >
                                            <a-select
                                                v-model:value="
                                                    selectedSmsTemplate
                                                "
                                                show-search
                                                allowClear
                                                :placeholder="
                                                    $t(
                                                        'common.select_default_text',
                                                        [$t('sms.template')]
                                                    )
                                                "
                                            >
                                                <a-select-option
                                                    v-for="item in smsTemplates"
                                                    :key="item.name"
                                                    :value="item.name"
                                                >
                                                    {{ item.name }}
                                                </a-select-option>
                                            </a-select>
                                        </a-form-item>
                                    </a-col>
                                    <a-col :span="24">
                                        <a-form-item
                                            name="body"
                                            class="required"
                                        >
                                            <a-textarea
                                                v-model:value="smsBody"
                                                show-count
                                                :maxlength="160"
                                                :placeholder="
                                                    $t(
                                                        'common.placeholder_default_text',
                                                        [$t('common.sms')]
                                                    )
                                                "
                                                :rows="4"
                                                style="resize: none"
                                            />
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <!-- <a-row class="mt-20">
                            <a-typography-title :level="5">{{
                                $t("docs.expiration_date")
                            }}</a-typography-title>
                            <a-col span="24">
                                <a-form-item
                                    :label="$t('docs.expiration_date')"
                                    name="expiration_date"
                                    class="required"
                                >
                                    <a-date-picker
                                        v-model:value="formDataDoc.expiration_date"
                                        :format="appSetting.date_format"
                                        :placeholder="
                                            $t(
                                                'common.placeholder_default_text',
                                                [$t('docs.expiration_date')]
                                            )
                                        "
                                        style="width: 160px;"
                                        valueFormat="YYYY-MM-DD"
                                        :disabled-date="disabledDate"
                                    />
                                </a-form-item>
                            </a-col>
                        </a-row> -->
                    </a-col>
                </a-row>
            </a-form>
        </a-card>
    </a-modal>

    <a-row class="mb-20">
        <a-col :span="24">
            <UploadFileBig
                :acceptFormat="'image/*,.pdf'"
                :formData="formDataDoc"
                folder="documents"
                uploadField="file"
                @onFileUploaded="
                    (file) => {
                        formDataDoc.file = file.file;
                        formDataDoc.file_url = file.file_url;
                    }
                "
                :noChange="true"
                @change="addItem"
            />
        </a-col>
    </a-row>

    <a-row :gutter="[16,16]" justify="space-between">
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="6">
            <a-button type="primary" @click="addItem" block>
                <PlusOutlined />
                {{ $t("docs.add") }}
            </a-button>
        </a-col>
        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="6">
            <a-button
                block
                @click="openGenerateDoc = true;"
                >{{ $t("docs.generate_documents") }}
            </a-button>
        </a-col>
    </a-row>

    <a-row class="mt-20">
        <a-col :span="24">
            <div class="table-responsive documents-table">
                <a-tabs v-model:activeKey="activeKey">
                    <a-tab-pane key="generated" :tab="$t('docs.generated')">
                        <sharedTable
                            :columns="columns"
                            :table="table"
                            :permsArray="permsArray"
                            :formatDateTime="formatDateTime"
                            :viewItem="viewItem"
                            :showDeleteConfirm="showDeleteConfirm"
                        />
                    </a-tab-pane>
                    <a-tab-pane key="esign" :tab="$t('docs.esign')">
                        <sharedTable
                            :columns="eSignColumns"
                            :table="table"
                            :permsArray="permsArray"
                            :formatDateTime="formatDateTime"
                            :viewItem="viewItem"
                            :showDeleteConfirm="showDeleteConfirm"
                            type="esign"
                            @refreshEsign="refreshEsign()"
                        />
                    </a-tab-pane>
                    <a-tab-pane key="uploaded" :tab="$t('docs.uploaded')">
                        <sharedTable
                            :columns="columns"
                            :table="table"
                            :permsArray="permsArray"
                            :formatDateTime="formatDateTime"
                            :viewItem="viewItem"
                            :showDeleteConfirm="showDeleteConfirm"
                        />
                    </a-tab-pane>
                    <template #rightExtra>
                    <a-button type="primary" :disabled="table.data.length == 0" :loading="esignRefreshLoading" @click="fetchEnvelopes" v-if="activeKey == 'esign'">
                        <template #icon>
                            <ReloadOutlined />
                        </template>
                        {{ $t('docs.refresh') }}
                    </a-button>
                    </template>
                </a-tabs>
            </div>
        </a-col>
    </a-row>
</template>

<script> 
import { onMounted, ref, watch } from "vue";
import fields from "./fields";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import UploadFileBig from "../../../common/core/ui/file/UploadFileBig.vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import { notification } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import {
    EditOutlined,
    DeleteOutlined,
    PlusOutlined,
    EyeOutlined,
    HighlightOutlined,
    FileAddOutlined,
    MinusCircleOutlined,
    ReloadOutlined
} from "@ant-design/icons-vue";
import AddEdit from "./AddEdit.vue";
import sharedTable from "./sharedTable.vue";
import { QuillEditor } from "@vueup/vue-quill";
import { find, forEach, replace } from "lodash-es";
import dayjs from 'dayjs';

export default {
    props: {
        pageName: {
            default: "index",
        },
        individualId: {
            default: undefined,
        },
        scrollStyle: {
            default: {},
        },
        leadFormData: {
            default: {},
        },
        individualDetails: {
            default: {},
        },
        selectedTab: {
            default: "generated",
        }
    },
    emits: ["success"],
    components: {
        EditOutlined,
        DeleteOutlined,
        PlusOutlined,
        AddEdit,
        EyeOutlined,
        MinusCircleOutlined,
        UploadFileBig,
        ReloadOutlined,
        sharedTable,
        HighlightOutlined,
        FileAddOutlined,
        QuillEditor,
    },
    methods: {
        signerTypeChanged(item) {
            // Reset signer values
            item.full_name = "",
            item.first_name = "";
            item.last_name = "";
            item.email = "";
            item.phone = "";

            if(item.type === "applicant") {
                item.full_name = `${this.individualDetails.first_name} ${this.individualDetails.last_name}`;
                item.first_name = this.individualDetails.first_name;
                item.last_name = this.individualDetails.last_name;
                item.email = this.individualDetails.email;
                item.phone = this.individualDetails.phone_number;
            } else if(item.type === "co-applicant") {
                item.full_name = `${this.individualDetails.co_applicant.first_name} ${this.individualDetails.co_applicant.last_name}`;
                item.first_name = this.individualDetails.co_applicant.first_name;
                item.last_name = this.individualDetails.co_applicant.last_name;
                item.email = this.individualDetails.co_applicant.email;
                item.phone = this.individualDetails.co_applicant.phone_number;
            } else if(item.type === "assigned-user") {
                item.full_name = this.individualDetails.assigned_user.name;
                const fullName = this.individualDetails.assigned_user.name.split(" ");
                item.first_name = fullName[0];
                item.last_name = fullName.slice(1).join(" ");
                item.email = this.individualDetails.assigned_user.email;
                item.phone = this.individualDetails.assigned_user.phone;
            }

            // Reset all disabled values to false
            this.signerTypes.forEach(option => {
                option.disabled = false;
            });

            // Find the selected option and set its disabled value to true
            const selectedOption = this.signerTypes.find(option => option.value === item.type);
            if (selectedOption && selectedOption.value !== "other") {
                selectedOption.disabled = true;
            }

            // Disable the selected type for all other signers
            this.signers.forEach(signer => {
                if (signer.type !== item.type && signer.type !== "other") {
                    const option = this.signerTypes.find(option => option.value === signer.type);
                    if (option) {
                        option.disabled = true;
                    }
                }
            });
        },
    },
    computed: {
        isEsignDisabled() {
            var isDisabled = false;
            if((!this.emailCheck && !this.smsCheck) || !this.formDataDoc.template_id) {
                isDisabled = true;
            }

            if(this.emailCheck) {
                if(!this.formDataDoc.subject || !this.formDataDoc.message) {
                    isDisabled = true;
                }
            }

            this.signers.forEach(signer => {
                if(!signer.type || !signer.full_name || !signer.email || !signer.phone) {
                    isDisabled = true;
                }
            });

            return isDisabled;
        },
        
    },
    setup(props, { emit }) {
        const activeKey = ref("");
        const { initData, columns, eSignColumns, addEditUrl } = fields(props);
        const { formatDateTime, permsArray } = common();
        const { addEditRequestAdmin } = apiAdmin();
        const crudVariables = crud();
        const { t } = useI18n();
        const extraFilters = ref({
            individual_id:
                props.individualId != undefined
                    ? props.individualId
                    : undefined,
        });
        const openGenerateDoc = ref(false);
        const confirmLoading = ref(false);
        const selectedOption = ref("save_doc");
        const emailCheck = ref(true);
        const smsCheck = ref(false);
        const formDataDoc = ref({
            template_id: "",
            subject: "",
            message: "",
            signOrder: false
        });
        const selectedSmsTemplate = ref(null);
        const allEsignEmailTemplates = ref([]);
        const allEsignDocTemplates = ref([]);
        const smsTemplates = ref({});
        const smsBody = ref("");
        const smsTemplateUrl = "sms-templates/all";
        const esignEmailTemplateUrl = "e-sign-templates/all";
        const esignDocTemplateUrl = "e-sign-doc-templates/all";
        const textEditor = ref(null);
        const { appSetting } = common();
        const generateDocLoading = ref(false);
        const esignDocLoading = ref(false);
        const esignRefreshLoading = ref(false);

        const signerTypes = ref([
            {
                value: "applicant",
                label: "Applicant",
                disabled: false,
            }
        ]);

        const signers = ref([{
            type: "",
            full_name: "",
            first_name: "",
            last_name: "",
            email: "",
            phone: "",
        }]);

        const addSigner = () => {
            signers.value.push({
                type: "",
                full_name: "",
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
            });
        };

        const removeSigner = signer => {
            const index = signers.value.indexOf(signer);
            if(index !== 0) {
                signers.value.splice(index, 1);
            }
            // Set disabled signer type to false
            if(signer.type) {
                signerTypes.value.find(option => option.value === signer.type).disabled = false;
            }
        }

        onMounted(() => {
            activeKey.value = props.selectedTab;

            setUrlData(activeKey.value);
            fetchInitData();

            if(props.individualDetails.co_applicant) {
                signerTypes.value.push({
                    value: "co-applicant",
                    label: "Co-Applicant",
                    disabled: false,
                });
            }

            if(props.individualDetails.assigned_user) {
                signerTypes.value.push({
                    value: "assigned-user",
                    label: "Assigned User",
                    disabled: false,
                });
            }

            signerTypes.value.push({
                value: "other",
                label: "Other",
                disabled: false,
            });
        });

        const fetchInitData = () => {
            const esignEmailTemplatesPromise = axiosAdmin.get(esignEmailTemplateUrl);
            const esignDocTemplatesPromise = axiosAdmin.get(esignDocTemplateUrl);
            const smsTemplatesPromise = axiosAdmin.get(smsTemplateUrl);

            Promise.all([smsTemplatesPromise, esignEmailTemplatesPromise, esignDocTemplatesPromise]).then(
                ([smsTemplatesResponse, esignEmailTemplatesResponse, esignDocTemplatesResponse]) => {
                    smsTemplates.value =
                        smsTemplatesResponse.data.sms_templates;
                    allEsignEmailTemplates.value =
                        esignEmailTemplatesResponse.data.esign_email_templates;
                    allEsignDocTemplates.value =
                        esignDocTemplatesResponse.data.esign_templates;
                }
            );
        };

        const setUrlData = (type) => {
            const url = `documents?fields=id,xid,title,type,status,signeasy_document_id,file,file_url,signers{id,first_name,last_name,email,status,last_modified_time},created_by_id,individual_id,x_individual_id,created_at,creator{id,xid,name}updater{id,xid,name}&type=${type}`;
            crudVariables.tableUrl.value = {
                url: url,
                extraFilters,
            };

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "docs";
            crudVariables.initData.value = {
                ...initData,
                individual_id: props.individualId,
            };
            crudVariables.formData.value = {
                ...initData,
                individual_id: props.individualId,
            };
        };

        const onAddEditSuccess = (id) => {
            crudVariables.addEditSuccess(id);

            activeKey.value = "uploaded";

            emit("success");
        };

        const emailTemplateChanged = () => {
            const selectedEmailTemplate = find(allEsignEmailTemplates.value, [
                "xid",
                formDataDoc.value.email_template_id,
            ]);

            if (selectedEmailTemplate && selectedEmailTemplate.body) {
                var bodyMessage = selectedEmailTemplate.body;

                forEach(props.leadFormData.lead_data, (leadDataValue, leadDataKey) => {
                    if (
                        leadDataValue.field_value != undefined &&
                        leadDataValue.field_value != ""
                    ) {
                        bodyMessage = replace(
                            bodyMessage,
                            `##${leadDataValue.field_name}##`,
                            leadDataValue.field_value
                        );
                    }
                });

                forEach(props.leadFormData.lead_data, (leadDataValue, leadDataKey) => {
                    if (
                        leadDataValue.field_value != undefined &&
                        leadDataValue.field_value != ""
                    ) {
                        bodyMessage = replace(
                            bodyMessage,
                            `##${leadDataValue.field_name}##`,
                            leadDataValue.field_value
                        );
                    }
                });

                formDataDoc.value = {
                    ...formDataDoc.value,
                    message: bodyMessage,
                    subject: selectedEmailTemplate.subject,
                };

                // Not execute at onMounted
                if (textEditor.value != undefined) {
                    textEditor.value.setHTML(bodyMessage);
                }
            }
        };

        const disabledDate = current => {
        // Can not select days before today and today
        return current && current < dayjs().endOf('day');
        };

        const generateDoc = () => {
            generateDocLoading.value = true;
            addEditRequestAdmin({
                url: "documents/generate-pdf",
                data: {
                    ...formDataDoc.value,
                    individualId: props.individualId,
                },
                success: (res) => {
                    openGenerateDoc.value = false;
                    generateDocLoading.value = false;
                    if(res.success == true) {
                        notification.success({
                            message: t("common.success"),
                            description: t("docs.generated_successfully"),
                            placement: "bottomRight",
                        });

                        if(activeKey.value === 'generated') {
                            setUrlData("generated");
                        } else {
                            activeKey.value = "generated";
                        }
                        
                        emit("success");
                    } else {
                        notification.error({
                            message: t("common.error"),
                            description: t("docs.generate_error_message"),
                            placement: "bottomRight",
                        });
                    }
                },
                error : () => {
                    generateDocLoading.value = false;
                }
            });
        }

        const sendEnvelope = () => {
            esignDocLoading.value = true;
            // sendEnvelopeLoading.value = true;
            addEditRequestAdmin({
                url: "documents/send-envelope",
                data: {
                    ...formDataDoc.value,
                    signers: signers.value,
                    individualId: props.individualId,
                },
                success: (res) => {
                    openGenerateDoc.value = false;
                    esignDocLoading.value = false;
                    if(res.success == true) {
                        //reset form data
                        signers.value = [{
                            type: "",
                            full_name: "",
                            first_name: "",
                            last_name: "",
                            email: "",
                            phone: "",
                        }];

                        formDataDoc.value = {
                            subject: "",
                            message: "",
                            signOrder: false,
                        };

                        emailCheck.value = false;
                        smsCheck.value = false;

                        signerTypes.value.forEach(option => {
                            option.disabled = false;
                        });

                        notification.success({
                            message: t("common.success"),
                            description: t("docs.esign_successfully"),
                            placement: "bottomRight",
                        });

                        if(activeKey.value === 'esign') {
                            setUrlData("e-sign");
                        } else {
                            activeKey.value = "esign";
                        }
                        
                        emit("success");
                    } else {
                        notification.error({
                            message: t("common.error"),
                            description: t("docs.esign_error_message"),
                            placement: "bottomRight",
                        });
                    }
                },
                error : () => {
                    esignDocLoading.value = false;
                }
            });
        }

        const fetchEnvelopes = () => {
            esignRefreshLoading.value = true;
            axiosAdmin.get(`documents/fetch-envelopes/${props.individualId}`).then((res) => {
                if(res.data.success == true) {
                    setUrlData("e-sign");
                    notification.success({
                        message: t("common.success"),
                        description: t("docs.refresh_successfully"),
                        placement: "bottomRight",
                    });
                }
            esignRefreshLoading.value = false;
        });
        }

        const refreshEsign = () => {
            setUrlData("e-sign");
        }

        const onClose = () => {
            formDataDoc.value = {
                template_id: "",
                subject: "",
                message: "",
                signOrder: false
            }

            signers.value = [];

            signerTypes.value.forEach(option => {
                option.disabled = false;
            });
        };

        watch(activeKey, (value) => {
            if (value === "generated") {
                setUrlData("generated");
            } else if (value === "uploaded") {
                setUrlData("uploaded");
            } else if (value === "esign") {
                setUrlData("e-sign");
            }
        });

        watch(selectedSmsTemplate, (value) => {
            if (value) {
                smsBody.value = smsTemplates.value.find(
                    (item) => item.name == value
                ).body;
            } else {
                smsBody.value = "";
            }
        });

        watch(emailCheck, (value) => {
            if (!value) {
                formDataDoc.value.email_template_id = undefined;
                formDataDoc.value.subject = "";
                formDataDoc.value.message = "";
            }
        });

        watch(smsCheck, (value) => {
            if (!value) {
                selectedSmsTemplate.value = null;
                smsBody.value = "";
            }
        });

        return {
            columns,
            extraFilters,
            permsArray,
            onAddEditSuccess,
            formatDateTime,
            eSignColumns,
            activeKey,
            confirmLoading,
            openGenerateDoc,
            selectedOption,
            emailCheck,
            smsCheck,
            allEsignEmailTemplates,
            allEsignDocTemplates,
            smsTemplates,
            smsBody,
            selectedSmsTemplate,
            emailTemplateChanged,
            formDataDoc,
            textEditor,
            appSetting,
            disabledDate,
            generateDoc,
            generateDocLoading,
            esignDocLoading,
            sendEnvelope,
            signerTypes,
            signers,
            addSigner,
            removeSigner,
            refreshEsign,
            esignRefreshLoading,
            fetchEnvelopes,
            onClose,

            ...crudVariables,
        };
    },
};
</script>