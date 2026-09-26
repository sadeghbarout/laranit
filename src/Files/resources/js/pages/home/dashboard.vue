<template>
    <div class="container-fluid" style="padding-left: 0 !important;padding-right: 0 !important;">
        <div class="row">

            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-2">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="fas fa-crown text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ items.adminsCount }}</h2>
                        <p class="mb-0">تعداد کل مدیران</p>
                    </div>
                    <div class="card-content">
                        <div id="total-user-chart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-2">
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="fas fa-stop text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">0</h2>
                        <p class="mb-0">لورم ایپسوم</p>
                    </div>
                    <div class="card-content">
                        <div id="total-user-chart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-2">
                        <div class="avatar bg-rgba-warning p-50 m-0">
                            <div class="avatar-content">
                                <i class="fas fa-stop text-warning font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">0</h2>
                        <p class="mb-0">لورم ایپسوم</p>
                    </div>
                    <div class="card-content">
                        <div id="total-request-chart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-2">
                        <div class="avatar bg-rgba-danger p-50 m-0">
                            <div class="avatar-content">
                                <i class="fas fa-stop text-danger font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">0</h2>
                        <p class="mb-0">لورم ایپسوم</p>
                    </div>
                    <div class="card-content">
                        <div id="total-request-open-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: {
                adminsCount: 0,
            },
            input: '',
            monitors: [],
            servers: null,
        }
    },
    methods: {
        fetchData() {
            axios.get('/dashboard/info')
                .then(response => {
                    checkResponse(response.data, (res) => {
                        this.items = res.items
                        this.monitors = res.monitors
                        this.servers = res.servers
                    }, true)
                })
        },
    },
    mounted() {
        this.fetchData()
    },
    watch: {}
}
</script>
