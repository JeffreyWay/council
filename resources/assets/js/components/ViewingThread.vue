<template>
    <div v-if="viewing.length" class="widget">
        <h4 class="widget-heading">Viewing thread</h4>
        <ul class="list-reset">
            <li class="pb-3 text-sm" v-for="user in viewing">
                <a
                  :class="{'font-bold': user.typing}"
                  class="link text-blue"
                  :href="'/profiles/' + user.name"
                  v-html="'@' + user.name + (user.typing ? ' (typing)':'')">
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
    if (this.viewingThread()) {
      if (typeof window.Echo !== "undefined" && window.App.signedIn) {
        window.Echo.join(`forum.${window.channelName}`)
          .listenForWhisper('typing', ({ user }) => {
            this.users.forEach(u => {
              if (u.id === user.id) {
                u.typing = true;

                // Simulate that the user has stopped typing
                // after a set amount of time.
                setTimeout(() => {
                  u.typing = false;
                }, 3000);
              }
            });
          })
          .here(users => {
            this.users = this.channelUsers(users);
          })
          .joining(user => {
            this.add(user);
          })
          .leaving(user => {
            this.remove(user);
          });
      }
    }
  },

  methods: {
    viewingThread() {
      return /\/threads\/.*\/.*/i.test(window.location.pathname);
    },
    channelUsers(users) {
      let channelUsers = [];

      users.forEach(user => {
        channelUsers.push({
          id: user.id,
          name: user.name,
          typing: false
        });
      });

      return channelUsers;
    },
    add(user) {
      this.users.push({
        id: user.id,
        name: user.name,
        typing: false
      });
    },
    remove(user) {
      let entry = this.users.find(u => {
        return u.id === user.id;
      });
      this.users.splice(this.users.indexOf(entry), 1);
    }
  }
};
</script>