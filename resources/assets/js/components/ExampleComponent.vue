<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header">Example Component</div>

                    <div class="box-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url'],
        mounted() {
            console.log('Component mounted.')

            axios
                .get(this.url+'/api/user')
                .then(response => (console.log(response)))
        }
    }
</script>
