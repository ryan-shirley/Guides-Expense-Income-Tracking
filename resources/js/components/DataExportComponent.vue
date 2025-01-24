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
        const year = this.$attrs.year;

        // Format Date Range
        this.endDate = year + "-12-31";
        this.startDate = year + "-01-01";

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
        initTable() {
            let app = this;

            $(document).ready(function() {
                // Add custom date sorting
                $.fn.dataTable.ext.type.order['date-euro-pre'] = function(date) {
                    if (date === null || date === '') {
                        return -Infinity;
                    }
                    // Convert from DD-MM-YYYY to YYYY-MM-DD for proper sorting
                    const parts = date.split('-');
                    return parts[2] + parts[1] + parts[0];
                };

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
                    columns: app.tableColumns.map(col => {
                        // Find the date column and add the custom sorting
                        if (col.title === "Date") {
                            return {
                                ...col,
                                type: 'date-euro'
                            };
                        }
                        return col;
                    }),
                    ajax: {
                        url: `${app.$attrs.url}?startDate=${
                            app.startDate
                        }&endDate=${app.endDate}&api_token=${
                            app.$props.api_token
                        }`,
                        method: "GET",
                        dataSrc: json => json.data.map(d => {
                            const property = app.tableColumns.find(col => col.title == "Date").data
                            d[property] = app.formatDate(d[property]);
                            return d;
                        })
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
            tableColumns: [],
            viewing: this.$attrs.url.split("/").at(-1),
        };
    }
};
</script>
