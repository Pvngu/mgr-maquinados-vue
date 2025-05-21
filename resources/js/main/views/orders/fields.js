import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "orders?fields=id,xid,client_id,order_date,total_amount,user_id";
    const addEditUrl = "orders";
    const { t } = useI18n();
    const hashableColumns = ["client_id", "user_id"];

    const initData = {
        client_id: undefined,
        order_date: "",
        total_amount: "",
        user_id: undefined,
    };

    
    const customers = ref([]);
    const users = ref([]);

    const getPrefetchData = () => {
        const clientsPromise = axiosAdmin.get("clients?fields=id,xid,name&limit=1000");
        const usersPromise = axiosAdmin.get("users?fields=id,xid,name&limit=1000");
        return Promise.all([clientsPromise, usersPromise]).then(([clientsResponse, usersResponse]) => {
            customers.value = clientsResponse.data;
            users.value = usersResponse.data;
        });
    };

    const columns = [
        {
            title: t("orders.client_id"),
            dataIndex: "client_id",
        },
        {
            title: t("orders.order_date"),
            dataIndex: "order_date",
        },
        {
            title: t("orders.total_amount"),
            dataIndex: "total_amount",
        },
        {
            title: t("orders.user_id"),
            dataIndex: "user_id",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "client_id",
            value: t("common.client_id"),
        },
        {
            key: "order_date",
            value: t("orders.order_date"),
        },
        {
            key: "total_amount",
            value: t("orders.total_amount"),
        },
        {
            key: "user_id",
            value: t("orders.user_id"),
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
        customers,
        users,
    };
};

export default fields;
