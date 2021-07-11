/**
 * main vue instance
 */
const main = new Vue({
  el: '#main',
  data: {
    // model for the BIN field
    bin: '',

    // will contain API response after the query
    response: '',

    // controller for burger icon when windows size is smaller than desktop
    is_nav_burger_visible: false,
    is_menu_visible: false,

    // controller for button animation
    button: {
      in_progress: false,
    },
  },

  methods: {
    /**
     * accepts boolean to toggle states
     */
    stateChanger: function (buttonInProgress) {
      this.button.in_progress = buttonInProgress
    },
    /**
     * Submit BIN info to API
     */
    submitBIN: function () {
      this.stateChanger(true)
      this.response = ''

      /**
       * initialize axios variables
       */
      const url = '../scripts/submit_BIN.php'
      let data = new URLSearchParams()

      data.append('bin', this.bin)

      // post via axios
      axios
        .post(url, data)
        .then((response) => {
          this.response = response.data
        })
        .catch((error) => {
          console.error(error)
        })
        .finally(() => {
          this.stateChanger(false)
        })
    },
    /**
     * used to toggle the menue to be visible in smaller screens
     */
    toggle_burger: function () {
      this.is_nav_burger_visible = !this.is_nav_burger_visible
      this.is_menu_visible = !this.is_menu_visible
    },
  },
})
