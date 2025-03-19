import { computed } from "vue";
import moment from "moment";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import { forEach, includes } from "lodash-es";
import { checkUserPermission } from "../scripts/functions";
import dayjs from 'dayjs';

import duration from 'dayjs/plugin/duration';

moment.suppressDeprecationWarnings = true;
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);
dayjs.extend(relativeTime);
dayjs.extend(utc);
dayjs.extend(timezone);
dayjs.extend(duration);

const common = () => {
    const store = useStore();
    const { t } = useI18n();
    const appType = window.config.app_type;
    const downloadLangCsvUrl = window.config.download_lang_csv_url;
    const menuCollapsed = computed(() => store.state.auth.menuCollapsed);
    const cssSettings = computed(() => store.state.auth.cssSettings);
    const appModules = computed(() => store.state.auth.activeModules);
    const visibleSubscriptionModules = computed(() => store.state.auth.visibleSubscriptionModules);
    const globalSetting = computed(() => store.state.auth.globalSetting);
    const appSetting = computed(() => store.state.auth.appSetting);
    const addMenus = computed(() => store.state.auth.addMenus);
    const selectedLang = computed(() => store.state.auth.lang);
    const user = computed(() => store.state.auth.user);

    const alertStatusTypes = {
        escalated: "warning",
        standard: "info",
        urgent: "error",
    }

    const statusColors = {
        enabled: "success",
        disabled: "error",
    };

    const userStatus = [
        {
            key: "enabled",
            value: t("common.enabled")
        },
        {
            key: "disabled",
            value: t("common.disabled")
        }
    ];

    const disabledDate = (current) => {
        // Can not select days before today and today
        return current && current > moment().endOf("day");
    };

    const dayjsObject = (date) => {
        if (date == undefined) {
            return dayjs()
                .tz(appSetting.value.timezone);
        } else {
            return dayjs(date)
                .tz(appSetting.value.timezone);
        }
    }

    // Example: 2024-11-22 -> 11/22/2024
    const formatDate = (date) => {
        if (date == undefined) {
            return dayjs()
                .tz(appSetting.value.timezone)
                .format(
                    `${appSetting.value.date_format}`
                )
        } else {
            return dayjs(date)
                .tz(appSetting.value.timezone)
                .format(
                    `${appSetting.value.date_format}`
                )
        }
    }

    // Example: 2024-11-22 -> Nov 22, 2024
    const prettyFormatDate = (date) => {
        if(date == undefined) {
            return dayjs(date).format('MMM D, YYYY');
        } else {
            return dayjs(date).format('MMM D, YYYY');
        }
    }

    // Example: 2024-11-22 14:30 -> 11/22/2024 14:30
    const formatDateTime = (dateTime) => {
        if (dateTime == undefined) {
            return dayjs()
                .tz(appSetting.value.timezone)
                .format(
                    `${appSetting.value.date_format} ${appSetting.value.time_format}`
                )
        } else {
            return dayjs(dateTime)
                .tz(appSetting.value.timezone)
                .format(
                    `${appSetting.value.date_format} ${appSetting.value.time_format}`
                )
        }
    }

    // Example: 3661 -> 1 H, 1 M, 1 S
    const formatTimeDuration = (seconds) => {
        if (seconds == 0) {
            return 0;
        } else {
            var totalSeconds = parseInt(seconds);
            var hours = Math.floor(totalSeconds / 3600);
            totalSeconds %= 3600;
            var minutes = Math.floor(totalSeconds / 60);
            var remainingSeconds = totalSeconds % 60;

            var secondsString = "";
            if (hours > 0) {
                secondsString += `${hours} H,`;
            }
            if (minutes > 0) {
                secondsString += ` ${minutes} M,`;
            }
            if (remainingSeconds > 0) {
                secondsString = secondsString.trim() + ` ${remainingSeconds} S`;
            }

            return secondsString.trim();
        }
    }

    // Example: 1637587200 -> 11/22/2024 14:30
    const formatUnixDateTime = (unixTimestamp) => {
        if(unixTimestamp === null) {
            return t("docs.unknown_date");
        }
        return dayjs.unix(unixTimestamp)
              .format(`${appSetting.value.date_format} ${appSetting.value.time_format}`);
    };

    // Example: 2024-11-22 14:30 -> 14:30
    const formatTime = (dateTime) => {
        return dayjs(dateTime).tz(appSetting.value.timezone).format(appSetting.value.time_format);
    }

    // Example: 2024-11-22 -> Today or Monday or 11/22/2024
    const formatDayOrDate = (date) => {
        const now = dayjs();
        const givenDate = dayjs(date);
        const duration = now.diff(givenDate, 'day');
    
        if(duration <= 1 && now.date() === givenDate.date()) {
            return t('common.today'); // Display "Today"
        }
        if (duration <= 7) {
            return givenDate.format('dddd'); // Display the day of the week
        } else {
            return givenDate.format(appSetting.value.date_format); // Display the full date
        }
    };

    // Example: 2024-11-22 14:30 -> 14:30 or Yesterday or 11/22/2024
    const formatRelativeTime = (dateTime) => {
        const durationInHours = dayjs().diff(dayjs(dateTime), 'hour');
        const durationInDays = dayjs().diff(dayjs(dateTime), 'day');
    
        if (durationInHours < 24) {
            return dayjs(dateTime)
                .tz(appSetting.value.timezone)
                .format(appSetting.value.time_format); // Show time
        } else if (durationInDays < 2) {
            return "Yesterday"; // Show "Yesterday"
        } else {
            return dayjs(dateTime)
                .tz(appSetting.value.timezone)
                .format(appSetting.value.date_format); // Show date
        }
    };

    // Example: 2024-11-22 14:30 -> 2024-11-22
    const formatMsgDate = (dateTime) => {
        return dayjs().tz(appSetting.value.timezone).format('YYYY-MM-DD')
    };

    // check if date is greater than today
    const isDateAfterBeforeOrToday = (date) => {
        if (dayjs(date).isSame(dayjs(), 'day')) {
            return 'today';
        } else if (dayjs(date).isAfter(dayjs(), 'day')) {
            return 'after';
        } else if (dayjs(date).isBefore(dayjs(), 'day')) {
            return 'before';
        }
    }

    const formatAmount = (amount) => {
        return parseFloat(parseFloat(amount).toFixed(2));
    };

    const formatSeconds = (seconds) => {
        const timeDuration = dayjs.duration(seconds, 'seconds');
        const hours = timeDuration.hours();
        const minutes = timeDuration.minutes();
      
        return `${hours ? `${hours}h ` : ''}${minutes ? `${minutes}m` : ''}`.trim();
    }

    const formatAmountCurrency = (amount) => {
        const newAmount = parseFloat(Math.abs(amount)).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        if (appSetting.value.currency.position == "front") {
            var newAmountString = `${appSetting.value.currency.symbol}${newAmount}`;
        } else {
            var newAmountString = `${newAmount}${appSetting.value.currency.symbol}`;
        }

        return amount < 0 ? `- ${newAmountString}` : newAmountString;
    };

    const formatAmountUsingCurrencyObject = (amount, currency) => {
        const newAmount = parseFloat(Math.abs(amount)).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        if (currency.position == "front") {
            var newAmountString = `${currency.symbol}${newAmount}`;
        } else {
            var newAmountString = `${newAmount}${currency.symbol}`;
        }

        return amount < 0 ? `- ${newAmountString}` : newAmountString;
    };

    const calculateFilterString = (filters) => {
        var filterString = "";

        forEach(filters, (filterValue, filterKey) => {
            if (filterValue != undefined) {
                filterString += `${filterKey} eq "${filterValue}" and `;
            }
        })

        if (filterString.length > 0) {
            filterString = filterString.substring(0, filterString.length - 4);
        }

        return filterString;
    }

    const checkPermission = (permissionName) => checkUserPermission(permissionName, user.value);

    const updatePageTitle = (pageName) => {
        store.commit("auth/updatePageTitle", t(`menu.${pageName}`));
    }

    const permsArray = computed(() => {
        const permsArrayList = user && user.value && user.value.role ? [user.value.role.name] : [];

        if (user && user.value && user.value.role) {
            forEach(user.value.role.perms, (permission) => {
                permsArrayList.push(permission.name);
            });
        }

        return permsArrayList;
    });

    const slugify = (text) => {
        return text
            .toString()
            .toLowerCase()
            .replace(/\s+/g, "-") // Replace spaces with -
            .replace(/[^\w\-]+/g, "") // Remove all non-word chars
            .replace(/\-\-+/g, "-") // Replace multiple - with single -
            .replace(/^-+/, "") // Trim - from start of text
            .replace(/-+$/, ""); // Trim - from end of text
    };

    const convertStringToKey = (textString) => {
        return textString
            .toString()
            .toLowerCase()
            .replace(/\s+/g, "_") // Replace spaces with -
            .replace(/[^\w\-]+/g, "") // Remove all non-word chars
            .replace(/\-\-+/g, "_") // Replace multiple - with single -
            .replace(/^-+/, "") // Trim - from start of text
            .replace(/-+$/, ""); // Trim - from end of text
    }

    const convertToPositive = (amount) => {
        return amount < 0 ? amount * -1 : amount;
    }

    const willSubscriptionModuleVisible = (moduleName) => {
        if (appType == 'non-saas') {
            return true;
        }
    }

    const optionLanguages = [
        {
            value: "English",
            label: "English",
        },
        {
            value: "Spanish",
            label: "Spanish",
        }
    ];

    const exportItems = (res, format, name, dates) => {
        let type = "";
        switch (format) {
            case 'xlsx':
                type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            break;
            case 'csv':
                type = 'text/csv';
            break;
            case 'pdf':
                type = 'application/pdf';
            break;
        }

        const blob = new Blob([res], { type: type });
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        const fileName = dates.length > 0 ? `${name}_${formatDate(dates[0])}_to_${formatDate(dates[1])}.${format}` : `${name}.${format}`;
        link.download = fileName;
        link.click();
    }

    return {
        menuCollapsed,
        appSetting,
        appType,
        addMenus,
        selectedLang,
        user,
        checkPermission,
        permsArray,
        statusColors,
        userStatus,
        alertStatusTypes,

        disabledDate,
        formatAmount,
        formatAmountCurrency,
        formatAmountUsingCurrencyObject,
        convertToPositive,

        calculateFilterString,
        updatePageTitle,

        downloadLangCsvUrl,
        appModules,
        dayjs,
        formatDate,
        formatDateTime,
        dayjsObject,
        slugify,
        convertStringToKey,
        formatUnixDateTime,
        formatTime,
        isDateAfterBeforeOrToday,

        cssSettings,
        globalSetting,

        willSubscriptionModuleVisible,
        visibleSubscriptionModules,
        formatTimeDuration,
        formatDayOrDate,
        formatRelativeTime,

        optionLanguages,
        prettyFormatDate,
        formatMsgDate,
        formatSeconds,

        exportItems,
    };
}

export default common;
