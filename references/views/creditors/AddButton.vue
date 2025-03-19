<template>
    <div
        v-if="
            permsArray.includes('creditors_create') || permsArray.includes('admin')
        "
    >
        <a-tooltip
            v-if="tooltip"
            placement="topLeft"
            :title="$t('creditor.add')"
            arrow-point-at-center
        >
            <a-button @click="showAdd" class="ml-5 no-border-radius" :type="btnType">
                <template #icon> <PlusOutlined /> </template>
                <slot></slot>
            </a-button>
        </a-tooltip>
        <a-button v-else @click="showAdd" class="ml-5 no-border-radius" :type="btnType">
            <template #icon> <PlusOutlined /> </template>
            <slot></slot>
        </a-button>

        <AddEdit
            :addEditType="addEditType"
            :visible="visible"
            :url="addEditUrl"
            @addEditSuccess="addEditSuccess"
            @closed="onClose"
            :formData="formData"
            :data="formData"
            :pageTitle="$t('creditor.add')"
            :states="states"
            :successMessage="$t('creditor.created')"
        />
    </div>
</template>
<script>
import { defineComponent, ref, onMounted } from "vue";
import { PlusOutlined } from "@ant-design/icons-vue";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";
import common from "../../../common/composable/common";

export default defineComponent({
    props: {
        btnType: {
            default: "default",
        },
        tooltip: {
            default: true,
        },
    },
    emits: ["onAddSuccess"],
    components: {
        PlusOutlined,
        AddEdit,
    },
    setup(props, { emit }) {
        const { permsArray } = common();
        const { initData, addEditUrl, states, getPrefetchData } = fields();
        const visible = ref(false);
        const addEditType = ref("add");
        const formData = ref({ ...initData });

        const showAdd = () => {
            visible.value = true;
        };

        onMounted(() => {
            getPrefetchData();
        });

        const addEditSuccess = () => {
            visible.value = false;
            formData.value = { ...initData };
            emit("onAddSuccess");
        };

        const onClose = () => {
            visible.value = false;
        };

        return {
            permsArray,
            visible,
            addEditType,
            addEditUrl,
            formData,
            states,

            addEditSuccess,
            onClose,
            showAdd,
        };
    },
});
</script>
