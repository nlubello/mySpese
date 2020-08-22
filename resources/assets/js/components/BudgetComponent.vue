<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-accent-danger">
                <div class="card-header">Categorie di spesa</div>

                <div class="card-body">
                    <data-tables :data="categories" :action-col="actionCol" :filters="filters">
                        <el-table-column v-for="title in titlesExp" :prop="title.prop" :label="title.label"
                            :key="title.prop" sortable="custom">
                        </el-table-column>
                    </data-tables>
                </div>
            </div>
            <div class="card card-accent-success">
                <div class="card-header">Categorie di incassi</div>

                <div class="card-body">
                    <data-tables :data="categories" :action-col="actionCol" :filters="filters">
                        <el-table-column v-for="title in titlesInc" :prop="title.prop" :label="title.label"
                            :key="title.prop" sortable="custom">
                        </el-table-column>
                    </data-tables>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-accent-default">
                <div class="card-header">Budget Totale</div>

                <div class="card-body">
                    <bar-chart id="bar" 
                        :data="totalData" 
                        xkey="y" 
                        ykeys='["inc","exp","bal"]'
                        labels='["Incassi","Spese","Bilancio"]'
                        bar-colors='[ "#00a65a", "#dd4b39", "#f0ad4e"]' 
                        grid="true"
                        grid-text-weight="bold"
                        resize="true">
                    </bar-chart>
                </div>
            </div>
            <div class="card card-accent-default">
                <div class="card-header">Budget Spese</div>

                <div class="card-body">
                    <donut-chart id="expDonut" :data="expData" :colors='expColors' resize="true"></donut-chart>
                </div>
            </div>
            <div class="card card-accent-default">
                <div class="card-header">Budget Incassi</div>

                <div class="card-body">
                    <donut-chart id="incDonut" :data="incData" :colors='incColors' resize="true"></donut-chart>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import {
        DonutChart,
        BarChart,
        LineChart,
        AreaChart
    } from 'vue-morris'

    export default {
        props: ['url'],

        components: {
            DonutChart,
            BarChart,
            LineChart,
            AreaChart
        },

        data: function () {
            return {
                year: new Date().getFullYear(),
                totalData: [],
                incData: [],
                incColors: [
                    '#0C9D01',
                    '#24A702',
                    '#3CB204',
                    '#55BD05',
                    '#6DC807',
                    '#86D308',
                    '#9EDE0A',
                    '#B7E90B',
                    '#CFF40D',
                    '#E8FF0F'
                ],
                expData: [],
                expColors: [
                    '#DF0118',
                    '#E21817',
                    '#E52F16',
                    '#E84715',
                    '#EB5E14',
                    '#EF7613',
                    '#F28D12',
                    '#F28D12',
                    '#F5A411',
                    '#F8BC10'
                ],
                categories: [],
                titlesInc: [],
                titlesExp: [],
                filters: [{
                    prop: 'flow_no',
                    value: ''
                }],
                actionCol: {
                    props: {
                        label: 'Azioni',
                    },
                    buttons: [{
                        props: {
                            type: 'primary',
                            size: 'mini',
                            icon: "el-icon-view",
                            circle: true
                        },
                        handler: row => {
                            window.location.href = this.url+'/admin/category/'+row.id;
                        },
                        label: ''
                    }, {
                        props: {
                            type: 'warning',
                            size: 'mini',
                            icon: "el-icon-edit",
                            circle: true
                        },
                        handler: row => {
                            window.location.href = this.url+'/admin/category/'+row.id+'/edit';
                        },
                        label: ''
                    }]
                },
                selectedRow: []
            };
        },

        mounted() {
            console.log('Component mounted.')

            this.titlesInc = [
                    { prop: "name", label: "Nome" }, 
                    { prop: "inc_last_year", label: "IN " + (this.year-1) }, 
                    { prop: "budget_income", label: "IN Budget" }, 
                    { prop: "inc_curr_year", label: "IN " + this.year + " [€]" },
                    { prop: "inc_curr_year_perc", label: "IN " + this.year + " [%]" },
                ];
            this.titlesExp = [
                    { prop: "name", label: "Nome" }, 
                    { prop: "exp_last_year", label: "OUT " + (this.year-1) }, 
                    { prop: "budget_expense", label: "OUT Budget" }, 
                    { prop: "exp_curr_year", label: "OUT " + this.year + " [€]" }, 
                    { prop: "exp_curr_year_perc", label: "OUT " + this.year + " [%]" }, 
                ];

            axios
                .get(this.url + '/api/categories')
                .then(response => (this.loadData(response.data)))
        },

        methods: {
            loadData(data) {
                this.categories = data;
                
                this.expData = []; this.incData = [];
                let totalExp = 0; let totalInc = 0;
                let prevExp = 0; let prevInc = 0;

                let vm = this;
                this.categories.forEach(function (item, index) {
                    // Convert numbers of JSON
                    Vue.set(vm.categories[index], 'budget_expense', parseFloat(item.budget_expense));
                    Vue.set(vm.categories[index], 'budget_income', parseFloat(item.budget_income));
                    Vue.set(vm.categories[index], 'exp_last_year', parseFloat(item.exp_last_year));
                    Vue.set(vm.categories[index], 'inc_last_year', parseFloat(item.inc_last_year));
                    Vue.set(vm.categories[index], 'exp_curr_year', parseFloat(item.exp_curr_year));
                    Vue.set(vm.categories[index], 'inc_curr_year', parseFloat(item.inc_curr_year));

                    totalExp += item.budget_expense;
                    totalInc += item.budget_income;
                    prevExp += item.exp_last_year;
                    prevInc += item.inc_last_year;

                    if(item.budget_expense > 0){
                        vm.expData.push({ label: item.name, value: item.budget_expense });
                    }
                    if(item.budget_income > 0){
                        vm.incData.push({ label: item.name, value: item.budget_income });
                    }
                })

                let year = new Date().getFullYear();

                this.totalData = [
                    { y: year-1, 'exp': prevExp, 'inc': prevInc, 'bal': prevInc-prevExp },
                    { y: year, 'exp': totalExp, 'inc': totalInc, 'bal': totalInc-totalExp },
                ]
            
            },
        },
    }
</script>