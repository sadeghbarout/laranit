<template>
    <td class='product-name' v-for="column in selectedColumnsData">
        <!-- custom -->
        <template v-if="$slots[column.id]">
            <slot :name="column.id" :column="column" :item="item"></slot>
        </template>

        <!-- normal -->
        <template v-else>
            <router-link :to='column.noLink!==1 ? to : null'
                :class="column.hasBadge==1 ? 'text-'+item[column.id+'_color'] : ''">

                <!-- color dot -->
                <i v-if="column.hasBadge==1" :class="['fa fa-circle font-small-3 mr-50 text-'+item[column.id+'_color']]"></i>

                <!-- value -->
                <img v-if="column.isImage==1" :src="getValue(column, column.val)">
                <template v-else>
                    {{ getValue(column, column.val) }}
                </template>
            </router-link>
        </template>
    </td>
</template>

<script>
export default {
    props: {
        selectedColumnsData: Array,
        to: String,
        item: Object,
    },
    data() {
        return {
        }
    },
    methods: {
        getValue(column,key){

            // ex: name|national_id
            if(key.includes('|')){
                let keys=key.split('|')
                var value=column.format;
                keys.forEach((k,i)=>{
                    if(value!=undefined){
                        value=value.replace('$'+(i+1),this.getValue(column,k))
                    }
                })

            // ex: user.name
            }else if(key.includes('.')){
                let keys=key.split('.')
                var value=this.item;
                keys.forEach(k=>{
                    if(value!=undefined)
                        value=value[k]
                })
            }else{
                var value=this.item;
                if(value!=undefined)
                    value=value[key]
            }


            return value
        }
    }
}
</script>

<!--
json attributes:


"id": "user_id",
"label": "نام",
"val": "name",  // show item.name
"val": "user.name",     // show item.user.name
"def": 1,   // show as default columns   (default :0)
"noLink": 1,    //  don't show as link  (default:0)
"sortable": 0   // ability to sort (default :1)
"hasBadge": 1   // show badge and color dot (default :0)

"val": "target_id|target_type_text",    // for formatting
"format": "$1 - $2"     // for special format

-->

<!--
for a custom text for a column , add a template as slot on component with name of column.id

ex: custom 'name' column
<custom-column-td ...>
    <template #name="{item}">

    </template>
</custom-column-td>
-->
