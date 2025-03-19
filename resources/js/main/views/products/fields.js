import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "products?fields=id,xid,name,description,price,stock_quantity,category_id,initial_stock_quantity";
    const addEditUrl = "products";
    const { t } = useI18n();
    const hashableColumns = ["category_id"];

    const initData = {
        name: "",
        description: "",
        price: 0,
        stock_quantity: 0,
        initial_stock_quantity: 0,
        category_id: undefined,
    };

    const columns = [
        {
            title: t("common.name"),
            dataIndex: "name",
        },
        {
            title: t("common.description"),
            dataIndex: "description",
        },
        {
            title: t("common.price"),
            dataIndex: "price",
        },
        {
            title: t("common.stock_quantity"),
            dataIndex: "stock_quantity",
        },
        {
            title: t("common.initial_stock_quantity"),
            dataIndex: "initial_stock_quantity",
        },
        {
            title: t("common.category"),
            dataIndex: "category_id",
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
            key: "category_id",
            value: t("common.category"),
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
