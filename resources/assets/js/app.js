
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
Vue.component('chat-form', require('./components/ChatForm.vue').default);

const app = new Vue({
    el: '#app',

    data: {
        messages: []
    },

    created() {
        this.fetchMessages();
        Echo.private('chat')
                .listen('MessageSent', (e) => {
                    //console.log(e);
                    this.messages.push({
                        created_at: e.message.created_at,
                        receiver_id: e.message.receiver_id,
                        message: e.message.message,
                        user: e.user
                    });
                });
    },

    methods: {
        fetchMessages() {
            var receiverID = window.Laravel.receiverID;
            axios.get('/messages/' + receiverID).then(response => {
                this.messages = response.data;
            });
        },

        addMessage(message) {
            this.messages.push(message);

            axios.post('/messages', message).then(response => {
                console.log(response.data);
            });
        }
    },
    updated: function() { 
        var el = document.getElementById("chatWindow"); 
        el.scrollTop = el.scrollHeight; 
    }
});
