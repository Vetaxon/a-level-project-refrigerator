<template>
    <div style="overflow-y: scroll; height:500px">
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
    
        props: ["events"],
    
        mounted() {
            this.getUserConnection();
            this.getEvents()
        },
    
        methods: {
            getUserConnection() {
                window.Echo.channel("events").listen(
                    "ClientEvent", ({
                        message
                    }) => {
                        if (this.messages.length > 19) {
                            this.messages.splice(this.messages.length - 1, 1)
                        }
                        this.messages.unshift(message)
                    }
                );
            },
            getEvents(){
                let messages = JSON.parse(this.events)
                messages.forEach(element => {
                    this.messages.push(element[0]);
                });

            }
        }
    };
</script>

<style scoped>
    .list-group-item {
        padding: 10px 10px;
        font-style: italic
    }
    
    .list-group {
        margin-bottom: 5px;
    }
</style>

