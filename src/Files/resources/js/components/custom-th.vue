<template>
    <th @mouseenter="hoverElem=true" @mouseleave="hoverElem=false" class="position-relative">
        <div class="w-100 d-flex justify-content-between align-items-center">
            <div  @click="changeSort" class="d-flex align-items-center cursor-pointer" style="gap: 4px;padding-left: 4px">
                <span>{{label}}</span>

                <div v-if="sortOptions && sortable">
                    <i v-if="sortOptions.sort !== name" class="fas fa-sort" :class="{
                        'text-primary': sortOptions.sort === name,
                        'item-hidden ': !hoverElem,
                        'item-visible ': hoverElem
                    }"></i>
                    <template v-else>
                        <i v-if="sortOptions.sortType === 'desc'" class="fas fa-sort-down text-primary"></i>
                        <i v-else-if="sortOptions.sortType === 'asc'" class="fas fa-sort-up text-primary"></i>
                    </template>
                </div>
            </div>

            <div>
                <div class="dropdown" style="position: relative;">
                    <div class="dropdown-menu dropdown-menu-right"
                         style="padding: 16px;width: 296px;"
                         aria-labelledby="dropdownMenuButton"
                         @click.stop>

                        <h6>فیلتر {{label}}</h6>
                        <form-select :options="filtersList" v-model="operation1"/>
                        <form-date v-if="filterType === 'date'" v-model="value1" :disabled="disabledOperation1"/>
                        <form-inputs v-else-if="filterType === 'text'" v-model="value1"  :disabled="disabledOperation1"/>
                        <form-select v-else-if="optionsList" v-model="value1" :options="optionsList" :disabled="disabledOperation1"/>

                        <form-select :options="operationList" v-model="logic" style="width: 100px;"/>

                        <form-select :options="filtersList" v-model="operation2"/>
                        <form-date  v-if="filterType === 'date'" v-model="value2" :disabled="disabledOperation2"/>
                        <form-inputs v-else-if="filterType==='text'" v-model="value2" :disabled="disabledOperation2"/>
                        <form-select v-else-if="optionsList" v-model="value2" :options="optionsList" :disabled="disabledOperation2"/>

                        <div class="d-flex justify-content-between">
                            <button @click="removeFilter" type="button" class="btn btn-sm btn-danger">حذف</button>
                            <button @click="addFilter" type="button" class="btn btn-sm btn-primary">افزودن</button>
                        </div>
                    </div>
                </div>


                <div v-if="filterType" type="button" style="padding: 6px;" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter" :class="{'text-primary': hasFilter()}"></i>
                </div>
            </div>
        </div>
    </th>
</template>
<script>
export default {
    props: {
        filters: {
            default: null,
        },
        label: {
            default: null,
            type: String
        },
        name: {
            default: null,
            type: String
        },
        filterType: {
            default: null,
            type: String
        },
        sortOptions: {
            default: null,
            type: Object
        },
        sortable: {
            default: false,
            type: Boolean
        },
        val: {
            default: null,
            type: String
        },
    },
    data() {
        return {
            hoverElem: false,
            filtersList:[],
            operationList:[],

            optionsList: null,

            operation1: 'equals',
            value1: '',
            logic: 'and',
            operation2: 'equals',
            value2: '',

            canFetchData: true,

            disabledOperation1: false,
            disabledOperation2: false,
            disabledOptions: [
                'is_null',
                'is_empty',
                'is_not_null',
                'is_not_empty',
            ],
        };
    },
    watch: {
        operation1(){
            if(this.disabledOptions.includes(this.operation1)){
                this.disabledOperation1 = true;
                this.value1 = '';
            }else{
                this.disabledOperation1 = false;
            }
        },
        operation2(){
            if(this.disabledOptions.includes(this.operation2)){
                this.disabledOperation2 = true;
                this.value2 = '';
            }else{
                this.disabledOperation2 = false;
            }
        }
    },
    methods: {
        addFilter(){
            this.removeFilter();

            if(this.disabledOptions.includes(this.operation1) || this.value1.trim().length !== 0 || this.value2.trim().length !== 0){
                this.filters.push({
                    name: this.name,
                    val: this.val,

                    operation1: this.operation1,
                    value1: this.value1,
                    operation2: this.operation2,
                    value2: this.value2,
                    logic: this.logic,
                });

                this.fetchData()
            }


            this.closeMenu();
        },
        removeFilter(){
            this.closeMenu();
            this.filters.map((item, index) => {
                if(item.name === this.name){
                    this.filters.splice(index, 1)
                    this.fetchData();
                }
            });
        },
        fetchData(){
            if(this.canFetchData){
                this.canFetchData = false;

                try {
                    this.$parent.$parent.page = 1;
                }catch (e) {
                    this.$parent.page = 1;
                }
                this.$nextTick(() => {
                    try {
                        this.$parent.$parent.fetchData();
                    }catch (e) {
                        this.$parent.fetchData();
                    }
                });

                setTimeout(() => {
                    this.canFetchData = true;
                }, 500)
            }
        },
        hasFilter(){
            return this.filters?.find(item => item.name === this.name)
        },
        closeMenu(){
            const dropdown = this.$el.querySelector('.dropdown');
            const dropdownMenu = dropdown.querySelector('.dropdown-menu');
            const toggle = this.$el.querySelector('#dropdownMenuButton');

            dropdown.classList.remove('show');
            dropdownMenu.classList.remove('show');
            toggle.setAttribute('aria-expanded', 'false');
        },
        changeSort(){
            if(!this.sortable){
                return;
            }

            if(this.sortOptions.sort !== this.name){
                this.sortOptions.sortType = 'desc';
            }else{
                if(this.sortOptions.sortType === 'desc'){
                    this.sortOptions.sortType = 'asc';
                }else{
                    this.sortOptions.sortType = 'desc';
                }
            }

            this.sortOptions.sort = this.name;

            this.$nextTick(() => {
                try {
                    this.$parent.$parent.fetchData();
                }catch (e) {
                    this.$parent.fetchData();
                }
            });
        }
    },
    mounted() {
        if(this.filterType && this.filterType !==' date' && this.filterType !==' text'){
            this.optionsList = Tools.utils(this.filterType, true);
        }

        this.filtersList = Tools.utils("filtersList");
        this.operationList = Tools.utils("operationList");
    }
}
</script>
<style scoped>
.filter-styles {
    left: 0 !important;
    top: 17px !important;
    font-size: 15px !important;
    cursor: pointer !important;
}

.icon-colors {
    color: #74787e;
}

.item-visible {
    visibility: visible;
}

.item-hidden {
    visibility: hidden;
}

.dropdown .dropdown-menu.dropdown-menu-right::before, .dropup {
    display: none;
}
</style>
