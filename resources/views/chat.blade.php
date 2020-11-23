@extends('layouts.chatapp')

@section('content')

<div class="">
    <div class="row" style="margin: 0px;">
        <div class="col-md-8 col-md-offset-2" style="padding:0px;">
            <div class="panel panel-default">
                <div class="panel-heading chatwindowheading">Quick Chat</div>

                <div class="panel-body" id="chatWindow">
                    <chat-messages :messages="messages" :receiverid="{{ $receiver_id }}" :senderid="{{ Auth::user()->id }}"></chat-messages>
                </div>
                <div class="panel-footer">
                    <chat-form
                        v-on:messagesent="addMessage"
                        :user="{{ Auth::user() }}"
                        :receiver_id="{{ $receiver_id }}"
                        ></chat-form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->
@endsection