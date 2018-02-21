<template>
    <div v-if="viewing.length" class="widget">
        <h4 class="widget-heading">Viewing thread</h4>
        <ul class="list-reset">
            <li class="pb-3 text-sm" v-for="user in viewing">
                <a
                  :class="{'font-bold': user.typing}"
                  class="link text-blue"
                  :href="'/profiles/' + user.name"
                  v-html="'@' + user.name + (user.typing ? ' (typing)':'')"
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

  computed: {
    viewing() {
      let exclude = []; // we might want to filter by multiple users later on

      if (window.App.signedIn) {
        exclude.push(window.App.user.username);
      }

      return this.users.filter(user => !exclude.includes(user.name));
    }
  },

  created() {
    if (this.viewingThread() && typeof window.Echo !== "undefined" && window.App.signedIn) {
      window.Echo.join(`forum.${window.channelName}`)
        .listenForWhisper('typing', ({ user }) => {

          let typingUser = this.users.find(entry => {
            return entry.id === user.id
          })

          typingUser.typing = true;

          // Simulate that the user has stopped typing
          setTimeout(() => {
            typingUser.typing = false;
          }, 3000);
        })
        .here(users => {
          this.users = users.map(user => {
            user.typing = false;
            return user;
          });
        })
        .joining(user => {
          user.typing = false;
          this.users.push(user);
        })
        .leaving(user => {
          let leaving = this.users.find(entry => {
            return entry.id === user.id;
          });
          this.users.splice(this.users.indexOf(leaving), 1);
        });
    }
  },

  methods: {
    viewingThread() {
      return /\/threads\/.*\/.*/i.test(window.location.pathname);
    }
  }
};
</script>