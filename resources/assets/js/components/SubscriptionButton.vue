<template>
    <div>
        <button v-if="!status" v-on:click="toggle()" type="button" class="btn btn-success btn-sm">Subscribe {{subscribers}}</button>
        <button v-if="status" v-on:click="toggle()" type="button" class="btn btn-danger btn-sm">Unsubscribe {{subscribers}}</button>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                status: false,
                subscribers: 0,
            }
        },
        props: ['subscribeStatus', 'targetUserId', 'subscribersCount'],
        methods: {
            toggle: function () {
                this.optimisticToggle();
                axios.post('/subscription/toggle', {target_id: this.targetUserId}).then(this.toggleSuccess);
            },
            toggleSuccess: function (response) {
                this.status = response.data.subscription;
                this.subscribers = response.data.count;
            },
            optimisticToggle: function () {
                if (this.status){
                    this.subscribers--;
                } else {
                    this.subscribers++;
                }
                this.status = !this.status;
            }
        },
        mounted() {
            this.status = this.subscribeStatus;
            this.subscribers = this.subscribersCount;
        }
    }
</script>
