/**
 * main vue instance
 */
const main = new Vue({
  el: "#main",
  data: {
    basic: {
      endpoint: "https://test.oppwa.com/v1/payments",
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
        "customParameters[3DS2_enrolled]=true",
        "customParameters[3DS2_flow]=challenge",
        "paymentBrand=VISA",
        "card.number=4000000000000044",
        "card.holder=Bruce Wayne",
        "card.expiryMonth=02",
        "card.expiryYear=2023",
        "card.cvv=987",
        "merchantTransactionId=test_trx_3dv1_001",
        "customer.browser.acceptHeader=text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
      ],
    },

    response: "",

    redirect: "",

    button: {
      in_progress: false,
    },

    is_nav_burger_visible: false,
    is_menu_visible: false,
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
      this.button.in_progress = true;
      this.response = "";

      const url = "../scripts/submit_server2server.php";
      let data = new URLSearchParams();

      data.append("url", this.basic.endpoint);
      data.append("data", this.build_data_string());
      data.append("authorization", this.basic.authorization);

      // post via axios
      axios
        .post(url, data)
        .then((response) => {
          this.response = response.data;

          // handle 3D URL
          response.data.redirect
            ? (this.redirect = this.build_3d_url(response.data.redirect))
            : (this.redirect = "non-3D");
        })
        .catch((error) => {
          console.error(error);
        })
        .finally(() => {
          // return button original state
          this.button.in_progress = false;
          // scroll down to the response textarea after vue has it rendered
          document.querySelector("#progress_bar").scrollIntoView();
        });
    },

    /**
     * build 3DS URL
     */
    build_3d_url: function (redirect) {
      let redirect_url = "";
      let query_array = [];
      // get the main redirect URL
      const url = redirect.url;
      redirect_url += url + "&";

      const redirect_object = redirect.parameters;
      let obj_container = {}; // this object will contain every name=value pair one at a time
      /**
       * parse the redirect object
       */
      for (const property in redirect_object) {
        obj_container = redirect_object[property];
        query_array.push(`${obj_container.name}=${obj_container.value}`);
      }

      // build the query string
      query_array.forEach((item, index) => {
        index != query_array.length - 1
          ? (redirect_url += item + "&")
          : (redirect_url += item);
      });

      return redirect_url;
    },

    /**
     * redirect to ACS page
     */
    redirect_to_acs: function () {
      // create the form element
      const form = document.createElement("form");
      document.body.appendChild(form);
      form.method = "post";
      form.action = this.response.redirect.url;

      // append the parameters as text areas
      const data = this.response.redirect.parameters;
      let obj_container = {}; // this object will contain every name=value pair one at a time
      /**
       * create inputs for each parameter and append to the form
       */
      for (const property in data) {
        obj_container = data[property];
        let input = document.createElement("input");
        input.type = "hidden";
        input.name = obj_container.name;
        input.value = obj_container.value;

        // add to form
        form.appendChild(input);
      }

      // submit the created form
      form.submit();
    },

    /**
     * returns the host
     */
    get_host: function () {
      const dummy_anchor = document.createElement("a");
      dummy_anchor.href = this.basic.endpoint;

      return (
        "https://" + dummy_anchor.host + "/v1/paymentWidgets.js?checkoutId="
      );
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
        "pages/trx-status-server.php"
      );
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
   * execute this when mounted
   */
  mounted: function () {
    /**
     * TODO:
     *  customer.browser.acceptHeader=<?php print $header; ?>"
     */

    // create new date object then get the timezone after
    const date = new Date();

    // get ip using seeip and push the rest of the data
    axios
      .get("https://ip4.seeip.org/json")
      .then((response) => {
        // get the IP from the API and push into data array
        this.basic.parameters.push("customer.ip=" + response.data.ip);
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        // push the rest of the dynamically acquired parameters into data array
        this.basic.parameters.push(
          "customer.browser.language=" + navigator.language,
          "customer.browser.screenHeight=" + window.innerHeight,
          "customer.browser.screenWidth=" + window.innerWidth,
          "customer.browser.timezone=" + date.getTimezoneOffset(),
          "customer.browser.userAgent=" + navigator.userAgent,
          "customer.browser.javaEnabled=" + navigator.javaEnabled(),
          "customer.browser.screenColorDepth=" + screen.colorDepth,
          "customer.browser.challengeWindow=5",
          "shopperResultUrl=" + this.build_return_url()
        );

        // console.trace('AJAX Call - Vue mounted: added requried paremeters for 3Ds V2')
      });
  },
});
