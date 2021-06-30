<template>
    <div>
        <div class="col-12">
            <fieldset class="form-group row">
                <div v-if="title != undefined" :class="'col-md-'+labelColSize">
                    <span v-text="title"></span>
                </div>
                <div :class="title != undefined? 'col-md-'+textareaColSize: 'col-md-12' ">
                    <!-- <i class="fa fa-times text-danger cursor-pointer" style="position:absolute;left:16px"  @click="clearInput($event)"></i> -->
                    <textarea class="form-control" rows="7" cols="80" :id="id" :name="name" @input="$emit('input', $event.target.value)" v-html="val" :placeholder="placeholder !== undefined ? placeholder : ''"></textarea>
                    <div v-if="copy!=undefined && id!=undefined" class="d-inline" style="position:absolute;left:-2px;top:0">
                        <i  class="fa fa-clone text-warning cursor-pointer" @click="copyField()"></i>
                        <input :id="'copyfield'+id" class="d-none">
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['title', 'val', 'id', 'name','labelSize','copy','placeholder'],
        model: {
            prop: 'val',
            event: 'input'
        },
        data(){
            return{
                inputValue : this.val,
                labelColSize : 4,
                textareaColSize : 8,
            }
        },
        methods: {
            clearInput(e){
                this.inputValue = '';
                $(e.target).next().val('');
                this.$emit('input', this.inputValue)
            },


            copyField(){
                var text = document.getElementById(this.id).innerHTML;
                var textarea = document.createElement("textarea");
                textarea.textContent = text;
                textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);
                alert2("فیلد مورد نظر در کلیپ بورد کپی شد",'کپی شد','success');

            }
        },
        mounted() {
            if(this.labelSize !== undefined){
                this.labelColSize = this.labelSize;
                this.textareaColSize = 12-this.labelSize;
            }
        },

    }
</script>
