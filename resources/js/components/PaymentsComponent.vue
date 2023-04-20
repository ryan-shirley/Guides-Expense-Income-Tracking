<template>
    <div>
        <div class="list-group list-group-flush">
            <div v-for="payment in payments.data" v-if="payments != null && payments.data != null">
                <a :href="'/admin/payments/' + payment._id + '/edit'" class="list-group-item list-group-item-action clearfix py-2 border-bottom">
                    <div class="container">
                        <div class="row align-items-center row justify-content-start">
                            <div class="col col-lg-3">
                                <h4 class="mb-1">{{ payment.title }}</h4>
                                <small class="text-muted mb-0">{{ payment.user.name }}</small><br />
                                <small class="text-muted mb-0">{{ payment.keyID }} | {{ payment.code_name }}</small>
                            </div>
                            <div class="col col-lg-2 text-center">
                                <p class="mb-0">{{ formatDate(payment.purchase_date) }}</p>
                                <small class="text-muted">(Purchased)</small>
                            </div>
                            <div class="col col-lg-2 text-center">
                                <p class="mb-0 font-weight-bold">€{{ payment.amount }}</p>
                                <small class="text-muted">{{ payment.is_cash ? "Cash": "Other" }} ({{ payment.guide_money ? "Guide" : "Personal" }})</small>
                            </div>
                            <div class="col col-lg-2">
                                <img :src="payment.receipt_url" onerror="this.src='https://placehold.co/600x600?text=No+Receipt'" class="img-thumbnail mb-2" style="max-height: 70px;" />
                            </div>
                            <div class="col col-lg-3 text-right">
                                <button class="btn btn-danger btn-sm mb-2" @click="deletePayment(payment._id)" v-bind:disabled="processingActionRequest"><i class="fas fa-times"></i></button>
                                <button v-if="!payment.approved" class="btn btn-success btn-sm mb-2" @click="approve(payment._id)" v-bind:disabled="processingActionRequest">Approve</button>
                                <button v-if="!payment.paid_back" class="btn btn-info btn-sm mb-2" @click="markPaidBack(payment._id)" v-bind:disabled="processingActionRequest">Mark Paid</button>
                                <button v-if="!payment.receipt_received" class="btn btn-info btn-sm mb-2" @click="markReceivedReceipt(payment._id)" v-bind:disabled="processingActionRequest">Received Receipt</button>
                            </div>
                            <div class="col-12">
                                <span v-if="!payment.paid_back" class="badge badge-pill badge-danger">
                                    Not Paid Back
                                </span>
                                <span v-if="!payment.approved" class="badge badge-pill badge-danger">
                                    Not Approved
                                </span>
                                <span v-if="!payment.receipt_received" class="badge badge-pill badge-danger">
                                    Missing Receipt
                                </span>
                            </div>
                        </div>
                    </div>

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
            payments: null,
            processingActionRequest: false
        }
    },
    mounted () {
        let app = this;
        axios
            .get(`/api/payments?api_token=${app.$attrs.api_token}`)
            .then(response => {
                this.payments = response.data;
            });
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
        },
        async approve(id){
            this.handleClickInit();

            let response = await axios.post(`/api/payments/${id}/approve?api_token=${this.$attrs.api_token}`)
            console.log(response.data);

            const index = this.getPaymentIndex(id)
            const updatedPayment = this.payments.data[index];
            updatedPayment.approved = true;
            this.payments.data[index] = updatedPayment;

            this.handleClickFinish();
        },
        async markPaidBack(id){
            this.handleClickInit();
            let response = await axios.post(`/api/payments/${id}/paid-back?api_token=${this.$attrs.api_token}`)
            console.log(response.data);

            const index = this.getPaymentIndex(id)
            const updatedPayment = this.payments.data[index];
            updatedPayment.paid_back = true;
            this.payments.data[index] = updatedPayment;

            this.handleClickFinish();
        },
        async markReceivedReceipt(id){
            this.handleClickInit();
            let response = await axios.post(`/api/payments/${id}/received-receipt?api_token=${this.$attrs.api_token}`)
            console.log(response.data);

            const index = this.getPaymentIndex(id)
            const updatedPayment = this.payments.data[index];
            updatedPayment.receipt_received = true;
            this.payments.data[index] = updatedPayment;

            this.handleClickFinish();
        },
        async deletePayment(id){
            this.handleClickInit();

            let result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            })

            if (result.value === true) {
                let response = await axios.delete(`/api/payments/${id}?api_token=${this.$attrs.api_token}`)
                console.log(response.data);

                const index = this.getPaymentIndex(id)
                if (index > -1) { // only splice array when item is found
                    this.payments.data.splice(index, 1); // 2nd parameter means remove one item only
                }
            }

            this.handleClickFinish();
        },
        getPaymentIndex(id){
            var foundIndex = this.payments.data.findIndex(x => x._id == id);
            return foundIndex;
        },
        handleClickInit(){
            event.preventDefault();
            this.processingActionRequest = true;
        },
        handleClickFinish(){
            this.processingActionRequest = false;
        }
    }
}
</script>

<style scoped>

</style>
