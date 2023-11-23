<template>
    <div>
        <hr>
        <ul class="custom-list">
            <li v-for="category in children" class="list-item2">
                <v-btn @click="toggleCollapse(category.name)" density="comfortable" icon="mdi-arrow-down"></v-btn>
                <v-btn class="category-link" :href="`/category/${category.id}/detail`" min-width="200">{{ category.name }}</v-btn>
                <categoryList v-if="categoryShown === category.name && category.categories !== {}" :categoryId="category.id"></categoryList>
            </li>
        </ul>

    </div>
</template>

<script>

export default {
    props: ['categoryId'],
    data() {
        return {
            children: [],
            collapsedCategories: [],
            showChildren: false,
            categoryShown: "",
        };
    },
    mounted() {
        this.getChildCategories(this.categoryId);
    },
    methods: {
        getChildCategories(categoryId) {
            const url = `/category/${categoryId}`;
            axios.get(url)
                .then(response => {
                    // this.parentName = category.name;
                    this.children = response.data;
                })
                .catch(error => {
                    console.error('Error fetching unapproved categories:', error);
                });
        },
        toggleCollapse(categoryName) {
          if (this.categoryShown === categoryName) {
            this.categoryShown = "";
          } else {
            this.categoryShown = categoryName;
          }
        },
    }
};
</script>

<style>

  /* Remove default list styles */
  .custom-list {
    list-style: none;
    padding: 0;
  }

  /* Adjust margins between list items */
  .list-item2 {
    padding-left: 40px; /* Indentation size */
    margin-bottom: 10px; /* Increase or decrease as needed */
  }

  /* Add space between buttons */
  .category-link {
    margin-left: 10px; /* Adjust the margin as needed */
  }
</style>
