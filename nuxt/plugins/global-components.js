import Vue from 'vue'
import HeaderSection from '~/components/section/HeaderSection';
import ReportSection from '~/components/section/ReportSection';
import FilterSection from '~/components/section/FilterSection';
import TableHeaderSection from '~/components/section/TableHeaderSection';
import TableDataLoadingSection from '~/components/section/TableDataLoadingSection';
import TableDataNotFoundSection from '~/components/section/TableDataNotFoundSection';
import ListOptionSection from "~/components/section/ListOptionSection";
import PaginationSection from "~/components/section/PaginationSection";
import ModalHeaderSection from "~/components/section/ModalHeaderSection";
import ModalFooterSection from "~/components/section/ModalFooterSection";

Vue.component("header-section",HeaderSection);
Vue.component("report-section",ReportSection);
Vue.component("filter-section",FilterSection);
Vue.component("table-header-section",TableHeaderSection);
Vue.component('table-data-loading-section',TableDataLoadingSection);
Vue.component('table-data-not-found-section',TableDataNotFoundSection);
Vue.component('list-option-section',ListOptionSection);
Vue.component("pagination-section",PaginationSection);
Vue.component("modal-header-section",ModalHeaderSection);
Vue.component("modal-footer-section",ModalFooterSection);
