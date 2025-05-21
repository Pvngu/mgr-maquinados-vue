import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "products?fields=id,xid,name,description,price,stock_quantity,category_id,x_category_id,initial_stock_quantity,category";
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

    const categories = ref([]);

    const getPrefetchData = () => {
        const categoryPromise = axiosAdmin.get("categories?fields=id,xid,name&limit=1000");
        return Promise.all([categoryPromise]).then(([categoryResponse]) => {
            categories.value = categoryResponse.data;
        });
    }

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
            title: t("products.price"),
            dataIndex: "price",
        },
        {
            title: t("products.stock_quantity"),
            dataIndex: "stock_quantity",
        },
        {
            title: t("products.initial_stock_quantity"),
            dataIndex: "initial_stock_quantity",
        },
        {
            title: t("products.category"),
            dataIndex: "category",
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
            key: "description",
            value: t("common.description"),
        },
    ];

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
        hashableColumns,
        getPrefetchData,
        categories
    };
};

export default fields;
