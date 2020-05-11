<template>
    <!-- Data List -->
    <div class="table-responsive">
        <div class="card-body py-0 row">
            <div class="col">
                <div class="form-group">
                    <label for="date">Start Date</label>
                    <input
                        type="date"
                        class="form-control"
                        v-model="startDate"
                        @change="reloadData"
                    />
                </div>
                <!-- /.Start Date -->
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="date">End Date</label>
                    <input
                        type="date"
                        class="form-control"
                        v-model="endDate"
                        @change="reloadData"
                    />
                </div>
                <!-- /.End Date -->
            </div>
        </div>
        <table
            class="table table-hover"
            id="export_table"
        >
            <thead class="thead-light">
                <tr>
                    <th
                        scope="col"
                        v-for="(column, index) in tableColumns"
                        :key="index"
                    >
                        {{ column }}
                    </th>
                </tr>
            </thead>
        </table>
        <!-- /.Incomes Table -->
    </div>
    <!-- /.Data List -->
</template>

<script>
export default {
    mounted() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, "0");
        var mm = String(today.getMonth() + 1).padStart(2, "0"); // January is 0!
        var yyyy = today.getFullYear();

        // Format Date Range
        this.endDate = yyyy + "-" + mm + "-" + dd;
        this.startDate = yyyy + "-01-01";

        // Get Columns
        this.tableColumns = JSON.parse(this.$props.columns);

        // Init Datatable
        this.initTable();
    },
    methods: {
        reloadData() {
            let app = this;

            $(document).ready(function() {
                console.log("Reloading Data");

                $(`#export_table`)
                    .DataTable()
                    .ajax.url(
                        `${app.$attrs.url}?startDate=${
                            app.startDate
                        }&endDate=${app.endDate}&api_token=${
                            app.$props.api_token
                        }`
                    )
                    .load();
            });
        },
        initTable() {
            let app = this;

            $(document).ready(function() {
                let table = $(`#export_table`).dataTable({
                    language: {
                        paginate: {
                            next: '<i class="fas fa-chevron-right"></i>',
                            previous: '<i class="fas fa-chevron-left"></i>'
                        }
                    },
                    destroy: true,
                    pageLength: 100,
                    dom:
                        "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 pr-4 text-right'B>>rt<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6 pr-4 text-right'p>>",
                    buttons: {
                        buttons: [
                            {
                                extend: "copyHtml5",
                                className: "btn btn-warning"
                            },
                            {
                                extend: "excelHtml5",
                                text: "Export Excel",
                                filename: `IGG ${app.viewing} ${
                                    app.startDate
                                } - ${app.endDate}`,
                                className: "btn btn-default btn-xs"
                            }
                        ]
                    },
                    columns: app.tableColumns,
                    ajax: {
                        url: `${app.$attrs.url}?startDate=${
                            app.startDate
                        }&endDate=${app.endDate}&api_token=${
                            app.$props.api_token
                        }`,
                        method: "GET",
                        dataSrc: json => json.data
                    }
                });
            });
        }
    },
    props: ["api_token", "columns"],
    data() {
        return {
            startDate: "",
            endDate: "",
            rows: [],
            tableColumns: []
        };
    }
};
</script>
