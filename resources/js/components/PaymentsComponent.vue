<template>
    <div>
        <div class="card-body py-0 row">
        </div>
        <div class="list-group list-group-flush">
            <div v-for="payment in payments">
                <a href="#" class="list-group-item list-group-item-action py-2">
                    <div class="d-flex w-100 justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ payment.title }}</h4>
                            <p class="text-muted mb-0">{{ payment.user_id }}</p>
                            <p class="text-muted mb-0">{{ payment.keyID }}</p>
                        </div>
                        <div class="text-center">
                            <p class="mb-0">{{ formatDate(payment.purchase_date) }}</p>
                            <small class="text-muted">(Purchased)</small>
                        </div>
                        <div class="text-center">
                            <p class="mb-0 font-weight-bold">€{{ payment.amount }}</p>
                            <small class="text-muted">{{ payment.is_cash ? "Cash": "Other" }} ({{ payment.guide_money ? "Guide" : "Personal" }})</small>
                        </div>
                        <div>
                            <p class="mb-0 font-weight-bold">Image...</p>
                        </div>
                        <div>
                            <button class="btn btn-warning btn-sm"><i class="far fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                            <button v-if="!payment.approved" class="btn btn-success btn-sm">Approve</button>
                            <button v-if="!payment.paid_back" class="btn btn-info btn-sm">Mark Paid</button>
                            <button v-if="!payment.receipt_received" class="btn btn-info btn-sm">Received Receipt</button>
                        </div>
                    </div>
                    <span v-if="!payment.paid_back" class="badge badge-pill badge-danger">
                        Not Paid Back
                    </span>
                    <span v-if="!payment.approved" class="badge badge-pill badge-danger">
                        Not Approved
                    </span>
                    <span v-if="!payment.receipt_received" class="badge badge-pill badge-danger">
                        Missing Receipt
                    </span>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentsComponent",
    data () {
        return {
            payments: null
        }
    },
    mounted () {
        let app = this;
        axios
            .get(`/api/payments?api_token=${app.$attrs.api_token}`)
            .then(response => (this.payments = response.data.data));
    },
    methods: {
        formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }
    }
}
</script>

<style scoped>

</style>
