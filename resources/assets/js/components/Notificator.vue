<template>
    <a :href="notificationPage"><span class="label label-primary"><i class="fa fa-bell" aria-hidden="true"></i> {{count}}</span></a>
</template>

<script>
    export default {
        data: function () {
            return {
                count: 0,
            };
        },
        props:['notificationPage', 'notificationCount'],
        methods:{
            notification: function (notify) {
                this.count ++;
                var stripper = this.stripTags;
                var url = this.notificationPage;
                Notification.requestPermission(function () {
                    var notification = new Notification("New notify", {
                        tag: notify.type,
                        body: stripper(notify.text),
                        icon: notify.icon,
                    });

                    notification.onclick = function () {
                        window.location = url;
                        window.focus();
                        this.close();
                    };
                });

            },
            stripTags: function (text) {
                return text.replace(/<(?:.|\n)*?>/gm, '');
            }
        },
        mounted() {
            this.count = this.notificationCount;
            window.Echo.private('App.Models.User.'+window.Laravel.userId)
                        .notification(this.notification);
        }
    }
</script>
