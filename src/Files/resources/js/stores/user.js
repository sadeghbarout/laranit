import {reactive} from "vue";

const userStore = reactive({
    isAuth: false,
    username: null,
    image: null,
    permissions: [],
    uncompletedRequestCount: 0,

    async checkAuth() {
        const result = await axios.get('/init-admin');

        if(result.status===200){
            const user = result.data.user;
            if(user){
                this.isAuth=true;
                this.username=user.username;
                this.image=user.image;
                this.permissions=result.data.admin_permissions;
                this.uncompletedRequestCount=result.data.uncompleted_request_count;
            }else{
                this.isAuth=false;
            }
        }
    },
    stopLoading(){
        const elem = document.getElementById('adminLoading');
        if(elem){
            elem.remove();
        }
    }
})

export default userStore;
