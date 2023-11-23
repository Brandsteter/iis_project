import './bootstrap';
import { createApp } from 'vue';
import { createVuetify } from 'vuetify/framework';
import 'vuetify/styles';
import { isProxy, toRaw } from 'vue';
import '@fortawesome/fontawesome-free/css/all.css'

// Import Vuetify modules
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

// Create Vuetify instance
const vuetify = createVuetify({
    components,
    directives,
});

export function isRole(role, user) {
    if (user) {
        return user.roles.findIndex(item => item.role === role) !== -1;
    }
}

export async function getAuthUser() {
    let user = null;
    await axios.get("/auth/me").then((response) => {
        user = response.data.data;
    });
    return user;
}

export function checkIfUserIsInArrayOfUsers(users, user){
    if (isProxy(user)){
        const userData = toRaw(user)
        return users.filter(userInArray => {
            return userInArray.id === userData.id
        }).length > 0
    }
    return false
}

const app = createApp({});

// Use Vuetify
app.use(vuetify);



app.component("register", require("./components/auth/RegisterComponent").default);
app.component("login", require("./components/auth/LoginComponent").default);
app.component("root", require("./components/RootComponent").default);
app.component("calendar", require("./components/CalendarComponent").default);
app.component("admin", require("./components/AdminComponent").default);
app.component("moderator", require("./components/ModeratorComponent.vue").default);
app.component("events", require("./components/EventsComponent").default);
app.component("places", require("./components/PlacesComponent").default);
app.component("categories", require("./components/CategoriesComponent").default);
app.component("categoryList", require("./components/CategoryListComponent.vue").default);
app.component("categorydetail", require("./components/CategorydetailComponent.vue").default);
app.component("eventdetail", require("./components/EventDetailComponent.vue").default);
app.component("usermyevents", require("./components/UserMyEventsComponent.vue").default);

app.mount('#app');
