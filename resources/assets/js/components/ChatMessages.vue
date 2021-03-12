<template>
    <ul class="chat">
        <li class="left clearfix" v-for="message in messages" v-if="(message.user.id == senderid && message.receiver_id == receiverid) || (message.user.id == receiverid && message.receiver_id == senderid)">
            
            <div class="chat-body leftbox clearfix" v-if="(message.user.id == senderid && message.receiver_id == receiverid)">
                <div class="header">
                    <strong class="primary-font">
                        {{ message.user.name }}
                    </strong><br>
                    {{ message.created_at }}
                </div>
                <p>
                    {{ message.message }}
                </p>
            </div>
            <div class="chat-body rightbox clearfix" v-if="(message.user.id == receiverid && message.receiver_id == senderid)">
                <div class="header">
                    <strong class="primary-font">
                        {{ message.user.name }}
                    </strong><br>
                    {{ message.created_at }}
                </div>
                <p>
                    {{ message.message }}
                </p>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['messages', 'receiverid', 'senderid']
    };
</script>