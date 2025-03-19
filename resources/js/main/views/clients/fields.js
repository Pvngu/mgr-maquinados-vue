import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "clients?fields=id,xid,name,email,phone,address";
    const addEditUrl = "clients";
    const { t } = useI18n();
    const hashableColumns = [];

    const initData = {
        name: "",
        email: "",
        phone: "",
        address: "",
    };

    const columns = [
        {
            title: t("common.name"),
            dataIndex: "name",
        },
        {
            title: t("common.email"),
            dataIndex: "email",
        },
        {
            title: t("common.phone"),
            dataIndex: "phone",
        },
        {
            title: t("common.address"),
            dataIndex: "address",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "name",
            value: t("common.name"),
        },
        {
            key: "email",
            value: t("common.email"),
        },
    ];

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
        hashableColumns,
    };
};

export default fields;
