<template>
    <div class="text-center" style="width: 100%; height:100%;" id="screen">
        <button v-if="!connection" v-on:click="join()" class="btn btn-success btn-lg">Join</button>

        <div v-for="stream in streams" class="col col-md-3">
            <video id="remoteStream" :src="stream" controls autoplay="true"></video>
        </div>

        <div style="position:fixed; right:0; bottom:0; border: 1px solid black;">
            <div class="bg-info text-center"><h4>Your stream</h4></div>
            <div style="width: 330px; height: 250px; padding: 5px;">
                <video style="width: 100%; height:100%;" id="stream" autoplay="true" muted></video>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                userId: window.Laravel.userId,
                connection: false,
                stream: null,
                streams: [],
                peers: {},
                PeerConnection: window.mozRTCPeerConnection || window.webkitRTCPeerConnection,
                SessionDescription: window.mozRTCSessionDescription || window.RTCSessionDescription,
                IceCandidate: window.mozRTCIceCandidate || window.RTCIceCandidate,
                server: {
                    iceServers: [
                        {url: "stun:23.21.150.121"},
                        {url: "stun:stun.l.google.com:19302"},
                    ]
                },
                options: {
                    optional: [
                        {DtlsSrtpKeyAgreement: true}, // требуется для соединения между Chrome и Firefox
                        {RtpDataChannels: true} // требуется в Firefox для использования DataChannels API
                    ]
                },
            };
        },
        props: ['roomLink'],
        mounted() {
            this.getMediaStream();
        },
        methods: {

            //Media stream part
            getMediaStream: function () {
                var streamConstraints = {
                    "audio": true,
                    "video": {
                        "mandatory": { "maxWidth": "320", "maxHeight": "240", "maxFrameRate": "25" },
                        "optional": []
                    }
                };

                if (navigator.webkitGetUserMedia) {
                    navigator.webkitGetUserMedia(streamConstraints, this.getMediaStreamSuccess, this.getMediaStreamError);
                } else {
                    navigator.mozGetUserMedia(streamConstraints, this.getMediaStreamSuccess, this.getMediaStreamError);
                }
            },
            getMediaStreamSuccess: function (stream) {
                document.getElementById('stream').src = URL.createObjectURL(stream);
                this.stream = stream;
            },
            getMediaStreamError: function (error) {
                console.error('Could\'t get user media stream: ' + error);
            },
            onRemoteStream: function (event) {
                this.streams.push(URL.createObjectURL(event.stream));
            },


            //WebSocket part
            join: function () {
                var vm = this;
                window.Echo.join('Room.'+this.roomLink)
                    .here(this.here)
                    .joining(this.joining)
                    .leaving(this.leaving)
                    .listenForWhisper('new-offer', function(event){
                        if (vm.userId == event.target){
                            vm.createAnswer(event.user, event.offer);
                            vm.setCandidates(event.user, event.candidates);
                        }
                    })
                    .listenForWhisper('new-answer', function(event){
                        if (vm.userId == event.target){
                            vm.setAnswer(event.user, event.answer);
                            vm.setCandidates(event.user, event.candidates);
                        }
                    });
            },
            here: function (members) { //when we complete join, open peer
                this.connection = true;
                members.forEach(this.createOffer);
            },
            joining: function (joiningMember, members) {
//                this.members.push(joiningMember);
            },
            leaving: function (leavingMember, members) {
//                this.members.splice(this.members.indexOf(leavingMember),1);
            },


            //WEBRtcConnection part
            getPeer: function (userId) {
                if (userId == this.userId) {
                    return null;
                }

                if (userId === undefined || this.peers[userId] === null || this.peers[userId] === undefined){
                    this.peers[userId] = {};
                    this.peers[userId].targetUser = userId;
                    this.peers[userId].candidates = [];
                    this.peers[userId].connection = new this.PeerConnection(this.server, this.options);
                }

                return this.peers[userId];
            },
            initPeer: function (peer, type) {
                var vm = this;
                peer.connection.onicecandidate = function (event) {
                    if (event.candidate){
                        peer.candidates.push(event.candidate);
                    } else {
                        if (type == 'offer'){
                            window.Echo.join('Room.'+vm.roomLink).whisper('new-offer', {target: peer.targetUser, user: vm.userId, offer: peer.connection.localDescription, candidates: peer.candidates});
                        } else if (type == 'answer'){
                            window.Echo.join('Room.'+vm.roomLink).whisper('new-answer', {target: peer.targetUser, user: vm.userId, answer: peer.connection.localDescription, candidates: peer.candidates});
                        }
                    }
                };
                peer.connection.onaddstream = this.onRemoteStream;
                peer.connection.addStream(this.stream);
            },
            createOffer: function (user) {
                var peer = this.getPeer(user.id);
                if (peer !== null){
                    this.initPeer(peer, 'offer');
                    peer.connection.createOffer(function(offer) {
                        peer.connection.setLocalDescription(offer);
                    }, function () {}, {});
                }
            },
            createAnswer: function (userId, offer) {
                var peer = this.getPeer(userId);
                if (peer !== null){
                    this.initPeer(peer, 'answer');
                    peer.connection.setRemoteDescription( new RTCSessionDescription(offer));
                    peer.connection.createAnswer(function(answer) {
                        peer.connection.setLocalDescription(answer);
                    }, function () {}, {'mandatory': { 'OfferToReceiveAudio':true, 'OfferToReceiveVideo':true }}
                    );
                }
            },
            setAnswer: function (userId, answer) {
                var peer = this.getPeer(userId);
                if (peer !== null){
                    peer.connection.setRemoteDescription( new RTCSessionDescription(answer));
                }
            },
            setCandidates: function (userId, candidates) {
                var peer = this.getPeer(userId);
                if (peer !== null){
                    candidates.forEach(function (candidate) {
                        peer.connection.addIceCandidate(new RTCIceCandidate(candidate));
                    });
                }
            },
        },
    }
</script>