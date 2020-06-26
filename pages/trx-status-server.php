<?php
# start the session to use the URL variable
session_start()
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PRT - Results</title>
  <!-- css -->
  <link rel="stylesheet" href="../css/bulma.min.css">
  <!-- fontawesome icons -->
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
  <!-- font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto+Mono&display=swap" rel="stylesheet">
  <!-- vue development version, includes helpful console warnings -->
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
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
  </div>

  <!-- hero -->
  <section class="hero is-primary">
    <div class="hero-body">
      <div class="container">
        <h1 class="title">Transaction Result</h1>
        <p class="subtitle">Merchant can display these info to their customer to let them know</p>
      </div>
    </div>
  </section>

  <!-- main section -->
  <section id="main" class="section" style="font-family: 'Roboto Mono';">
    <div class="container">

      <?php
      /**
       * use cURL for POST request
       */
      function request($host_url, $id, $entity, $auth)
      {
        $url = "$host_url/v1/payments/$id?entityId=$entity";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:Bearer $auth"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
          return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
      }

      /**
       * explode data string to find the entity ID parameter
       */
      function get_entity($str_data)
      {
        # chop the strings and locate the entityId parameter
        $entity_parameter = explode('&', $str_data);
        foreach ($entity_parameter as $item) {

          $key = explode('=', $item)[0];

          if ($key == 'entityId') {
            $value = explode('=', $item)[1];
          }
        }

        return $value;
      }

      /**
       * chop URL to get host
       */
      function get_url_host($url)
      {
        $build_url = parse_url($url);
        return $build_url['scheme'] . '://' . $build_url['host'];
      }

      ?>

      <!-- display response in a notification -->
      <div class="notification is-size-7">
        <div class="columns">
          <!-- response details -->
          <div class="column">

            <label for="" class="label is-small" style="font-family: 'Roboto Mono';">Data Sent</label>
            <div class="table-container">
              <table class="table is-striped is-hoverable is-fullwidth is-bordered">
                <thead>
                  <tr>
                    <th>Key</th>
                    <th>Value</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <span class="icon is-small is-left">
                        <i class="fas fa-external-link-square-alt"></i>
                      </span>
                      URL
                    </td>
                    <td><?= $_SESSION['url'] ?></td>
                  </tr>

                  <tr>
                    <td>
                      <span class="icon is-small is-left">
                        <i class="fas fa-table"></i>
                      </span>
                      Data String
                    </td>
                    <td>
                      <textarea name="" id="" cols="30" rows="6" class="textarea is-small" style="font-family: 'Roboto Mono';" spellcheck="false" readonly><?= htmlspecialchars($_SESSION['data']) ?></textarea>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <span class="icon is-small is-left">
                        <i class="fas fa-universal-access"></i>
                      </span>
                      Entity ID
                    </td>
                    <td><?= get_entity($_SESSION['data']) ?></td>
                  </tr>

                  <tr>
                    <td>
                      <span class="icon is-small">
                        <i class="fas fa-key"></i>
                      </span>
                      Authorization
                    </td>
                    <td><?= $_SESSION['authorization'] ?></td>
                  </tr>
                </tbody>
              </table>
            </div>


            <label for="" class="label is-small" style="font-family: 'Roboto Mono';">Data Received</label>
            <div class="table-container">
              <table class="table is-striped is-hoverable is-fullwidth is-bordered">
                <thead>
                  <tr>
                    <th>Key</th>
                    <th>Value</th>
                  </tr>
                </thead>

                <tr>
                  <td>
                    <span class="icon is-small is-left">
                      <i class="fas fa-info"></i>
                    </span>
                    Unique ID
                  </td>
                  <td><?= $_GET['id'] ?></td>
                </tr>

                <tr>
                  <td>
                    <span class="icon is-small is-left">
                      <i class="fas fa-project-diagram"></i>
                    </span>
                    ResourcePath
                  </td>
                  <td><?= $_GET['resourcePath'] ?></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- actual response -->
          <div class="column">
            <?php

            $responseData = request(get_url_host($_SESSION['url']), $_GET['id'], get_entity($_SESSION['data']), $_SESSION['authorization']);

            ?>


            <div class="field">
              <div class="control">
                <label for="" class="label is-small" style="font-family: 'Roboto Mono';">
                  <span class="icon is-small">
                    <i class="fab fa-js"></i>
                  </span>
                  JSON Response
                </label>
                <div class="control">
                  <textarea name="" id="txt_result" cols="30" rows="30" class="textarea is-small" style="font-family: 'Roboto Mono';" spellcheck="false" readonly></textarea>
                </div>
              </div>
            </div>

            <!-- testing jsonstringify -->
            <script>
              /**
               * display the response data(JSON) with proper spacing and indentation
               */
              document.getElementById('txt_result').innerHTML = JSON.stringify(<?= $responseData ?>, undefined, 4);
            </script>

            <div class="field">
              <div class="control">
                <a href="server-to-server.php" class="button is-primary is-small">Submit Another Request</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- footer -->
  <footer class="footer">
    <div class="container">
      <div class="columns">

        <div class="column">
          <h1 class="title">Links</h1>
          <ul>
            <li><a href="https://paymentknowledgebase.com/" target="_blank">Payment Knowledgebase</a></li>
            <li><a href="https://docs.oppwa.com/" target="_blank">OPPWA API Documentation</a></li>
            <li><a href="https://docs.oppwa.com/support/3d-secure-2.0-guide" target="_blank">3D Secure 2.0 Integration Guide</a></li>
            <li><a href="https://paymentknowledgebase.com/pages/viewpage.action?pageId=68893760" target="_blank">3D Secure 2.0 Simulator (PSP) Docs</a></li>
            <li><a href="https://paymentknowledgebase.com/pages/viewpage.action?pageId=67272837" target="_blank">Technical Integration Guide (PSP) Docs</a></li>
            <li><a href="https://esupport.force.com/ACIHome" target="_blank">ACI eSupport</a></li>
            <li><a href="https://docs.google.com/spreadsheets/d/1mDGVT-9mqMqt3sm482imvSuervuMInzvgdpyDzuUmAU/edit?usp=sharing" target="_blank">Scripts Repository</a></li>
            <li><a href="https://192.168.0.48:8888/integration/" target="_blank">COPYandPAY Integration Guide - Internal</a></li>
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

  <script src="../js/nav_bar_toggle.js"></script>
</body>

</html>