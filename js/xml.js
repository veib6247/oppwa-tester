/**
 * main vue instance
 */
const main = new Vue({
  el: '#main',
  data: {
    endpoint: 'https://test.ctpe.io/payment/ctpe',
    xmlData: '',
    is_nav_burger_visible: false,
    is_menu_visible: false,
    response: '',
    showProgressBar: false,
    isButtonLoading: false,
    internalSubmitURL: '../scripts/submit_xml.php',
  },

  /**
   * all the methods be here
   */
  methods: {
    // toggle to view menu on mobile
    toggle_burger: function () {
      this.is_nav_burger_visible = !this.is_nav_burger_visible
      this.is_menu_visible = !this.is_menu_visible
    },

    // run this function to read the xml template once the page loads
    readTemplate: function () {
      // use axios to GET the file
      axios
        .get('../src/xmls/request_template.xml')
        .then((response) => {
          console.log('Fetching XML template')
          this.xmlData = response.data
        })
        .catch((error) => {
          // handle error
          console.error(error)
        })
    },

    setLoading: function (progressBar, button) {
      this.showProgressBar = progressBar
      this.isButtonLoading = button
    },

    // use axios to post to PHP script in the server to execute the transaction
    submitXMLRequest: function () {
      // clear response variable
      this.response = ''

      // start loading animation
      this.setLoading(true, true)

      // create var to hold URL search parameters for posting
      let data = new URLSearchParams()
      // append each field
      data.append('url', this.endpoint)
      data.append('data', this.xmlData)

      // call axios, use arrow functions because it's too modern to not understand normal functions, lol
      axios
        .post(this.internalSubmitURL, data)
        .then((response) => {
          this.response = response.data
        })
        .catch((error) => {
          console.error(error)
        })
        .finally(() => {
          // stop progressbar animation
          this.setLoading(false, false)
        })
    },
  },

  /**
   * this will run once script is loaded
   */
  mounted: function () {
    this.readTemplate()
  },
})
