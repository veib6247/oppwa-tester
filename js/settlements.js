const app = new Vue({
  el: '#main',
  data: {
    button: {
      in_progress: false,
    },

    fields: {
      endpoint:
        'https://test.oppwa.com/reports/v1/reconciliations/aggregations',
      bearer: 'OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg=',
    },

    parameters: [
      'entityId=8a8294174b7ecb28014b9699220015ca',
      'date.from=2015-08-01',
      'date.to=2015-08-02',
      'currency=EUR',
      'testMode=INTERNAL',
    ],

    response: {
      data: '',
    },

    is_nav_burger_visible: false,
    is_menu_visible: false,
  },

  methods: {
    /**
     * used to toggle the menue to be visible in smaller screens
     */
    toggle_burger: function () {
      this.is_nav_burger_visible = !this.is_nav_burger_visible
      this.is_menu_visible = !this.is_menu_visible
    },

    /**
     * submit via axios
     */
    submit: function () {
      this.button.in_progress = true
      this.response.data = ''

      const url = '../scripts/submit_settlements.php'
      let data = new URLSearchParams()

      data.append('host', this.fields.endpoint)
      data.append('data', this.build_data_string())
      data.append('bearer', this.fields.bearer)

      // post via axios
      axios
        .post(url, data)
        .then((response) => {
          this.response.data = response.data
        })
        .catch((error) => {
          console.error(error)
        })
        .finally(() => {
          this.button.in_progress = false
        })
    },

    /**
     * build the string to be used for POST request later
     */
    build_data_string: function () {
      let str_data = ''
      // loop through each item to build string
      this.parameters.forEach((element, index) => {
        // append the separator except the end of the string
        index !== this.parameters.length - 1
          ? (str_data += element + '&')
          : (str_data += element)
      })
      // return data string for later use
      return str_data
    },
  },

  computed: {
    build_parameter_string: {
      // getter
      get: function () {
        let str_parameter = ''
        // loop through each item to build string
        this.parameters.forEach((element, index) => {
          // if it's not the end of the array yet
          index !== this.parameters.length - 1
            ? (str_parameter += element + '\n')
            : (str_parameter += element)
        })

        return str_parameter
      },

      // setter
      set: function () {
        // get the textarea by ID
        const textarea = document.querySelector('#txt_params')
        const param_array = textarea.value.split('\n')
        // clear the array
        this.parameters = []
        // push new items
        param_array.forEach((element) => {
          this.parameters.push(element)
        })
      },
    },
  },
})
