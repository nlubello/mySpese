<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="box box-default">
                <div class="box-header">Categorie di spesa/incassi</div>

                <div class="box-body">
                    <data-tables :data="categories" :action-col="actionCol" :filters="filters">
                        <el-table-column type="selection" width="55">
                        </el-table-column>

                        <el-table-column v-for="title in titles" :prop="title.prop" :label="title.label"
                            :key="title.prop" sortable="custom">
                        </el-table-column>
                    </data-tables>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header">Budget Totale</div>

                <div class="box-body">
                    <donut-chart id="donut" :data="totalData" colors='[ "#dd4b39", "#00a65a"]' resize="true"></donut-chart>
                </div>
            </div>
            <div class="box box-default">
                <div class="box-header">Budget Spese</div>

                <div class="box-body">
                    <donut-chart id="expDonut" :data="expData" :colors='expColors' resize="true"></donut-chart>
                </div>
            </div>
            <div class="box box-default">
                <div class="box-header">Budget Incassi</div>

                <div class="box-body">
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
                totalData: [
                    { label: 'Spese', value: 0 },
                    { label: 'Incassi', value: 0 },
                ],
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
                titles: [
                    { prop: "name", label: "Nome" }, 
                    { prop: "exp_last_year", label: "Spesa Precedente" }, 
                    { prop: "inc_last_year", label: "Incasso Precedente" }, 
                    { prop: "exp_curr_year", label: "Spesa Attuale" }, 
                    { prop: "inc_curr_year", label: "Incasso Attuale" }, 
                    { prop: "budget_income", label: "Budget incasso" }, 
                    { prop: "budget_expense", label: "Budget spesa" }, 
                ],
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

            axios
                .get(this.url + '/api/categories')
                .then(response => (this.loadData(response.data)))
        },

        methods: {
            loadData(data) {
                this.categories = data;
                
                this.expData = [];
                this.incData = [];
                let totalExp = 0;
                let totalInc = 0;
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

                    if(item.budget_expense > 0){
                        vm.expData.push({ label: item.name, value: item.budget_expense });
                    }
                    if(item.budget_income > 0){
                        vm.incData.push({ label: item.name, value: item.budget_income });
                    }
                })

                this.totalData = [
                    { label: 'Spese', value: totalExp },
                    { label: 'Incassi', value: totalInc },
                ]
            
            },
        },
    }
</script>