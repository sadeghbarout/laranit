<template>
    <div class="card">
        <div class="card-header" style="padding:5px">
            <h4 class="card-title">فیلترها</h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a @click="collapseTogller()"><i class="feather icon-chevron-down"></i></a></li>
                </ul>
            </div>
        </div>
        <div :class="['card-content collapse', contentDisplay? 'show': '']">
            <div class="card-body p-0 pt-1">
                <div class="users-list-filter">
                <form @submit.prevent="fetchDataChild(1)" id="filterForm" class="m-0">
                    <div class="row">
                        <slot></slot>
                    </div>

                    <!-- <button class="btn btn-primary ml-1">
                        <i class="fa fa-search"></i>
                    </button> -->
                </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [],
        model: {
            prop: 'val',
            event: 'input',
        },
        data(){
            return {
                contentDisplay : true,
            }
        },
        methods:{
            fetchDataChild(p){
                this.$parent.fetchData(p);
            },

            collapseTogller(){
                this.contentDisplay = !this.contentDisplay;
            },
        },

        mounted(){
        }
    }
</script>
