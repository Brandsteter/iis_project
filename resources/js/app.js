import './bootstrap';

import {createApp} from 'vue'


function isRole(role, user) {
  if (user) {
    return user.roles.findIndex(item => item.name === role) !== -1
  }
}

async function getAuthUser() {
    let user = null
    await axios.get(route("auth.me")).then((response) => {
        user = response.data.data
    })
    return user
}


const app = createApp({})

app.component("test", require("./components/test.vue").default)
app.component("register", require("./components/auth/RegisterComponent").default)
app.mount("#app")
