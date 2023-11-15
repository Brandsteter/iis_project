import './bootstrap';
import { createApp } from 'vue';
import { createVuetify } from 'vuetify/framework';
import 'vuetify/styles';

// Import Vuetify modules
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

// Create Vuetify instance
const vuetify = createVuetify({
    components,
    directives,
});

function isRole(role, user) {
    if (user) {
        return user.roles.findIndex(item => item.name === role) !== -1;
    }
}

async function getAuthUser() {
    let user = null;
    await axios.get(route("auth.me")).then((response) => {
        user = response.data.data;
    });
    return user;
}

const app = createApp({});

// Use Vuetify
app.use(vuetify);



app.component("register", require("./components/auth/RegisterComponent").default);
app.component("login", require("./components/auth/LoginComponent").default);
app.component("root", require("./components/RootComponent.vue").default);
app.component("calendar", require("./components/CalendarComponent.vue").default);
app.component("admin", require("./components/AdminComponent.vue").default);

app.mount('#app');
