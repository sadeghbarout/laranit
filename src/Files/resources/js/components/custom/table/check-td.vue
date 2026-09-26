<template>
    <div>
        <div class='vs-checkbox-con vs-checkbox-primary' >
            <input type='checkbox' :class="header?'rowCheckbox':'headerCheckbox' " :checked="this.$parent.selectedIds?.indexOf(id) !== -1 ? true : false " value='false'  @click="select($event)">
            <span class='vs-checkbox vs-checkbox-sm'>
                <span class='vs-checkbox--check'>
                    <i class="fas fa-check text-white" style="font-size: 10px;"></i>
                </span>
            </span>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            id : [Number, String],
            header:{
                type:Boolean,
                default:false,
            }
        },

        methods:{
            select(e){
                if(this.header){

                    var isCheck =  $(e.target).is(':checked')
                    if(!isCheck){
                        this.$parent.selectedIds = [];
                        return;
                    }

                    this.$parent.items.forEach((item)=>{
                        this.$parent.selectedIds.push(item.id);
                    })
                } else{
                    if(this.$parent.selectedIds.indexOf(this.id) == -1)
                        this.$parent.selectedIds.push(this.id);
                    else
                        this.$parent.selectedIds.splice(this.$parent.selectedIds.indexOf(this.id), 1);

                }

            },
        },

        mounted() {

        },
    }
</script>
