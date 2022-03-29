import Vue from 'vue'
// import ReportSection from '~/components/section/ReportSection';
// import FilterSection from '~/components/section/FilterSection';
import TableDataLoadingSection from '~/components/global/TableDataLoadingSection';
import TableDataNotFoundSection from '~/components/global/TableDataNotFoundSection';
import ListOptionSection from "~/components/global/ListOptionSection";
import ListAllOptionSection from "~/components/global/ListAllOptionSection";
import PaginationSection from "~/components/global/PaginationSection";
import ThSortTableSection from "~/components/global/ThSortTableSection";

// Vue.component("report-section",ReportSection);
// Vue.component("filter-section",FilterSection);
Vue.component('table-data-loading-section',TableDataLoadingSection);
Vue.component('table-data-not-found-section',TableDataNotFoundSection);
Vue.component('list-option-section',ListOptionSection);
Vue.component('list-all-option-section',ListAllOptionSection);
Vue.component("pagination-section",PaginationSection);
Vue.component('th-sort-table-section',ThSortTableSection);