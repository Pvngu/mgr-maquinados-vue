<template>
    <a-modal
        :open="visible"
        :closable="false"
        :centered="true"
        :title="pageTitle"
        @ok="onSubmit"
    >
    <a-form layout="vertical">
        <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-form-item
                    :label="$t('docs.title')"
                    name="title"
                    :help="
                        rules.title
                            ? rules.title.message
                            : null
                    "
                    :validateStatus="
                        rules.title ? 'error' : null
                    "
                    class="required"
                >
                <a-input
                    v-model:value="formData.title"
                    :placeholder="
                        $t(
                            'common.placeholder_default_text',
                            [$t('docs.title')]
                        )
                    "
                />
                </a-form-item>
            </a-col>
        </a-row>
        <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <UploadFileBig
                    :acceptFormat="'image/*,.pdf'"
                    :formData="formData"
                    folder="documents"
                    uploadField="file"
                    @onFileUploaded="
                        (file) => {
                            formData.file = file.file;
                            formData.file_url = file.file_url;
                        }
                    "
                />
            </a-col>
        </a-row>
    </a-form>
    <template #footer>
        <a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
            <template #icon>
                <SaveOutlined />
            </template>
            {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
        </a-button>
        <a-button key="back" @click="onClose">
            {{ $t("common.cancel") }}
        </a-button>
    </template>
    </a-modal>
</template>
<script>
import { defineComponent, ref, onMounted, computed } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, CloudUploadOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import UploadFileBig from "../../../common/core/ui/file/UploadFileBig.vue";


export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        CloudUploadOutlined,
        UploadFileBig
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

        const onSubmit = () => {
            addEditRequestAdmin({
                url: props.url,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        return {
            loading,
            rules,
            onClose,
            onSubmit,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>