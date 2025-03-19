import { useI18n } from "vue-i18n";

const fields = () => {
    const { t } = useI18n();
    const addEditUrl = "documents";

    const initData = {
        title: "",
        created_by: "",
        created_at: "",
    }

    const columns = [
        {
            title: t("docs.title"),
            dataIndex: "title",
            key: "title"
        },
        {
            title: t("common.created_by"),
            dataIndex: "created_by_id",
            key: "created_by_id"
        },
        {
            title: t("docs.created_at"),
            dataIndex: "created_at",
        },
        {
            title: t("common.action"),
            dataIndex: "action"
        },
    ];

    const eSignColumns = [
        {
            title: t("docs.title"),
            dataIndex: "title",
            key: "title"
        },
        {
            title: t("common.status"),
            dataIndex: "status",
            key: "status",
        },
        {
            title: t("docs.sent_by"),
            dataIndex: "created_by_id",
            key: "created_by_id",
        },
        {
            title: t("docs.sent_at"),
            dataIndex: "created_at",
        },
        {
            title: t("common.action"),
            dataIndex: "action"
        },
    ]

    return {
        initData,
        columns,
        addEditUrl,
        eSignColumns,
    }
}

export default fields;