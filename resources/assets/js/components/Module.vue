<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Menu</div>

                    <div class="panel-body">
                        hola
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name:'Module',
        props:{
            list:''
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
