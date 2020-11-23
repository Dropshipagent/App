<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">

        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['user', 'receiver_id'],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendMessage() {
                const today = new Date();
                const date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date + ' ' + time;
                this.$emit('messagesent', {
                    created_at: dateTime,
                    receiver_id: this.receiver_id,
                    user: this.user,
                    message: this.newMessage
                });

                this.newMessage = ''
            }
        }
    }
</script>
