<template>
    <div v-if="payments != null && payments.data != null">
        <div class="card-body py-0 row">
            <div class="col-3">
                <div class="form-group row align-items-center">
                    <label for="limit" class="col-md-auto col-form-label">Show</label>
                    <select class="col-3 custom-select custom-select-sm" v-model="limit" @change="reloadData">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <label class="col-md-auto col-form-label">entries</label>
                </div>
            </div>
            <!-- Add search field -->
            <div class="col-4">
                <div class="form-group row align-items-center">
                    <label for="search" class="col-md-auto col-form-label">Search:</label>
                    <div class="col">
                        <input type="text" 
                               class="form-control form-control-sm" 
                               v-model="search" 
                               @input="handleSearch"
                               placeholder="Search payments...">
                    </div>
                </div>
            </div>
            <!-- Add date range -->
            <div class="col-5">
                <div class="form-group row align-items-center">
                    <label class="col-auto col-form-label">Date:</label>
                    <div class="col">
                        <input type="date" 
                               class="form-control form-control-sm" 
                               v-model="dateFrom"
                               @change="handleDateChange">
                    </div>
                    <label class="col-auto col-form-label">to</label>
                    <div class="col">
                        <input type="date" 
                               class="form-control form-control-sm" 
                               v-model="dateTo"
                               @change="handleDateChange">
                    </div>
                </div>
            </div>
        </div>

        <div class="list-group list-group-flush">
            <div v-for="payment in payments.data">
                <a :href="'/admin/' + $attrs.year + '/payments/' + payment._id + '/edit'" class="list-group-item list-group-item-action clearfix py-2 border-bottom">
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

        <div class="card-body">
            <div class="d-flex w-100 justify-content-between">
                <small>Showing from {{ payments.from }} to {{ payments.to }} of {{ payments.total }} payments</small>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        <li class="page-item" v-if="payments.prev_page_url !== null" @click="page -= 1">
                            <a class="page-link"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <div v-for="pageNum in pageNumbers">
                            <li class="page-item" :class="payments.current_page === pageNum && 'active'"><a class="page-link" @click="page = pageNum">{{ pageNum }}</a></li>
                        </div>
                        <li class="page-item" v-if="payments.next_page_url !== null" @click="page += 1">
                            <a class="page-link"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentsComponent",
    data () {
        const currentYear = this.$attrs.year;
        return {
            payments: null,
            processingActionRequest: false,
            limit: 25,
            page: 1,
            pageNumbers: [1],
            search: '',
            searchTimeout: null,
            dateFrom: `${currentYear}-01-01`,
            dateTo: `${currentYear}-12-31`,
        }
    },
    mounted () {
        this.reloadData();
    },
    watch: {
        page: function (value) {
            this.reloadData();
        }
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

            return [day, month, year].join('-');
        },
        async approve(id){
            this.handleClickInit();

            try {
                let response = await axios.post(`/api/payments/${id}/approve?api_token=${this.$attrs.api_token}`)
                console.log(response.data);

                const index = this.getPaymentIndex(id)
                const updatedPayment = this.payments.data[index];
                updatedPayment.approved = true;
                this.payments.data[index] = updatedPayment;
            }
            catch (error) {
                this.displayError(error.message, error.response.data.message)
            }

            this.handleClickFinish();
        },
        async markPaidBack(id){
            this.handleClickInit();

            try {
                let response = await axios.post(`/api/payments/${id}/paid-back?api_token=${this.$attrs.api_token}`)
                console.log(response.data);

                const index = this.getPaymentIndex(id)
                const updatedPayment = this.payments.data[index];
                updatedPayment.paid_back = true;
                this.payments.data[index] = updatedPayment;
            }
            catch (error) {
                this.displayError(error.message, error.response.data.message)
            }

            this.handleClickFinish();
        },
        async markReceivedReceipt(id){
            this.handleClickInit();

            try {
                let response = await axios.post(`/api/payments/${id}/received-receipt?api_token=${this.$attrs.api_token}`)
                console.log(response.data);

                const index = this.getPaymentIndex(id)
                const updatedPayment = this.payments.data[index];
                updatedPayment.receipt_received = true;
                this.payments.data[index] = updatedPayment;
            }
            catch (error) {
                this.displayError(error.message, error.response.data.message)
            }

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
                try {
                    let response = await axios.delete(`/api/payments/${id}?api_token=${this.$attrs.api_token}`)
                    console.log(response.data);

                    const index = this.getPaymentIndex(id)
                    if (index > -1) { // only splice array when item is found
                        this.payments.data.splice(index, 1); // 2nd parameter means remove one item only
                    }
                }
                catch (error) {
                    this.displayError(error.message, error.response.data.message)
                }
            }

            this.handleClickFinish();
        },
        getPaymentIndex(id){
            let foundIndex = this.payments.data.findIndex(x => x._id == id);
            return foundIndex;
        },
        handleClickInit(){
            event.preventDefault();
            this.processingActionRequest = true;
        },
        handleClickFinish(){
            this.processingActionRequest = false;
        },
        displayError(title, message){
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                footer: 'I\'m sorry 🥺',
                timer: 4000,
                timerProgressBar: true,
            })
        },
        handleSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.page = 1; // Reset to first page when searching
                this.reloadData();
            }, 300); // Debounce for 300ms
        },
        handleDateChange() {
            this.page = 1; // Reset to first page when changing dates
            this.reloadData();
        },
        reloadData(){
            let app = this;
            let params = {
                api_token: app.$attrs.api_token,
                limit: app.limit,
                page: app.page,
                search: app.search
            };
            
            if (app.dateFrom) params.date_from = app.dateFrom;
            if (app.dateTo) params.date_to = app.dateTo;

            axios
                .get(`/api/payments/${this.$attrs.year}`, { params })
                .then(response => {
                    app.payments = response.data;

                    let currentPage = app.page;
                    let hasPreviousPage = app.payments.prev_page_url !== null;
                    let hasNextPage = app.payments.next_page_url !== null;

                    let nextPageNumbers = [currentPage];
                    if(hasPreviousPage) {
                        nextPageNumbers.push(currentPage - 1)
                    }
                    if(hasNextPage) {
                        nextPageNumbers.push(currentPage + 1)
                    }

                    this.pageNumbers = nextPageNumbers.sort();
                })
                .catch(error => {
                    app.displayError(error.message, error.response.data.message)
                });
        }
    }
}
</script>

<style scoped>

</style>
