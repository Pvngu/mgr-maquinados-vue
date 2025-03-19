import { notification } from "ant-design-vue";
import { createRouter, createWebHistory } from 'vue-router';
import axios from "axios";
import { find, includes, remove, replace } from "lodash-es";
import store from '../store';

import AuthRoutes from './auth';
import DashboardRoutes from './dashboard';
import UserRoutes from './users';
import MessagingRoutes from './messaging';
import FormRoutes from './forms';
import SettingRoutes from './settings';
import SetupAppRoutes from './setupApp';
import CategoriesRoutes from './categories';
import { checkUserPermission } from '../../common/scripts/functions';

const appType = window.config.app_type;
const allActiveModules = window.config.modules;

const isAdminCompanySetupCorrect = () => {
    var appSetting = store.state.auth.appSetting;

    if (appSetting.x_currency_id == null) {
        return false;
    }

    return true;
}

const isSuperAdminCompanySetupCorrect = () => {
    var appSetting = store.state.auth.appSetting;

    if (appSetting.x_currency_id == null || appSetting.white_label_completed == false) {
        return false;
    }

    return true;
}

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '',
            redirect: '/admin/login'
        },
        ...AuthRoutes,
        ...DashboardRoutes,
        ...UserRoutes,
        ...SettingRoutes,
        ...MessagingRoutes,
        ...FormRoutes,
        ...CategoriesRoutes,
    ],
    scrollBehavior: () => ({ left: 0, top: 0 }),
});

SetupAppRoutes.forEach(route => router.addRoute(route));

const checkLogFog = (to, from, next) => {
  var userType = window.config.app_type == 'non-saas' ? "admin" : "superadmin";
  const routeParts = to.name.split('.');
  if (routeParts.length > 0 && routeParts[0] == "admin") {
    if (to.meta.requireAuth && !store.getters['auth/isLoggedIn']) {
      store.dispatch("auth/logout");
      next({ 'name': "admin.login" });
    } else {
      if (to.meta.requireAuth && isAdminCompanySetupCorrect() == false && routeParts[1] != "setup_app") {
        next({ 'name': "admin.setup_app.index" });
      } else {
        if (to.meta.requireUnauth && store.getters["auth/isLoggedIn"]) {
          next({ 'name': "admin.dashboard.index" });
        } else {
          if (to.name == userType + ".settings.modules.index") {
            store.commit("auth/updateAppChecking", false);
            next();
          } else {
            var permission = to.meta.permission;
            if (routeParts[1] == "stock") {
              permission = replace(to.meta.permission(to), '-', '_');
            }
            if (!to.meta.permission || checkUserPermission(permission, store.state.auth.user)) {
              next();
            } else {
              next({ 'name': "admin.dashboard.index" });
            }
          }
        }
      }
    }
  } else if (routeParts.length > 0 && routeParts[0] == 'front') {
    if (to.meta.requireAuth && !store.getters["front/isLoggedIn"]) {
      store.dispatch("front/logout");
      next({ 'name': "front.homepage" });
    } else {
      next();
    }
  } else {
    next();
  }
};

router.beforeEach((to, from, next) => {
  var config = { 'modules': window.config.modules };
  if (to.meta && to.meta.appModule) {
    config.module = to.meta.appModule;
    if (!includes(allActiveModules, to.meta.appModule)) {
      next({ 'name': 'admin.login' });
    }
  }
  checkLogFog(to, from, next);
});

export default router
