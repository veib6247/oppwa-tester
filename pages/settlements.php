<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PRT - Settlements</title>
  <!-- css -->
  <link rel="stylesheet" href="../css/bulma.min.css">
  <!-- fontawesome icons -->
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  <!-- font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto+Mono&display=swap" rel="stylesheet">
  <!-- vue development version, includes helpful console warnings -->
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <!-- axios, for making http requests -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <!-- site tab icon -->
  <link rel="icon" href="../src/icons/api.svg">
</head>

<body>
  <div id="main">
    <!-- navbar -->
    <nav id="nav" class="navbar" role="navigation" aria-label="main navigation">
      <!-- brand name and logo -->
      <div class="navbar-brand">
        <a class="navbar-item" href="#nav">
          <img src="../src/logos/prt_logo.png">
        </a>

        <!-- burger minimizes on mobile -->
        <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="nav_menu" :class="{'is-active': is_nav_burger_visible}" @click="toggle_burger">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <!-- menus -->
      <div id="nav_menu" class="navbar-menu" :class="{'is-active': is_menu_visible}">
        <div class="navbar-start">
          <a class="navbar-item" href="copy-and-pay.php">
            <span class="icon is-medium">
              <i class="far fa-credit-card"></i>
            </span>
            <span>
              CopyandPay
            </span>


          </a>

          <a class="navbar-item" href="server-to-server.php">
            <span class="icon is-medium">
              <i class="fas fa-code-branch"></i>
            </span>
            <span>
              Server to Server
            </span>

          </a>

          <a class="navbar-item" href="query-transaction.php">
            <span class="icon is-medium">
              <i class="fas fa-search"></i>
            </span>
            <span>
              Query Transaction
            </span>

          </a>

          <a class="navbar-item" href="settlements.php">
            <span class="icon is-medium">
              <i class="fa fa-table" aria-hidden="true"></i>
            </span>
            <span>
              Settlements
            </span>

          </a>

        </div>

      </div>
    </nav>

    <!-- hero -->
    <section class="hero is-primary">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">
            Settlement Reports
          </h1>
          <p class="subtitle">
            Use the gateway's API to generate your reports
          </p>
        </div>
      </div>
    </section>

    <!-- main section -->
    <section class="section" style="font-family: 'Roboto Mono';">
      <div class="container">

        <div class="notification">

          <!-- endpoint -->
          <div class="field">
            <label for="" class="label is-small">API Endpoint</label>
            <div class="control has-icons-left">
              <input type="text" class="input is-small" placeholder="https://test.oppwa.com/v1/checkouts" style="font-family: 'Roboto Mono';" spellcheck="FALSE" v-model="fields.endpoint">

              <span class="icon is-small is-left">
                <i class="fas fa-external-link-square-alt"></i>
              </span>
            </div>
            <p class="help">Indicates where to sent the request</p>
          </div>



          <div class="columns">

            <!-- left side, all fields -->
            <div class="column">

              <!-- auth bearer -->
              <label for="" class="label is-small">Authorization</label>
              <div class="field has-addons">
                <div class="control">
                  <a class="button is-static is-small">
                    <span class="icon">
                      <i class="fas fa-key"></i>
                    </span>
                    <span>Bearer</span>

                  </a>
                </div>
                <div class="control is-expanded">
                  <input class="input is-small" type="text" placeholder="OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg=" style="font-family: 'Roboto Mono';" spellcheck="FALSE" v-model="fields.bearer">
                </div>
              </div>

              <!-- paramaters -->
              <div class="field">
                <label for="" class="label is-small">
                  <span class="icon is-small">
                    <i class="fas fa-ruler-combined"></i>
                  </span>
                  Parameters
                </label>
                <div class="control" :class="{'is-loading': button.in_progress}">
                  <textarea name="" id="txt_params" cols="30" rows="15" class="textarea is-small" style="font-family: 'Roboto Mono';" spellcheck="false" v-model="build_parameter_string"></textarea>
                </div>
              </div>

              <!-- submit button -->
              <div class="field">
                <div class="control">
                  <a class="button is-small is-primary" @click="submit" :class="{'is-loading': button.in_progress}">
                    <span class="icon is-small">
                      <i class="far fa-paper-plane"></i>
                    </span>
                    <span>
                      Submit
                    </span>
                  </a>
                </div>
              </div>

            </div>

            <!-- right side, results -->
            <div class="column">
              <progress class="progress is-medium is-warning" max="100" v-if="button.in_progress">30%</progress>

              <div class="field" v-if="response.data">
                <div class="control">
                  <label for="" class="label is-small" style="font-family: 'Roboto Mono';">
                    <span class="icon is-small">
                      <i class="fab fa-js"></i>
                    </span>
                    JSON Response
                  </label>
                  <div class="control">
                    <textarea name="" id="" cols="30" rows="35" class="textarea is-small" style="font-family: 'Roboto Mono';" spellcheck="false" readonly>{{response.data}}</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
    </section>
  </div>


  <!-- footer -->
  <footer class="footer">
    <div class="container">
      <div class="columns">

        <div class="column">
          <h1 class="title">Links</h1>
          <ul>
            <li><a href="https://paymentknowledgebase.com/" target="_blank">Payment Knowledgebase</a></li>
            <li><a href="https://docs.oppwa.com/" target="_blank">OPPWA API Documentation</a></li>
            <li><a href="https://docs.oppwa.com/support/3d-secure-2.0-guide" target="_blank">3D Secure 2.0 Integration
                Guide</a></li>
            <li><a href="https://paymentknowledgebase.com/pages/viewpage.action?pageId=68893760" target="_blank">3D
                Secure 2.0 Simulator (PSP) Docs</a></li>
            <li><a href="https://paymentknowledgebase.com/pages/viewpage.action?pageId=67272837" target="_blank">Technical Integration Guide (PSP) Docs</a></li>
            <li><a href="https://esupport.force.com/ACIHome" target="_blank">ACI eSupport</a></li>
            <li><a href="https://docs.google.com/spreadsheets/d/1mDGVT-9mqMqt3sm482imvSuervuMInzvgdpyDzuUmAU/edit?usp=sharing" target="_blank">Scripts Repository</a></li>
            <li><a href="https://192.168.0.48:8888/integration/" target="_blank">COPYandPAY Integration Guide -
                Internal</a></li>
          </ul>
        </div>

        <div class="column">
          <h1 class="title">About</h1>
          <p>
            This page is meant to test your transactions if you don't have an online shop yet but want to test the <a href="https://docs.oppwa.com/tutorials/integration-guide" target="_blank">OPP API</a> from <a href="https://www.aciworldwide.com/products/global-payment-gateway" target="_blank">ACI.</a>
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- js -->
  <script src="../js/settlements.js"></script>
</body>

</html>