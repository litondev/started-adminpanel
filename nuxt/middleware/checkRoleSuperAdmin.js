export default function ({ store,redirect,route}) {
  let user = store.state.auth.user;

  if(user.parent_id){
    return redirect('/')
  }
}
