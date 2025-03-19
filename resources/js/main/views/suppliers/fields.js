import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "suppliers?fields=id,xid,name,contact_name,contact_email,contact_phone";
    const addEditUrl = "suppliers";
    const { t } = useI18n();
    const hashableColumns = [];

    const initData = {
        name: "",
        contact_name: "",
        contact_email: "",
        contact_phone: "",
    };

    const columns = [
        {
            title: t("common.name"),
            dataIndex: "name",
        },
        {
            title: t("common.contact_name"),
            dataIndex: "contact_name",
        },
        {
            title: t("common.contact_email"),
            dataIndex: "contact_email",
        },
        {
            title: t("common.contact_phone"),
            dataIndex: "contact_phone",
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
            key: "contact_name",
            value: t("common.contact_name"),
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
