const app = new Vue({
  el: '#main',
  data: {
    is_nav_burger_visible: false,
    is_menu_visible: false
  },
  methods: {
    /**
     * used to toggle the menue to be visible in smaller screens
     */
    toggle_burger: function () {
      this.is_nav_burger_visible = !this.is_nav_burger_visible
      this.is_menu_visible = !this.is_menu_visible
    }
  }
})