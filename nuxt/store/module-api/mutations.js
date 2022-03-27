export default {
    set_data : (state,payload) => {
      state.data = payload
    },
    set_error :(state,payload) => {
      state.error = payload
    },
    set_result :(state,payload) => {
      state.result = payload
    },
    set_code : (state,payload) => {
      state.code = payload;
    },
    set_lookup_roles : (state,payload) => {
      state.lookup_roles = payload;
    },
    set_lookup_group_roles : (state,payload) => {
      state.lookup_group_roles = payload;
    },
    set_lookup_sellers : (state,payload) => {
      state.lookup_sellers = payload;
    },
    set_lookup_customer_groups : (state,payload) => {
      state.lookup_customer_groups = payload;
    },
    set_lookup_suppliers : (state,payload) => {
      state.lookup_suppliers = payload;
    },
    set_lookup_product_spesifications : (state,payload) => {
      state.lookup_product_spesifications = payload;
    },
    set_lookup_units : (state,payload) => {
      state.lookup_units = payload;
    },
    set_looup_customer_groups : (state,payload) => {
      state.lookup_customer_groups = payload;
    },
    set_lookup_customers : (state,payload) => {
      state.lookup_customers = payload;
    },
    set_lookup_products : (state,payload) => {
      state.lookup_products = payload;
    },
    set_lookup_quotations : (state,payload) => {
      state.lookup_quotations = payload;
    },
    set_lookup_po_customers : (state,payload) => {
      state.lookup_po_customers = payload;
    },
    set_lookup_po_suppliers : (state,payload) => {
      state.lookup_po_suppliers = payload;
    },
    set_lookup_purchaseings : (state,payload) => {
      state.lookup_purchaseings = payload;
    },
    set_lookup_warehouses : (state,payload) => {
      state.lookup_warehouses = payload;
    }
  }
  