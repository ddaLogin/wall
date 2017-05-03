<template>
    <div>
        <form class="form-horizontal">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
                <input v-model="q" type="text" class="form-control" placeholder="Search in subscriptions">
            </div>
        </form>
        <br>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" v-bind:class="{'active': isTabNeeded('#subscriptions')}" ><a href="#subscriptions" id="subscriptions-tab" aria-controls="subscriptions" role="tab" data-toggle="tab">Subscriptions <span class="badge">{{filteredSubscriptions.length}}</span></a></li>
            <li role="presentation" v-bind:class="{'active': isTabNeeded('#subscribers')}" ><a href="#subscribers" id="subscribers-tab" aria-controls="subscribers" role="tab" data-toggle="tab">Subscribers <span class="badge">{{filteredSubscribers.length}}</span></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane" v-bind:class="{'active': isTabNeeded('#subscriptions')}"  id="subscriptions">
                <ul class="list-group">
                    <li v-for="subscription in filteredSubscriptions" class="list-group-item">
                        <a :class="{'online-2': subscription.target.status}" :href="subscription.target.link">
                            <img class="photo-mini" :src="subscription.target.photo_link_mini" alt="">
                            {{subscription.target.nickname}}
                        </a>
                    </li>
                    <h3 v-if="!filteredSubscriptions.length" class="text-center text-warning">Subscriptions not found</h3>
                    <h3 v-if="!subscriptions.length" class="text-center text-warning">You have no subscriptions</h3>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane" v-bind:class="{'active': isTabNeeded('#subscribers')}"  id="subscribers">
                <ul class="list-group">
                    <li v-for="subscriber in filteredSubscribers" class="list-group-item">
                        <a :class="{'online-2': subscriber.user.status}" :href="subscriber.user.link">
                            <img class="photo-mini" :src="subscriber.user.photo_link_mini" alt="">
                            {{subscriber.user.nickname}}
                        </a>
                    </li>
                    <h3 v-if="!filteredSubscribers.length" class="text-center text-warning">Subscribers is not found</h3>
                    <h3 v-if="!subscribers.length" class="text-center text-warning">You have no subscribers</h3>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                q: '',
            }
        },
        props: ['subscriptions', 'subscribers'],
        computed: {
            filteredSubscriptions : function () {
                var subscriptions = this.subscriptions;
                var q = this.q.toLowerCase();
                var data = [];
                subscriptions.forEach(function (element, index, array) {
                    if (element['target'].nickname.toLowerCase().indexOf(q) != -1) {
                        data.push(element);
                    }
                });
                return data;
            },
            filteredSubscribers : function () {
                var subscribers = this.subscribers;
                var q = this.q.toLowerCase();
                var data = [];
                subscribers.forEach(function (element, index, array) {
                    if (element['user'].nickname.toLowerCase().indexOf(q) != -1) {
                        data.push(element);
                    }
                });
                return data;
            }
        },
        methods: {
            isTabNeeded: function (tabName) {
                var ifEmptyHash = (window.location.hash == '' && tabName == '#subscriptions');
                return (window.location.hash == tabName || ifEmptyHash);
            }
        }
    }
</script>