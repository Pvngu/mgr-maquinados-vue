import { reactive, ref, createVNode, watch, computed } from "vue";
import { Modal, notification } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import datatable from "./datatable";
import { includes, has, get, forEach } from "lodash-es";

const crud = () => {
    const {
        table,
        tableUrl,
        hashable,
        currentPage,
        handleTableChange,
        fetch,
        onTableSearch,
        onRowSelectChange,
        exportDetails,
    } = datatable();
    const detailsVisible = ref(false);
    const viewData = ref({});
    const crudUrl = ref("");
    const langKey = ref("");
    const initData = ref({});
    const hashableColumns = ref([]);
    const multiDimensalObjectColumns = ref({});

    const addEditVisible = ref(false);
    const addEditType = ref("add");
    const addEditUrl = ref("");

    const formData = ref({});
    const { t } = useI18n();
    const store = useStore();

    const viewItem = (item) => {
        detailsVisible.value = true;
        viewData.value = item;
    };

    const addItem = () => {
        addEditUrl.value = crudUrl.value;
        addEditType.value = "add";
        addEditVisible.value = true;
        viewData.value = {};
    };

    const onCloseAddEdit = () => {
        restFormData();
        addEditVisible.value = false;
    };

    const onCloseDetails = () => {
        detailsVisible.value = false;
        viewData.value = {};
    };

    const editItem = (item) => {
        const itemDetails = {};
        var multiDimension = multiDimensalObjectColumns.value;

        Object.keys(initData.value).forEach((key) => {
            if (has(multiDimension, key)) {
                const multiDimensalObjectColumnValue = multiDimension[key];
                itemDetails[key] = get(item, multiDimensalObjectColumnValue);
            } else if (includes(hashableColumns.value, key)) {
                itemDetails[key] = item[`x_${key}`];
            } else {
                itemDetails[key] = item[key];
            }
        });

        itemDetails["_method"] = "PUT";
        formData.value = itemDetails;

        viewData.value = item;
        addEditUrl.value = `${crudUrl.value}/${item.xid}`;
        addEditType.value = "edit";
        addEditVisible.value = true;
    };

    // Update Visible Subscription Modules
    const updateSubscriptionModules = () => {
        store.dispatch("auth/updateVisibleSubscriptionModules");
    };

    const addEditSuccess = (id) => {
        successAction(id, 'add-edit');
    };

    const addAndNewSuccess = (id) => {
        successAction(id, 'add-new');
    }

    const addAndContinueSuccess = (id) => {
        successAction(id, 'add-continue');
    }

    const successAction = (id, submitType = 'add-edit') => {
        // If add action is performed then move page to first
        if (addEditType.value == "add") {
            const pagination = { ...table.pagination };
            pagination.current = 1;
            currentPage.value = 1;
            table.pagination = pagination;
        }

        // Update Visible Subscription Modules
        updateSubscriptionModules();

        if (submitType == "add-edit" || submitType == 'add-new') {
            restFormData();
        }

        fetch({
            page: addEditType.value == "add" ? 1 : currentPage.value,
            limit: 10,
        });

        if (submitType == "add-edit") {
            addEditVisible.value = false;
        }
    };

    const showDeleteConfirm = (id, file) => {
        Modal.confirm({
            title: t("common.delete") + "?",
            icon: createVNode(ExclamationCircleOutlined),
            content: t(`${langKey.value}.delete_message`),
            centered: true,
            okText: t("common.yes"),
            okType: "danger",
            cancelText: t("common.no"),
            onOk() {
                if(crudUrl.value == 'documents') {
                    axiosAdmin.delete(`deleteFile/${file}`);
                }
                return axiosAdmin
                    .delete(`${crudUrl.value}/${id}`)
                    .then((successResponse) => {
                        // Update Visible Subscription Modules
                        updateSubscriptionModules();

                        fetch({
                            page: currentPage.value,
                        });

                        notification.success({
                            message: t("common.success"),
                            description: t(`${langKey.value}.deleted`),
                            placement: "bottomRight",
                        });
                    })
                    .catch(() => { });
            },
            onCancel() { },
        });
    };

    const showSelectedDeleteConfirm = () => {
        Modal.confirm({
            title: t("common.delete") + "?",
            icon: createVNode(ExclamationCircleOutlined),
            content: t(`${langKey.value}.selected_delete_message`),
            centered: true,
            okText: t("common.yes"),
            okType: "danger",
            cancelText: t("common.no"),
            onOk() {
                const allDeletePromise = [];
                forEach(table.selectedRowKeys, (selectedRow) => {
                    allDeletePromise.push(
                        axiosAdmin.delete(`${crudUrl.value}/${selectedRow}`)
                    );
                });

                Promise.all(allDeletePromise).then((successResponse) => {
                    // Update Visible Subscription Modules
                    updateSubscriptionModules();

                    fetch({
                        page: currentPage.value,
                    });

                    notification.success({
                        message: t("common.success"),
                        description: t(`${langKey.value}.deleted`),
                        placement: "bottomRight",
                    });
                });
            },
            onCancel() { },
        });
    };

    const restFormData = () => {
        formData.value = { ...initData.value };
    };

    const assignNewFormData = (newObject) => {
        formData.value = {
            ...formData.value,
            ...newObject,
        };
    };

    const pageTitle = computed(() => {
        if (langKey.value != "") {
            return addEditType.value == "add"
                ? t(`${langKey.value}.add`)
                : t(`${langKey.value}.edit`);
        } else {
            return "";
        }
    });

    const successMessage = computed(() => {
        if (langKey.value != "") {
            return addEditType.value == "add"
                ? t(`${langKey.value}.created`)
                : t(`${langKey.value}.updated`);
        } else {
            return "";
        }
    });

    watch(hashableColumns, (newVal, oldVal) => {
        hashable.value = newVal;
    });

    return {
        detailsVisible,
        viewData,
        onCloseDetails,
        addEditVisible,
        addEditType,
        addEditUrl,
        currentPage,
        formData,
        initData,
        crudUrl,
        langKey,
        hashableColumns,
        multiDimensalObjectColumns,
        pageTitle,
        successMessage,

        viewItem,
        addItem,
        editItem,
        onCloseAddEdit,
        showDeleteConfirm,
        addEditSuccess,
        addAndNewSuccess,
        addAndContinueSuccess,
        restFormData,
        assignNewFormData,
        showSelectedDeleteConfirm,

        table,
        tableUrl,
        hashable,
        handleTableChange,
        fetch,
        onTableSearch,
        onRowSelectChange,
        exportDetails,
    };
};

export default crud;
