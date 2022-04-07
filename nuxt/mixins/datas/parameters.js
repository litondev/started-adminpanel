export default {
	data(){
		return {
			parameters : {
          params : {
            soft_deleted : '',
            search      : '',
            order       : 'id',
            sort        : 'desc',
            all         : '',
            per_page    : 20,
            page        : 1,

            start_date  : '',
            end_date    : ''
          },
          default_params : {
            soft_deleted : '',
            search      : '',
            order       : 'id',
            sort        : 'desc',
            all         : '',
            per_page    : 20,
            page        : 1,

            start_date  : '',
            end_date    : ''
          }
      },
		}
	}
}