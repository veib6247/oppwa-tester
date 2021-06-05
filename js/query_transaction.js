const main = new Vue({
  el: "#main",
  data: {
    transaction: {
      host: "https://test.oppwa.com",
      uid: "",
      auth: "OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg=",
      entity: "8a8294174b7ecb28014b9699220015ca"
    },
    response: "",
    button: {
      in_progress: false
    },
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
    },
    submit: function () {
      // validate if all fields are complete
      if (
        this.transaction.host == "" ||
        this.transaction.uid == "" ||
        this.transaction.auth == "" ||
        this.transaction.entity == ""
      ) {
        alert("Please fill up all fields");
      } else {
        // start axios post request
        this.button.in_progress = true;
        this.response = "";

        const url = "../scripts/query_trx.php";
        let data = new URLSearchParams();
        data.append("host", this.transaction.host);
        data.append("id", this.transaction.uid);
        data.append("entity", this.transaction.entity);
        data.append("auth", this.transaction.auth);

        axios
          .post(url, data)
          .then(response => {
            this.response = response.data;
          })
          .catch(error => {
            console.log(error);
          })
          .finally(() => {
            this.button.in_progress = false;
          });
      }
    }
  }
});