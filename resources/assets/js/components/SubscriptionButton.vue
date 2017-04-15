<template>
    <div>
        <button v-if="!status" v-on:click="toggle()" type="button" class="btn btn-success btn-sm">Subscribe</button>
        <button v-if="status" v-on:click="toggle()" type="button" class="btn btn-danger btn-sm">Unsubscribe</button>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                status: false,
            }
        },
        props: ['subscribeStatus', 'targetUserId'],
        methods: {
            toggle: function () {
                this.optimisticToggle();
                axios.post('/subscription/toggle', {target_id: this.targetUserId}).then(this.toggleSuccess);
            },
            toggleSuccess: function (response) {
                this.status = response.data.subscription;
            },
            optimisticToggle: function () {
                this.status = !this.status;
            }
        },
        mounted() {
            this.status = this.subscribeStatus;
        }
    }
</script>
