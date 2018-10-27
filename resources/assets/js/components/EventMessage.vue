<template>
    <div>
        <ul class="list-group">
            <li class="list-group-item" v-for="(message, i) in messages" :key="i">{{message}}</li> 
        </ul>       
    </div>
</template>

<script>
       export default {
        data() {
            return {
                messages: []
            };
        },
        mounted() {
            this.getUserConnection();
        },

        methods: {
            getUserConnection() {
                window.Echo.channel("events").listen(
                    "ClientEvent", ({message}) => {                        
                        if( this.messages.length > 4){
                        this.messages.splice(this.messages.length - 1, 1)
                        }
                        this.messages.unshift(message)                        
                    }
                );
            }
        }
    };
</script>

<style scoped>
.list-group-item {    
    padding: 10px 10px;    
    font-style:italic
}

.list-group {
    margin-bottom: 5px;
}

</style>

