<template>
   <div v-if="users.length" class="widget">
            <h4 class="widget-heading">Who's online?</h4>

            <ul class="list-reset">
                <li class="pb-3 text-sm" v-for="user in users">
                    <a 
                        class="link text-blue"
                        :href="'/profiles/' + user.name"
                        v-html="'@' + user.name"
                    >
                    </a> 
            </li>
        </ul>  
    </div>
</template>

<script>
export default {
  data() {
    return {
      users: []
    };
  },

  created() {
    if (typeof window.Echo !== 'undefined' && window.App.signedIn) {
      window.Echo.join('forum.online')
        .here(users => {
          this.users = users;
        })
        .joining(user => {
          this.users.push(user);
        })
        .leaving(user => {
          this.users.splice(this.users.indexOf(user), 1);
        });
    }
  }
};
</script>
