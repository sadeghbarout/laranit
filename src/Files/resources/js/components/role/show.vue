<template>
    <div id="data-list-view" class="data-list-view-header">
        <section class="page-users-view">
            <div class="row">


                <!-- role info -->
                <div class="col-12">
                    <card-component title="نقش">
                        <div class="col-sm-12 p-1">
                            <form-label title="شناسه" :val="role.id"></form-label>
                            <form-label title="نام فارسی " :val="role.name"></form-label>
                            <form-label title="نام انگلیسی " :val="role.desc"></form-label>
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
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" :id="'permission'+permission.id"
                                                   :checked="permissionIds.indexOf(permission.id) != -1"
                                                   @click="permissionOperation(permission)">
                                            <label class="custom-control-label" :for="'permission'+permission.id">
                                            </label>
                                            <span class="switch-label" v-html="permission.desc"></span>
                                        </div>
                                    </div>
                                </div>


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
            }
        },

        methods: {



            permissionOperation(permission){
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
                axios.delete('/role/'+this.role.id)
                    .then(response => {
                        checkResponse(response.data);
                    });
            },

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
                    },true)
                });

        },
    }
</script>
