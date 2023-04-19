<template>
    <div>
        <div class="card-body py-0 row">
            <div class="col">
                <div class="form-group">
                    <p>Start Date</p>
                </div>
                <!-- /.Start Date -->
            </div>
            <div class="col">
                <div class="form-group">
                    <p>End Date</p>
                </div>
                <!-- /.End Date -->
            </div>
        </div>
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">List group item heading</h5>
                    <small>3 days ago</small>
                </div>
                <p class="mb-1">Some placeholder content in a paragraph.</p>
                <small>And some small print.</small>
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">List group item heading</h5>
                    <small class="text-muted">3 days ago</small>
                </div>
                <p class="mb-1">Some placeholder content in a paragraph.</p>
                <small class="text-muted">And some muted small print.</small>
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">List group item heading</h5>
                    <small class="text-muted">3 days ago</small>
                </div>
                <p class="mb-1">Some placeholder content in a paragraph.</p>
                <small class="text-muted">And some muted small print.</small>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentsComponent",
    data () {
        return {
            info: null
        }
    },
    mounted () {
        let app = this;
        axios
            .get(`/api/payments?startDate=${app.startDate}&endDate=${app.endDate}&api_token=${app.$attrs.api_token}`)
            .then(response => (this.info = response));
    }
}
</script>

<style scoped>

</style>
