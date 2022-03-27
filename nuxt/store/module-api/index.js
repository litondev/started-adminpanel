import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const defaultState = {
  data                     : [],
  error                    : '',
  result                   : '',
  code                     : '',
  lookup_roles             : {
    data : []
  },
  lookup_group_roles       : {
    data : []
  },
  lookup_sellers           : {
    data : []
  },
  lookup_customer_groups           : {
    data : []
  },
  lookup_suppliers         : {
    data : []
  },
  lookup_customers         : {
    data : []
  },
  lookup_products           : {
    data : []
  },
  lookup_quotations         : {
    data : []
  },
  lookup_po_customers       : {
    data : []
  },
  lookup_po_suppliers       :  {
    data : []
  },
  lookup_purchaseings       : {
    data : []
  },
  lookup_warehouses         : {
    data : []
  },
  lookup_product_spesifications : [],
  lookup_units             : [],
  lookup_customer_groups   : [],
}

const state = defaultState;

export default {
  namespaced : true,
  state,
  actions,
  mutations,
  getters
}
