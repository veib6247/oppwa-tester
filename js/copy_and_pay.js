/**
 * main vue instance
 */
const main = new Vue({
  el: "#main",
  data: {
    basic: {
      endpoint: "https://test.oppwa.com/v1/checkouts",
      authorization: "OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg=",
      parameters: [
        "entityId=8a8294174b7ecb28014b9699220015ca",
        "amount=00.01",
        "currency=EUR",
        "paymentType=PA",
        "billing.city=city1",
        "billing.country=PH",
        "billing.street1=strt1",
        "billing.postcode=1610",
        "customer.email=test@test.com",
        "customer.givenName=Test",
        "customer.surname=Tester",
      ],
    },

    response: "",

    button: {
      in_progress: false,
    },

    widget_content: "",

    is_nav_burger_visible: false,
    is_menu_visible: false,
    /**
     * this will be used
     */
    widget_custom: 'var wpwlOptions = {\n  style:"card"\n}',
  },

  methods: {
    /**
     * used to toggle the menue to be visible in smaller screens
     */
    toggle_burger: function () {
      this.is_nav_burger_visible = !this.is_nav_burger_visible;
      this.is_menu_visible = !this.is_menu_visible;
    },
    /**
     * submit all the details
     */
    submit: function () {
      /**
       * create script element,
       * insert customization
       * append to body
       */
      let custom_script = document.createElement("script");
      custom_script.innerText = this.widget_custom;
      document.body.appendChild(custom_script);

      /**
       * update button state
       */
      this.button.in_progress = true;
      this.response = "";

      /**
       * initialize axios variables
       */
      const url = "../scripts/submit_copyandpay.php";
      let data = new URLSearchParams();

      data.append("url", this.basic.endpoint);
      data.append("data", this.build_data_string());
      data.append("authorization", this.basic.authorization);

      // post via axios
      axios
        .post(url, data)
        .then((response) => {
          this.response = response.data;
        })
        .catch((error) => {
          console.error(error);
        })
        .finally(() => {
          this.button.in_progress = false;
        });
    },

    /**
     * call this to build the return url
     */
    build_return_url: function () {
      // entire URL
      const url = new URL(window.location.href);
      // find pages directory and append the file name
      return (
        // get the substring from 0(beginning of the URL) until the 'pages' directory
        url.toString().substr(0, url.toString().search("pages")) +
        "pages/trx-status.php"
      );
    },

    /**
     * returns host URL
     */
    get_host: function () {
      const dummy_anchor = document.createElement("a");
      dummy_anchor.href = this.basic.endpoint;

      return (
        "https://" + dummy_anchor.host + "/v1/paymentWidgets.js?checkoutId="
      );
    },

    /**
     * loops through each item in the select element to find the
     * selected brands and create the string
     */
    get_brands: function () {
      const selected_brands = document.getElementById("supported_brands");
      let brands = selected_brands.selectedOptions;

      let container = "";

      for (iterator = 0; iterator < brands.length; iterator++) {
        container += brands[iterator].label + " ";
      }

      console.trace(`Selected Brands: ${container}`);
      return container;
    },

    /**
     * call this function to build the data
     */
    build_data_string: function () {
      let str_data = "";
      // loop through each item to build string
      this.basic.parameters.forEach((element, index) => {
        // append the separator except the end of the string
        index !== this.basic.parameters.length - 1
          ? (str_data += element + "&")
          : (str_data += element);
      });

      return str_data;
    },
  },

  computed: {
    build_parameter_string: {
      // getter
      get: function () {
        let str_parameter = "";
        // loop through each item to build string
        this.basic.parameters.forEach((element, index) => {
          // if it's not the end of the array yet
          index !== this.basic.parameters.length - 1
            ? (str_parameter += element + "\n")
            : (str_parameter += element);
        });

        return str_parameter;
      },

      // setter
      set: function () {
        // get the textarea by ID
        const textarea = document.querySelector("#txt_params");
        const param_array = textarea.value.split("\n");
        // clear the array
        this.basic.parameters = [];
        // push new items
        param_array.forEach((element) => {
          this.basic.parameters.push(element);
        });
      },
    },
  },

  /**
   * call function when Vue is mounted into the DOM
   */
  mounted: function () {
    // get ip using ipify and push the rest of the data
    this.button.in_progress = true;
    axios
      .get("https://geo.ipify.org/api/v1?apiKey=at_AJyABOfKFcq8SbIS53Ori83AM78wh")
      .then((response) => {
        // dynamically add remaining required params for 3DSv2
        this.basic.parameters.push(
          "customer.ip=" + response.data.ip // <== it's the only thing I need from this really...         
        );

        // log the IP 
        console.trace(response.data.ip)
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        this.button.in_progress = false;
        console.trace("AJAX Call - Vue mounted: added customer IP");
      });
  },
});

/**
 * component widget
 */
Vue.component("widget", {
  props: ["checkout", "brands", "action"],
  template: `
    <div>
      <script type="application/javascript" :src="checkout"></script>
      <form :action="action" class="paymentWidgets" :data-brands="brands"></form>
    </div>
  `,
});
