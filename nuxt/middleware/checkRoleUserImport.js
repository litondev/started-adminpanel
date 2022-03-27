export default function ({ store,redirect,route}) {
    let user = store.state.auth.user;

    if(!user.parent_id){
        return ;
    }

    let route_name = route.name.split("-");

    let main_role = user.group_role.roles.find(item => item.name == route_name[1]);

    if(!main_role){
        return redirect("/");
    }

    if(JSON.parse(main_role.pivot.operators).includes("all")){
        return ;
    }
        
    if(            
      !(
        JSON.parse(main_role.pivot.operators).includes("import")
        &&
        JSON.parse(main_role.pivot.operators).includes("import-template")            
      )
    ){
      return redirect("/");  
    }    
}
