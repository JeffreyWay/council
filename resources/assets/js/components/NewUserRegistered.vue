<template>
</template>
<script>
    export default {
        created() {
            window.Echo.channel('new-users').listen('NewUserRegistered', ({user}) => {
                flash(`<a href="/profiles/${user.name}">${user.name}</a> has just joined, welcome to the forum ${user.name}!`, 'info', 5000);
            });
        }
    }
</script>