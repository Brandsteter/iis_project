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
    let user = null
    try {
        const response = await axios.get("/auth/me");
        return response.data.data;
    } catch (error) {
        // If the request fails, it means the user is not logged in
        throw new Error("User not logged in");
    }
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
app.component("calendar", require("./components/CalendarComponent").default);
app.component("admin", require("./components/AdminComponent").default);
app.component("moderator", require("./components/ModeratorComponent.vue").default);
app.component("events", require("./components/event/EventsComponent.vue").default);
app.component("places", require("./components/place/PlacesComponent.vue").default);
app.component("categories", require("./components/category/CategoriesComponent.vue").default);
app.component("categoryList", require("./components/category/CategoryListComponent.vue").default);
app.component("categorydetail", require("./components/category/CategoryDetailComponent.vue").default);
app.component("eventdetail", require("./components/event/EventDetailComponent.vue").default);
app.component("usermyevents", require("./components/event/UserMyEventsComponent.vue").default);
app.component("placedetail", require("./components/place/PlaceDetailComponent.vue").default);

app.mount('#app');
