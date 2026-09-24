<template>
    <div id="data-list-view" class="data-list-view-header">
        <section class="page-users-view">
            <div class="row">


                <!-- role info -->
                <div class="col-12">
                    <card-component title="نقش">
                        <custom-table>
                            <tbody>
                            <tr>
                                <td-label title="شناسه">{{ role.id }}</td-label>
                                <td-label title="نام">{{ role.desc }}</td-label>
                            </tr>
                            </tbody>
                        </custom-table>

                        <div class="col-sm-12 p-1">
                            <div class="d-flex justify-content-end">
                                <router-link v-if="adminHasPermission(PERM_ROLE_UPDATE)" :to="'/role/create/'+role.id" class="btn btn-outline-warning float-right">ویرایش</router-link>
                                <div style="padding: 0 2px;"></div>
                                <button v-if="adminHasPermission(PERM_ROLE_DESTROY)"  class="btn btn-outline-danger float-right" @click="deleteRole()">حذف</button>
                            </div>
                        </div>
                    </card-component>
                </div>
                <!-- / -->


                <!-- role roles -->
                <div class="col-12" v-if="adminHasPermission(PERM_ROLE_PERMISSION)">
                    <card-component title="دسترسی ها">
                        <div class="col-sm-12">
                            <div class="row mb-2" >

                                <div class="col-6 p-2" v-for="permissions in permissionGroups">
                                    <div  v-for="(permission,index) in permissions" :key="permission.id" style="padding-top: 4px;">
                                        <div class="custom-control custom-switch custom-control-inline d-flex align-items-center">
                                            <input type="checkbox" class="custom-control-input" :id="'permission'+permission.id"
                                                   :checked="permissionIds.indexOf(permission.id) != -1"
                                                   @click="permissionOperation(permission)">
                                            <label class="custom-control-label" :for="'permission'+permission.id">
                                            </label>
                                            <span @click="showPermissionData(permission.id)" class="switch-label text-primary cursor-pointer" v-html="permission.desc"></span>
                                        </div>
                                    </div>
                                </div>

                                <modal ref="permissionData" title="اطلاعات دسترسی" max-width="800px">
                                    <div class="p-1" style="height: 70vh;overflow: auto">
                                        <tab v-model="currentTab" :tabs="[
                                            {title: 'کارشناسان', name: 'admins', hsePermission: true},
                                            {title: 'نقش ها', name: 'roles', hsePermission: true},
                                        ]"/>

                                        <div v-if="currentTab === 'admins'">
                                            <div class="table-responsive table-list" style="min-height: 50px !important;">
                                                <table class="table data-list-view px-0">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center" style="width: 250px">شناسه</th>
                                                        <th class="text-center" style="width: 250px">نام</th>
                                                        <th class="text-center" style="width: 250px">نام کاربری</th>
                                                        <th v-if="adminHasPermission(PERM_ADMIN_LIST_SHOW)" class="text-center" style="width: 250px">عملیات</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="admin in admins">
                                                        <td  class="text-center">{{ admin.id }}</td>
                                                        <td  class="text-center">{{ admin.name }}</td>
                                                        <td  class="text-center">{{ admin.username }}</td>
                                                        <td v-if="adminHasPermission(PERM_ADMIN_LIST_SHOW)" class="text-center">
                                                            <router-link :to="`/admin/${admin.id}`" class="btn btn-warning btn-sm">مشاهده</router-link>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div v-if="currentTab === 'roles'">
                                            <div class="table-responsive table-list" style="min-height: 50px !important;">
                                                <table class="table data-list-view px-0">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center" style="width: 250px">شناسه</th>
                                                        <th class="text-center" style="width: 250px">نام</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="role in roles">
                                                        <td  class="text-center">{{ role.id }}</td>
                                                        <td  class="text-center">{{ role.desc }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </modal>

                            </div>
                        </div>
                    </card-component>
                </div>
                <!-- / -->

            </div>
        </section>
    </div>
</template>

<script>

export default {

    data(){
        return {
            role: {},
            permissionGroups: {},
            permissionIds: [],

            roles:[],
            admins:[],

            currentTab: 'admins'
        }
    },

    methods: {



        permissionOperation(permission){
            showLoading();
            axios.post('/role/permission', {
                role_id: this.role.id,
                permission_id: permission.id,
                operation: this.permissionIds.indexOf(permission.id) != -1 ? 'remove' : 'assign',
            })
                .then(response => {
                    checkResponse(response.data);
                });
        },

        deleteRole(){
            showLoading();
            axios.delete('/role/'+this.role.id)
                .then(response => {
                    checkResponse(response.data);
                });
        },

        showPermissionData(id){
            showLoading()
            this.currentTab === 'admins';
            axios.get(`/role/getPermissionData/${id}`)
                .then(response => {
                    checkResponse(response.data, (res) => {
                        this.roles = res.roles;
                        this.admins = res.admins;
                        this.$refs.permissionData.open();
                    },true)
                });
        }
    },

    mounted() {
        axios.get('/role/' + this.$route.params.id)
            .then(response => {
                checkResponse(response.data, () => {
                    this.role = response.data.item;
                    this.permissionGroups = response.data.permissions;
                    this.role.permissions.forEach((role) => {
                        this.permissionIds.push(role.id);
                    })
                }, true)
            });

    },
}
</script>
