<template>
    <div>
        <btn-icon @click="openModal" title="مدیریت جدول">
            <i class="fas fa-columns"></i>
        </btn-icon>

        <modal ref="dataModal" title="انتخاب ستون های لیست" max-width="500px">
            <template v-if="isLoading">
                <div class="pb-2 pt-2 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="p-2 space-y-1">
                    <div v-for="column in allColumns" :key="column.id" class="flex items-center">
                        <div class="custom-control custom-switch custom-control-inline">
                            <input type="checkbox" class="custom-control-input" :id="`column-${column.id}`" :checked="isColumnSelected(column.id)" @change="toggleColumn(column.id, $event)">
                            <label class="custom-control-label" :for="`column-${column.id}`"></label>
                            <span class="switch-label" v-html="column.label"></span>
                        </div>
                    </div>
                </div>
            </template>

            <div class="px-2 pb-2 gap-2 row">
                <button class="col-6 btn btn-success" @click="saveColumns">ذخیره</button>
                <button class="col-3 btn btn-primary" @click="closeModal">بستن</button>
                <button class="col-3 btn btn-warning" @click="setDefaults">پیشفرض</button>
            </div>
        </modal>
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            required: true
        },
        selectedColumnIds: {
            type: Array,
            default: () => []
        }
    },

    data() {
        return {
            allColumns: [],
            isLoading: false,
            localSelectedColumns: []
        };
    },

    methods: {
        openModal() {
            this.loadColumns();
            this.$refs.dataModal.open();
        },

        closeModal() {
            this.$refs.dataModal.close();
        },

        loadColumns() {
            this.localSelectedColumns=[...this.selectedColumnIds]
            this.allColumns = window.listColumns[this.type]?.list || [];

            if (this.localSelectedColumns.length === 0) {
                this.setDefaultColumns();
            }
        },

        setDefaultColumns() {
            this.localSelectedColumns = this.allColumns
                .filter(column => column.def === 1)
                .map(column => column.id);
        },

        isColumnSelected(columnId) {
            return this.localSelectedColumns.includes(columnId);
        },

        toggleColumn(columnId, event) {
            if (event.target.checked) {
                this.localSelectedColumns.push(columnId);
            } else {
                this.localSelectedColumns = this.localSelectedColumns.filter(id => id !== columnId);
            }
        },
        async setDefaults() {
            this.localSelectedColumns=[];
            await this.saveColumns()
        },

        async saveColumns() {
            this.isLoading = true;

            try {
                const response = await axios.post('/admin/columns', {
                    columns: this.localSelectedColumns,
                    type: this.type
                });

                checkResponse(response.data, res => {
                    const selectedColumnsData = Tools.getSelectedColumnsData(this.type, this.localSelectedColumns);
                    this.$emit('update:modelValue', selectedColumnsData);
                    this.selectedColumnIds.splice(0, this.selectedColumnIds.length, ...this.localSelectedColumns);
                    this.closeModal();
                }, true);
            } finally {
                this.isLoading = false;
            }
        }
    }
};
</script>

<style scoped>
.switch-label {
    margin-right: 0.5rem;
}
</style>
