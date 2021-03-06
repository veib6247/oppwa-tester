<?php
# start the session to use the URL variable
session_start()
?>

<!DOCTYPE html>
<html lang="en" class="has-navbar-fixed-top">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PRT - Results</title>
  <!-- css -->
  <link rel="stylesheet" href="../css/bulma.min.css">
  <!-- fontawesome icons -->
  <?php require "component/fontawesome_kit_url.php" ?>
  <!-- font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto+Mono&display=swap" rel="stylesheet">
  <!-- site tab icon -->
  <link rel="icon" href="../src/icons/api.svg">
  <!-- vue development version, includes helpful console warnings -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script> -->
  <!-- production version, optimized for size and speed -->
  <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
</head>

<body>
  <div id="main">
    <!-- navbar -->
    <nav id="nav" class="navbar is-fixed-top is-light" role="navigation" aria-label="main navigation">
      <!-- brand name and logo -->
      <?php 
        // this will pull up a component that has the nav brand logo/emoji
        require "component/nav_brand.php"
      ?>

      <!-- menus -->
      <?php 
        // this will pull up a component that has all the menu items
        require "component/menu.php"
      ?>
    </nav>
  </div>

  <!-- hero -->
  <section class="hero is-primary"
    style="background-image: url(../src/images/pekora.png); background-position: center;">
    <div class="hero-body">
      <div class="container is-fluid">
        <h1 class="title">Transaction Result</h1>
        <p class="subtitle">Merchant can display these info to their customer to let them know</p>
      </div>
    </div>


  </section>

  <!-- main section -->
  <section class="section" style="font-family: 'Roboto Mono';">
    <div class="container is-fluid">

      <?php
      /**
       * use cURL for POST request
       */
      function request($host_url, $id, $entity, $auth)
      {
        $url = "$host_url/v1/checkouts/$id/payment?entityId=$entity";

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

            <h1 class="subtitle">Detail Overview (Internal)</h1>
            <hr />

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
                    <td><?= $_SESSION['cnp_url'] ?></td>
                  </tr>

                  <tr>
                    <td>
                      <span class="icon is-small is-left">
                        <i class="fas fa-table"></i>
                      </span>
                      Data String
                    </td>
                    <td>
                      <textarea name="" id="" cols="30" rows="6" class="textarea is-small"
                        style="font-family: 'Roboto Mono';" spellcheck="false"
                        readonly><?= htmlspecialchars($_SESSION['cnp_data']) ?></textarea>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <span class="icon is-small is-left">
                        <i class="fas fa-universal-access"></i>
                      </span>
                      Entity ID
                    </td>
                    <td><?= get_entity($_SESSION['cnp_data']) ?></td>
                  </tr>

                  <tr>
                    <td>
                      <span class="icon is-small">
                        <i class="fas fa-key"></i>
                      </span>
                      Authorization
                    </td>
                    <td><?= $_SESSION['cnp_authorization'] ?></td>
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
                    Checkout ID
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
            $responseData = request(get_url_host($_SESSION['cnp_url']), $_GET['id'], get_entity($_SESSION['cnp_data']), $_SESSION['cnp_authorization']);
            ?>

            <h1 class="subtitle">Step 3. Get the payment status</h1>
            <hr />
            <div class="field">
              <div class="control">
                <label for="" class="label is-small" style="font-family: 'Roboto Mono';">
                  <span class="icon is-small">
                    <i class="fab fa-js"></i>
                  </span>
                  JSON Response
                </label>
                <div class="control">
                  <textarea name="" id="txt_result" cols="30" rows="30" class="textarea is-small"
                    style="font-family: 'Roboto Mono';" spellcheck="false" readonly></textarea>
                </div>
              </div>
            </div>

            <script>
            /**
             * display the response data(JSON) with proper spacing and indentation
             */
            document.getElementById('txt_result').innerHTML = JSON.stringify(<?= $responseData ?>, undefined, 4);
            </script>

            <div class="field">
              <div class="control">
                <a href="copy-and-pay.php" class="button is-primary is-small">
                  <span class="icon is-small">
                    <i class="fas fa-redo"></i>
                  </span>
                  <span>Submit Another Request</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>



  <!-- footer -->
  <footer class="footer">
    <div class="container is-fluid">
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
            <li><a href="https://paymentknowledgebase.com/pages/viewpage.action?pageId=67272837"
                target="_blank">Technical Integration Guide (PSP) Docs</a></li>
            <li><a href="https://esupport.force.com/ACIHome" target="_blank">ACI eSupport</a></li>
            <li><a
                href="https://docs.google.com/spreadsheets/d/1mDGVT-9mqMqt3sm482imvSuervuMInzvgdpyDzuUmAU/edit?usp=sharing"
                target="_blank">Scripts Repository</a></li>
            <li><a href="https://192.168.0.48:8888/integration/" target="_blank">COPYandPAY Integration Guide -
                Internal</a></li>
          </ul>
        </div>

        <div class="column">
          <h1 class="title">About</h1>
          <p>
            This page is meant to test your transactions if you don't have an online shop yet but want to test the <a
              href="https://docs.oppwa.com/tutorials/integration-guide" target="_blank">OPP API</a> from <a
              href="https://www.aciworldwide.com/products/global-payment-gateway" target="_blank">ACI.</a>
          </p>
        </div>
      </div>
    </div>
  </footer>

  <script src="../js/nav_bar_toggle.js"></script>
</body>

</html>